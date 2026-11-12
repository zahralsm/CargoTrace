<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Shipment;
use App\Models\TrackingEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TrackingService
{
    protected ShipmentService $shipmentService;

    public function __construct(ShipmentService $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    /**
     * Tambahkan titik tracking baru (Slide 07)
     */
    public function addEvent(Shipment $shipment, array $data, User $actor): TrackingEvent
    {
        $eventTime = Carbon::parse($data['event_time'] ?? Carbon::now());

        // Validasi Kronologis: Waktu event baru tidak boleh lebih mundur dari event terakhir
        $latestEvent = $shipment->latestEvent;
        if ($latestEvent && $eventTime->lt($latestEvent->event_time)) {
            throw new InvalidArgumentException(
                "Waktu tracking event tidak valid! Waktu kejadian tidak boleh mendahului event sebelumnya (" . 
                $latestEvent->event_time->format('d M Y H:i') . ")."
            );
        }

        return DB::transaction(function () use ($shipment, $data, $eventTime, $actor) {
            // Buat record tracking event yang immutable (Slide 07)
            $event = TrackingEvent::create([
                'shipment_id'   => $shipment->id,
                'location_id'   => $data['location_id'] ?? null,
                'event_type'    => strtoupper($data['event_type']),
                'event_time'    => $eventTime,
                'location_name' => $data['location_name'] ?? null,
                'latitude'      => $data['latitude'] ?? null,
                'longitude'     => $data['longitude'] ?? null,
                'description'   => $data['description'],
                'created_by'    => $actor->id,
            ]);

            // Jika tipe event sama dengan nama status lifecycle dan sah untuk ditransisikan,
            // otomatis sinkronkan status kargo
            $targetStatus = strtoupper($data['event_type']);
            if ($this->shipmentService->canTransitionTo($shipment, $targetStatus)) {
                $this->shipmentService->transitionStatus(
                    $shipment, 
                    $targetStatus, 
                    "Auto-transitioned via tracking event: " . $data['description'], 
                    $actor
                );
            }

            // Catat ke Audit Log
            AuditLog::create([
                'user_id'     => $actor->id,
                'action'      => 'ADD_TRACKING_EVENT',
                'entity_type' => TrackingEvent::class,
                'entity_id'   => $event->id,
                'old_values'  => null,
                'new_values'  => [
                    'event_type' => $event->event_type,
                    'event_time' => $event->event_time->toIso8601String(),
                ],
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),
            ]);

            return $event;
        });
    }

    /**
     * Ambil seluruh riwayat timeline secara berurutan kronologis
     */
    public function getTimeline(Shipment $shipment): Collection
    {
        return $shipment->trackingEvents()
            ->with(['location', 'creator'])
            ->orderBy('event_time', 'asc')
            ->get();
    }

    /**
     * Ambil titik lokasi terakhir kargo yang valid untuk ditampilkan di peta
     */
    public function getCurrentLocation(Shipment $shipment): ?array
    {
        $latest = $shipment->latestEvent;

        if (!$latest || ($latest->latitude === null && $latest->longitude === null)) {
            // Jika belum ada event, fallback ke koordinat pelabuhan asal (origin)
            return [
                'name'      => $shipment->origin->name,
                'latitude'  => $shipment->origin->latitude,
                'longitude' => $shipment->origin->longitude,
                'timestamp' => $shipment->expected_departure,
            ];
        }

        return [
            'name'      => $latest->location_name ?? $latest->location?->name ?? 'Titik Koordinat Laut/Darat',
            'latitude'  => $latest->latitude,
            'longitude' => $latest->longitude,
            'timestamp' => $latest->event_time,
        ];
    }
}