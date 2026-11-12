<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CargoTrace') }} — Masuk</title>

        <!-- Fontawesome 6 CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Tailwind CSS CDN (Memastikan semua class CSS langsung aktif tanpa perlu build npm) -->
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            }
        </style>
    </head>
    <body class="bg-slate-100 text-slate-900 antialiased min-h-screen flex flex-col justify-center items-center p-4">
        <!-- Logo CargoTrace Maritim -->
        <div class="mb-4 text-center">
            <a href="/" class="inline-flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-xl bg-sky-500 flex items-center justify-center text-white shadow-lg group-hover:bg-sky-400 transition">
                    <i class="fa-solid fa-anchor text-2xl"></i>
                </div>
                <div class="text-left">
                    <span class="text-2xl font-black tracking-tight text-slate-900 block leading-tight">CargoTrace</span>
                    <span class="text-[11px] font-bold text-slate-500 tracking-wider uppercase">Global Shipment Visibility</span>
                </div>
            </a>
        </div>

        <!-- Kartu Formulir Login -->
        <div class="w-full sm:max-w-md bg-white border border-slate-200/90 shadow-xl rounded-2xl overflow-hidden p-6 sm:p-8">
            {{ $slot }}
        </div>

        <p class="text-xs text-slate-400 mt-6 text-center">
            &copy; 2026 CargoTrace — Sistem Pelacakan Logistik & Visibilitas Pengiriman Global
        </p>
    </body>
</html>