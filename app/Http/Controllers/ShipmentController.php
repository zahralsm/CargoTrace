<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Shipment;
use App\Models\ShipmentException;
use App\Models\TrackingEvent;
use App\Services\ShipmentService;
use App\Services\TrackingService;
use App\Services\WeatherService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    protected TrackingService $trackingService;
    protected WeatherService $weatherService;
    protected ShipmentService $shipmentService;

    public function __construct(
        TrackingService $trackingService, 
        WeatherService $weatherService,
        ShipmentService $shipmentService
    ) {
        $this->trackingService = $trackingService;
        $this->weatherService = $weatherService;
        $this->shipmentService = $shipmentService;
    }

    /**
     * Membuat Shipment Baru (Operator & Admin) - Slide 05 & 06
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->isCustomer()) {
            abort(403, 'Akses Ditolak: Customer tidak memiliki hak membuat manifest pengiriman.');
        }

        $validated = $request->validate([
            'customer_id'        => 'required|exists:users,id',
            'carrier_id'         => 'required|exists:carriers,id',
            'origin_id'          => 'required|exists:locations,id',
            'destination_id'     => 'required|exists:locations,id|different:origin_id',
            'cargo_type'         => 'required|string|max:255',
            'weight_kg'          => 'required|numeric|min:1',
            'volume_cbm'         => 'required|numeric|min:0.1',
            'expected_departure' => 'required|date',
            'expected_arrival'   => 'required|date|after:expected_departure',
            'notes'              => 'nullable|string',
        ]);

        $shipmentNumber = $this->shipmentService->generateShipmentNumber();
        $expectedArrival = Carbon::parse($validated['expected_arrival']);

        Shipment::create([
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
            'estimated_arrival'  => $expectedArrival->copy(),
            'delay_status'       => 'ON_TIME',
            'delay_hours'        => 0,
            'delay_severity'     => 'NONE',
            'notes'              => $validated['notes'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('success', "Manifest kargo baru {$shipmentNumber} berhasil didaftarkan!");
    }

    /**
     * Halaman Detail Shipment & Visualisasi Peta Khusus (Slide 07 & 09)
     */
    public function show(Shipment $shipment)
    {
        $user = Auth::user();

        // 1. Otorisasi Akses (Slide 05: Customer hanya berhak melihat kargo miliknya)
        if ($user->isCustomer() && $shipment->customer_id !== $user->id) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk melihat manifest kargo ini.');
        }

        // 2. Load Relasi Lengkap
        $shipment->load([
            'customer',
            'carrier',
            'origin',
            'destination',
            'items',
            'trackingEvents.location',
            'trackingEvents.creator',
            'exceptions.resolver'
        ]);

        // 3. Panggil API Cuaca Live untuk Pelabuhan Asal & Tujuan (Slide 09)
        $originWeather = $this->weatherService->getWeather(
            (float) $shipment->origin->latitude, 
            (float) $shipment->origin->longitude
        );

        $destinationWeather = $this->weatherService->getWeather(
            (float) $shipment->destination->latitude, 
            (float) $shipment->destination->longitude
        );

        // 4. Daftar Lokasi untuk Dropdown Tambah Tracking Event (khusus Operator/Admin)
        $locations = Location::all();

        return view('shipments.show', compact(
            'shipment',
            'originWeather',
            'destinationWeather',
            'locations'
        ));
    }

    /**
     * Menambahkan Tracking Event Baru (Khusus Operator & Admin) - Slide 07
     */
    public function storeTrackingEvent(Request $request, Shipment $shipment)
    {
        $user = Auth::user();

        // Proteksi Otorisasi
        if ($user->isCustomer()) {
            abort(403, 'Customer tidak memiliki izin menambahkan tracking event.');
        }

        $validated = $request->validate([
            'event_type'    => 'required|string|max:50',
            'event_time'    => 'required|date',
            'location_id'   => 'nullable|exists:locations,id',
            'location_name' => 'nullable|string|max:255',
            'latitude'      => 'nullable|numeric|between:-90,90',
            'longitude'     => 'nullable|numeric|between:-180,180',
            'description'   => 'required|string|min:5',
        ]);

        try {
            $this->trackingService->addEvent($shipment, $validated, $user);

            return redirect()->route('shipments.show', $shipment)
                ->with('success', 'Titik tracking baru berhasil dicatat dan status kargo telah diperbarui!');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['tracking_error' => $e->getMessage()]);
        }
    }

    /**
     * Melaporkan Exception / Kendala Operasional Baru - Slide 08
     */
    public function storeException(Request $request, Shipment $shipment)
    {
        $user = Auth::user();

        if ($user->isCustomer()) {
            abort(403, 'Customer tidak memiliki hak melaporkan exception.');
        }

        $validated = $request->validate([
            'exception_type' => 'required|in:CUSTOMS_HOLD,WEATHER_DELAY,PORT_CONGESTION,DAMAGED,MISROUTED,DOCUMENTATION_ISSUE',
            'severity'       => 'required|in:LOW,MEDIUM,HIGH',
            'description'    => 'required|string|min:10',
            'detected_at'    => 'required|date',
        ]);

        $shipment->exceptions()->create([
            'exception_type' => $validated['exception_type'],
            'severity'       => $validated['severity'],
            'status'         => 'OPEN',
            'description'    => $validated['description'],
            'detected_at'    => Carbon::parse($validated['detected_at']),
        ]);

        return redirect()->route('shipments.show', $shipment)
            ->with('warning', 'Exception operasional berhasil dilaporkan!');
    }

    /**
     * Menyelesaikan Exception (RESOLVED) - Slide 08
     */
    public function resolveException(Request $request, ShipmentException $exception)
    {
        $user = Auth::user();

        if ($user->isCustomer()) {
            abort(403, 'Customer tidak memiliki hak menyelesaikan exception.');
        }

        $validated = $request->validate([
            'resolution_notes' => 'required|string|min:5',
        ]);

        $exception->update([
            'status'           => 'RESOLVED',
            'resolved_at'      => Carbon::now(),
            'resolved_by'      => $user->id,
            'resolution_notes' => $validated['resolution_notes'],
        ]);

        return redirect()->back()->with('success', 'Status gangguan (exception) berhasil diselesaikan!');
    }
}