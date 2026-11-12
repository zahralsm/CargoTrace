<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\ShipmentException;
use App\Services\EtaService;
use App\Services\ShipmentService;
use App\Services\TrackingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ShipmentApiController extends Controller
{
    protected ShipmentService $shipmentService;
    protected TrackingService $trackingService;
    protected EtaService $etaService;

    public function __construct(
        ShipmentService $shipmentService,
        TrackingService $trackingService,
        EtaService $etaService
    ) {
        $this->shipmentService = $shipmentService;
        $this->trackingService = $trackingService;
        $this->etaService = $etaService;
    }

    /**
     * GET /api/shipments - Daftar kargo dengan filter & pagination (Slide 12)
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Customer Scoping (Slide 05 & 13)
        $query = Shipment::query()->with(['carrier', 'origin', 'destination', 'latestEvent']);

        if ($user->isCustomer()) {
            $query->where('customer_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('current_status', $request->input('status'));
        }

        if ($request->filled('delay_status')) {
            $query->where('delay_status', $request->input('delay_status'));
        }

        $shipments = $query->latest('id')->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Data shipment berhasil diambil',
            'data'    => $shipments,
        ], 200);
    }

    /**
     * POST /api/shipments - Buat shipment baru (Operator & Admin) - Slide 12
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->isCustomer()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses Ditolak: Customer tidak memiliki hak membuat manifest pengiriman.',
            ], 403);
        }

        $validated = $request->validate([
            'customer_id'        => 'required|exists:users,id',
            'carrier_id'         => 'required|exists:carriers,id',
            'origin_id'          => 'required|exists:locations,id',
            'destination_id'     => 'required|exists:locations,id|different:origin_id',
            'cargo_type'         => 'required|string|max:255',
            'weight_kg'          => 'required|numeric|min:0.1',
            'volume_cbm'         => 'required|numeric|min:0.1',
            'expected_departure' => 'required|date',
            'expected_arrival'   => 'required|date|after:expected_departure',
            'estimated_arrival'  => 'nullable|date',
            'notes'              => 'nullable|string',
        ]);

        $shipmentNumber = $this->shipmentService->generateShipmentNumber();
        $expectedArrival = Carbon::parse($validated['expected_arrival']);
        $estimatedArrival = isset($validated['estimated_arrival']) 
            ? Carbon::parse($validated['estimated_arrival']) 
            : $expectedArrival->copy();

        $shipment = Shipment::create([
            'shipment_number'    => $shipmentNumber,
            'customer_id'        => $validated['customer_id'],
            'carrier_id'         => $validated['carrier_id'],
            'origin_id'          => $validated['origin_id'],
            'destination_id'     => $validated['destination_id'],
            'cargo_type'         => $validated['cargo_type'],
            'weight_kg'          => $validated['weight_kg'],
            'volume_cbm'         => $validated['volume_cbm'],
            'current_status'     => 'CREATED',
            'expected_departure' => Carbon::parse($validated['expected_departure']),
            'expected_arrival'   => $expectedArrival,
            'estimated_arrival'  => $estimatedArrival,
            'delay_status'       => 'ON_TIME',
            'delay_hours'        => 0,
            'delay_severity'     => 'NONE',
            'notes'              => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shipment berhasil dibuat',
            'data'    => $shipment->load(['carrier', 'origin', 'destination']),
        ], 201);
    }

    /**
     * GET /api/shipments/{id} - Detail satu shipment (Slide 12)
     */
    public function show(Request $request, $id)
    {
        $shipment = Shipment::with([
            'customer', 'carrier', 'origin', 'destination', 
            'items', 'trackingEvents', 'exceptions'
        ])->find($id);

        if (!$shipment) {
            return response()->json([
                'success' => false,
                'message' => 'Shipment tidak ditemukan',
            ], 404);
        }

        // Authorization Guard
        $user = $request->user();
        if ($user->isCustomer() && $shipment->customer_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses Ditolak: Anda tidak memiliki akses ke kargo ini.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => $shipment,
        ], 200);
    }

    /**
     * GET /api/shipments/{id}/tracking - Riwayat titik perjalanan (Slide 12)
     */
    public function tracking(Request $request, $id)
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json(['success' => false, 'message' => 'Shipment tidak ditemukan'], 404);
        }

        $user = $request->user();
        if ($user->isCustomer() && $shipment->customer_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Akses Ditolak'], 403);
        }

        $timeline = $this->trackingService->getTimeline($shipment);

        return response()->json([
            'success'  => true,
            'shipment' => $shipment->shipment_number,
            'data'     => $timeline,
        ], 200);
    }

    /**
     * POST /api/shipments/{id}/tracking - Tambah tracking checkpoint baru (Slide 12)
     */
    public function addTracking(Request $request, $id)
    {
        $user = $request->user();

        if ($user->isCustomer()) {
            return response()->json(['success' => false, 'message' => 'Akses Ditolak: Customer tidak dapat menambah tracking.'], 403);
        }

        $shipment = Shipment::find($id);
        if (!$shipment) {
            return response()->json(['success' => false, 'message' => 'Shipment tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'event_type'    => 'required|string|max:50',
            'event_time'    => 'required|date',
            'location_id'   => 'nullable|exists:locations,id',
            'location_name' => 'nullable|string',
            'latitude'      => 'nullable|numeric|between:-90,90',
            'longitude'     => 'nullable|numeric|between:-180,180',
            'description'   => 'required|string|min:5',
        ]);

        try {
            $event = $this->trackingService->addEvent($shipment, $validated, $user);

            return response()->json([
                'success' => true,
                'message' => 'Tracking event berhasil dicatat dan status kargo telah disinkronkan',
                'data'    => $event,
            ], 201);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * GET /api/carriers - Daftar armada maskapai kargo (Slide 12)
     */
    public function carriers()
    {
        return response()->json([
            'success' => true,
            'data'    => Carrier::where('is_active', true)->get(),
        ], 200);
    }

    /**
     * GET /api/locations - Daftar pelabuhan dan hub logistik (Slide 12)
     */
    public function locations()
    {
        return response()->json([
            'success' => true,
            'data'    => Location::all(),
        ], 200);
    }
}