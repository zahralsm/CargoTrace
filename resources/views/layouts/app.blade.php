<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CargoTrace') }} — Sistem Pelacakan Logistik Global</title>

        <!-- Favicon Logo Jangkar CargoTrace -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><rect width='512' height='512' rx='128' fill='%230284c7'/><path fill='white' d='M256 112c-26.5 0-48 21.5-48 48s21.5 48 48 48 48-21.5 48-48-21.5-48-48-48zm-32 144v-16h64v16c61.9 0 112 50.1 112 112h32c0-79.5-64.5-144-144-144v-16c44.2 0 80-35.8 80-80s-35.8-80-80-80-80 35.8-80 80c0 44.2 35.8 80 80 80v16c-79.5 0-144 64.5-144 144h32c0-61.9 50.1-112 112-112zM144 384c0 61.9 50.1 112 112 112s112-50.1 112-112h48c0 88.4-71.6 160-160 160S96 472.4 96 384h48z'/></svg>">

        <!-- Google Fonts: Inter (Font Standar Modern yang dipakai aplikasi populer seperti Shopee/Tokopedia) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                        }
                    }
                }
            }
        </script>

        <!-- Standarisasi Font untuk Seluruh Elemen -->
        <style>
            * {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            }
        </style>

        <!-- FontAwesome Icons CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

        <!-- Chart.js CDN (Slide 14) -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Leaflet Map CDN (Slide 09) -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-100 text-slate-800">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Header Halaman -->
            @isset($header)
                <header class="bg-white border-b border-slate-200">
                    <div class="max-w-7xl mx-auto py-3.5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Konten Halaman -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>