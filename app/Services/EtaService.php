<?php

namespace App\Services;

use App\Models\Shipment;
use Carbon\Carbon;

class EtaService
{
    /**
     * Hitung status keterlambatan dan tingkat keparahannya (Sesuai Slide 08)
     */
    public function calculateDelay(Shipment $shipment, ?Carbon $newEstimatedArrival = null): array
    {
        $estimatedArrival = $newEstimatedArrival ?? $shipment->estimated_arrival;
        $expectedArrival = $shipment->expected_arrival;

        // Jika estimasi tiba lebih awal atau tepat waktu
        if ($estimatedArrival->lessThanOrEqualTo($expectedArrival)) {
            return [
                'delay_status' => 'ON_TIME',
                'delay_hours' => 0,
                'delay_severity' => 'NONE',
            ];
        }

        // Hitung selisih jam keterlambatan (pembulatan ke atas)
        $delayHours = (int) ceil($expectedArrival->diffInHours($estimatedArrival, false));

        // Tentukan tingkat keparahan berdasarkan aturan Slide 08
        $severity = $this->determineSeverity($delayHours);

        return [
            'delay_status' => 'DELAYED',
            'delay_hours' => $delayHours,
            'delay_severity' => $severity,
        ];
    }

    /**
     * Aturan Ambang Batas Keterlambatan (Slide 08):
     * 0 - 6 jam    => LOW
     * >6 - 24 jam  => MEDIUM
     * >24 jam      => HIGH
     */
    public function determineSeverity(int $delayHours): string
    {
        if ($delayHours <= 0) {
            return 'NONE';
        }

        if ($delayHours <= 6) {
            return 'LOW';
        }

        if ($delayHours <= 24) {
            return 'MEDIUM';
        }

        return 'HIGH';
    }
}