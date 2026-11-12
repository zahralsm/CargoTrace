<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\ShipmentException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Base Query dengan pembatasan Role (Slide 05: Customer hanya melihat kargo miliknya)
        $query = Shipment::query()->with(['customer', 'carrier', 'origin', 'destination', 'latestEvent', 'openExceptions']);

        if ($user->isCustomer()) {
            $query->where('customer_id', $user->id);
        }

        // 2. Fitur Pencarian & Filter Server-Side (Slide 10)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('shipment_number', 'like', "%{$search}%")
                  ->orWhere('cargo_type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('current_status', $request->input('status'));
        }

        if ($request->filled('carrier_id')) {
            $query->where('carrier_id', $request->input('carrier_id'));
        }

        if ($request->filled('delay_status')) {
            $query->where('delay_status', $request->input('delay_status'));
        }

        // 3. Ambil data dengan server-side pagination (10 data per halaman)
        $shipments = $query->latest('id')->paginate(10)->withQueryString();

        // 4. Hitung Metrik Operasional Real-time dari Database (Bukan data statis / hardcoded)
        $metricQuery = Shipment::query();
        if ($user->isCustomer()) {
            $metricQuery->where('customer_id', $user->id);
        }

        $totalShipments = (clone $metricQuery)->count();
        $inTransitCount = (clone $metricQuery)->where('current_status', 'IN_TRANSIT')->count();
        $deliveredCount = (clone $metricQuery)->where('current_status', 'DELIVERED')->count();
        $delayedCount   = (clone $metricQuery)->where('delay_status', 'DELAYED')->count();

        // On-Time Delivery Rate KPI
        $onTimeRate = $totalShipments > 0 
            ? round((($totalShipments - $delayedCount) / $totalShipments) * 100, 1) 
            : 100.0;

        // Total Exception yang masih berstatus OPEN atau INVESTIGATING
        $openExceptionsCount = ShipmentException::where('status', '!=', 'RESOLVED')
            ->whereHas('shipment', function ($q) use ($user) {
                if ($user->isCustomer()) {
                    $q->where('customer_id', $user->id);
                }
            })->count();

        // 5. Transformasi Data untuk Peta Interaktif Leaflet.js
        $mapShipments = (clone $metricQuery)
            ->with(['origin', 'destination', 'latestEvent', 'carrier'])
            ->whereIn('current_status', ['BOOKED', 'PICKED_UP', 'IN_TRANSIT', 'DELIVERED'])
            ->limit(20)
            ->get()
            ->map(function ($s) {
                return [
                    'id'               => $s->id,
                    'number'           => $s->shipment_number,
                    'status'           => $s->current_status,
                    'delay_status'     => $s->delay_status,
                    'carrier'          => $s->carrier->name,
                    'cargo'            => $s->cargo_type,
                    'origin'           => [
                        'name' => $s->origin->name,
                        'lat'  => (float) $s->origin->latitude,
                        'lng'  => (float) $s->origin->longitude,
                    ],
                    'destination'      => [
                        'name' => $s->destination->name,
                        'lat'  => (float) $s->destination->latitude,
                        'lng'  => (float) $s->destination->longitude,
                    ],
                    'current_location' => [
                        'name' => $s->latestEvent?->location_name ?? $s->origin->name,
                        'lat'  => (float) ($s->latestEvent?->latitude ?? $s->origin->latitude),
                        'lng'  => (float) ($s->latestEvent?->longitude ?? $s->origin->longitude),
                    ],
                ];
            });

        $carriers = Carrier::where('is_active', true)->get();
        $customers = User::where('role', 'customer')->get();
        $locations = Location::all();

        return view('dashboard', compact(
            'shipments',
            'totalShipments',
            'inTransitCount',
            'deliveredCount',
            'delayedCount',
            'onTimeRate',
            'openExceptionsCount',
            'mapShipments',
            'carriers',
            'customers',
            'locations'
        ));
    }
}