<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CargoTrace') }} — Masuk Portal Logistik</title>

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
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                            mono: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                        }
                    }
                }
            }
        </script>

        <style>
            *, ::before, ::after {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            }
            .font-mono, [class*="font-mono"], text, svg text {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            }
        </style>
    </head>
    <body class="min-h-screen antialiased bg-slate-900 text-slate-100 flex flex-col justify-between relative overflow-x-hidden bg-cover bg-center bg-no-repeat"
          style="background-image: linear-gradient(to bottom, rgba(15, 23, 42, 0.45) 0%, rgba(15, 23, 42, 0.70) 100%), url('{{ asset('images/login-bg.jpg') }}'); background-attachment: fixed;">

        <!-- NAVBAR TRANSPARAN DI ATAS -->
        <header class="w-full z-20 px-6 sm:px-12 py-5 flex items-center justify-between">
            <!-- Brand Logo CargoTrace -->
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-sky-500/90 backdrop-blur-md flex items-center justify-center text-white shadow-lg group-hover:scale-105 transition-transform duration-200 border border-white/20">
                    <i class="fa-solid fa-anchor text-xl"></i>
                </div>
                <div>
                    <span class="text-xl font-black tracking-tight text-white block drop-shadow-md">CargoTrace</span>
                    <span class="text-[10px] font-bold text-sky-200 tracking-widest uppercase block drop-shadow-sm">Global Shipment Visibility</span>
                </div>
            </a>

            <!-- Status Pill Transparan Kanan -->
            <div class="hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs text-white/90 shadow-sm font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Sistem Operasional 100%</span>
            </div>
        </header>

        <!-- KONTEN UTAMA: KARTU FORMULIR LOGIN -->
        <main class="w-full flex-1 flex items-center justify-center px-4 py-8 z-10">
            <div class="w-full sm:max-w-md bg-white/95 backdrop-blur-md border border-white/40 shadow-2xl rounded-2xl overflow-hidden p-7 sm:p-8 text-slate-900 transition-all duration-300">
                {{ $slot }}
            </div>
        </main>

        <!-- FOOTER TRANSPARAN BAWAH -->
        <footer class="w-full z-20 py-4 text-center text-xs text-white/70 drop-shadow-sm">
            &copy; 2026 CargoTrace — Sistem Visibilitas & Pelacakan Kargo Maritim Global
        </footer>

    </body>
</html>