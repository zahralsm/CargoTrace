<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Shipment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ShipmentService
{
    protected EtaService $etaService;

    // Matriks transisi status yang sah (Slide 06)
    // Kunci array adalah status saat ini, isinya adalah status berikutnya yang diperbolehkan
    private const ALLOWED_TRANSITIONS = [
        'CREATED'    => ['BOOKED'],
        'BOOKED'     => ['PICKED_UP'],
        'PICKED_UP'  => ['IN_TRANSIT'],
        'IN_TRANSIT' => ['DELIVERED'],
        'DELIVERED'  => [], // Status Terminal: tidak boleh berpindah lagi
    ];

    public function __construct(EtaService $etaService)
    {
        $this->etaService = $etaService;
    }

    /**
     * Memeriksa apakah perpindahan status diperbolehkan
     */
    public function canTransitionTo(Shipment $shipment, string $targetStatus): bool
    {
        $current = strtoupper($shipment->current_status);
        $target = strtoupper($targetStatus);

        $allowedNext = self::ALLOWED_TRANSITIONS[$current] ?? [];

        return in_array($target, $allowedNext, true);
    }

    /**
     * Jalankan perpindahan status dengan validasi ketat dan pencatatan audit log
     */
    public function transitionStatus(Shipment $shipment, string $newStatus, ?string $notes = null, ?User $actor = null): Shipment
    {
        $newStatus = strtoupper($newStatus);

        // Jika status yang dituju melanggar alur
        if (!$this->canTransitionTo($shipment, $newStatus)) {
            throw new InvalidArgumentException(
                "Transisi status tidak valid! Dari '{$shipment->current_status}' tidak dapat berpindah ke '{$newStatus}'."
            );
        }

        return DB::transaction(function () use ($shipment, $newStatus, $notes, $actor) {
            $oldStatus = $shipment->current_status;
            $now = Carbon::now();

            $shipment->current_status = $newStatus;

            // Catat waktu keberangkatan aktual jika mulai IN_TRANSIT
            if ($newStatus === 'IN_TRANSIT' && !$shipment->actual_departure) {
                $shipment->actual_departure = $now;
            }

            // Catat waktu kedatangan aktual jika DELIVERED
            if ($newStatus === 'DELIVERED') {
                $shipment->actual_arrival = $now;
            }

            if ($notes) {
                $shipment->notes = $notes;
            }

            $shipment->save();

            // Catat ke tabel Audit Log (Slide 10)
            AuditLog::create([
                'user_id'     => $actor?->id,
                'action'      => 'STATUS_TRANSITION',
                'entity_type' => Shipment::class,
                'entity_id'   => $shipment->id,
                'old_values'  => ['current_status' => $oldStatus],
                'new_values'  => ['current_status' => $newStatus, 'notes' => $notes],
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),
            ]);

            return $shipment;
        });
    }

    /**
     * Pembaruan estimasi waktu tiba (ETA) dan otomatis hitung ulang keterlambatan
     */
    public function updateEstimatedArrival(Shipment $shipment, Carbon $newEta, ?string $reason = null, ?User $actor = null): Shipment
    {
        $delayData = $this->etaService->calculateDelay($shipment, $newEta);

        $shipment->estimated_arrival = $newEta;
        $shipment->delay_status = $delayData['delay_status'];
        $shipment->delay_hours = $delayData['delay_hours'];
        $shipment->delay_severity = $delayData['delay_severity'];

        if ($reason) {
            $shipment->delay_reason = $reason;
        }

        $shipment->save();

        return $shipment;
    }

    /**
     * Buat nomor shipment unik berurutan: CTG-2026-0001, CTG-2026-0002, dst.
     */
    public function generateShipmentNumber(): string
    {
        $year = date('Y');
        $latest = Shipment::whereYear('created_at', $year)->latest('id')->first();
        $nextNumber = $latest ? ($latest->id + 1) : 1;

        return sprintf('CTG-%s-%04d', $year, $nextNumber);
    }
}