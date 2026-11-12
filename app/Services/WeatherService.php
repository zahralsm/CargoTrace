<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class WeatherService
{
    /**
     * Mengambil kondisi cuaca live berdasarkan koordinat latitude & longitude pelabuhan (Slide 09)
     */
    public function getWeather(float $latitude, float $longitude): array
    {
        // Cache data cuaca selama 30 menit berdasarkan koordinat
        $cacheKey = sprintf('weather_lat_%s_lng_%s', round($latitude, 2), round($longitude, 2));

        return Cache::remember($cacheKey, 1800, function () use ($latitude, $longitude) {
            try {
                // Laravel HTTP Client dengan batas timeout 3 detik (Slide 09)
                $response = Http::timeout(3)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'current' => 'temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code',
                    'timezone' => 'auto',
                ]);

                if ($response->successful()) {
                    $current = $response->json('current') ?? [];
                    $weatherCode = $current['weather_code'] ?? 0;

                    return [
                        'status'      => 'ONLINE',
                        'temperature' => $current['temperature_2m'] ?? null,
                        'humidity'    => $current['relative_humidity_2m'] ?? null,
                        'wind_speed'  => $current['wind_speed_10m'] ?? null,
                        'condition'   => $this->mapWeatherCode($weatherCode),
                    ];
                }

                Log::warning("Weather API returned non-200: " . $response->status());
            } catch (Throwable $e) {
                // Penanganan kegagalan jika offline atau timeout (Slide 09 & 15)
                Log::warning("Weather API failure: " . $e->getMessage());
            }

            // Fallback aman jika API pihak ketiga bermasalah
            return [
                'status'      => 'OFFLINE',
                'temperature' => null,
                'humidity'    => null,
                'wind_speed'  => null,
                'condition'   => 'Data Cuaca Sementara Tidak Tersedia',
            ];
        });
    }

    /**
     * Menerjemahkan WMO Weather Code menjadi teks yang mudah dipahami
     */
    private function mapWeatherCode(int $code): string
    {
        return match ($code) {
            0 => 'Cerah (Clear Sky)',
            1, 2, 3 => 'Sebagian Berawan (Partly Cloudy)',
            45, 48 => 'Berkabut (Foggy)',
            51, 53, 55 => 'Gerimis Ringan (Drizzle)',
            61, 63, 65 => 'Hujan Sedang (Rain)',
            71, 73, 75 => 'Bersalju (Snow)',
            80, 81, 82 => 'Hujan Deras / Badai Lokal (Rain Showers)',
            95, 96, 99 => 'Badai Petir Tropis (Thunderstorm)',
            default => 'Kondisi Normal',
        };
    }
}