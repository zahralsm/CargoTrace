<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CargoTrace') }} — Masuk</title>

        <!-- Favicon Logo Jangkar CargoTrace -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><rect width='512' height='512' rx='128' fill='%230284c7'/><path fill='white' d='M256 112c-26.5 0-48 21.5-48 48s21.5 48 48 48 48-21.5 48-48-21.5-48-48-48zm-32 144v-16h64v16c61.9 0 112 50.1 112 112h32c0-79.5-64.5-144-144-144v-16c44.2 0 80-35.8 80-80s-35.8-80-80-80-80 35.8-80 80c0 44.2 35.8 80 80 80v16c-79.5 0-144 64.5-144 144h32c0-61.9 50.1-112 112-112zM144 384c0 61.9 50.1 112 112 112s112-50.1 112-112h48c0 88.4-71.6 160-160 160S96 472.4 96 384h48z'/></svg>">

        <!-- Google Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Fontawesome 6 CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            * {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
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