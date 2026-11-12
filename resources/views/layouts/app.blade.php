<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CargoTrace') }} - Global Shipment Visibility</title>

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['"Segoe UI"', '-apple-system', 'BlinkMacSystemFont', '"Helvetica Neue"', 'Arial', 'sans-serif'],
                            mono: ['Consolas', '"Courier New"', 'monospace']
                        },
                        colors: {
                            navy: {
                                900: '#1b1f3b',
                                950: '#13162b',
                            }
                        }
                    }
                }
            }
        </script>

        <!-- Font Native System App (Bukan font AI seperti Figtree / Inter) -->
        <style>
            body, button, input, select, textarea, table {
                font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, "Helvetica Neue", Arial, sans-serif !important;
            }
            code, pre, .font-mono {
                font-family: Consolas, "Courier New", monospace !important;
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

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-slate-200">
                    <div class="max-w-7xl mx-auto py-3.5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
