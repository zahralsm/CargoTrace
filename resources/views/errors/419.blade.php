<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>419 - Sesi Kedaluwarsa | CargoTrace</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex flex-col justify-center items-center px-4 py-12 text-slate-100 bg-[#0c1021]">
    <div class="max-w-md w-full text-center">
        <!-- Logo Header -->
        <div class="inline-flex items-center gap-2.5 mb-8">
            <div class="w-10 h-10 rounded-xl bg-sky-500 flex items-center justify-center text-white shadow-lg">
                <i class="fa-solid fa-anchor text-xl"></i>
            </div>
            <span class="text-2xl font-black tracking-tight text-white">CargoTrace</span>
        </div>

        <!-- Card Container -->
        <div class="bg-[#1b1f3b] border border-[#292f54] rounded-2xl p-8 shadow-2xl relative overflow-hidden text-center">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>

            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
                HTTP 419 • SESSION EXPIRED
            </span>

            <h1 class="text-xl font-black text-white mt-3 mb-2">Sesi Halaman Kedaluwarsa</h1>
            <p class="text-slate-300 text-xs leading-relaxed mb-6">
                Token keamanan (CSRF) Anda telah kedaluwarsa karena halaman dibiarkan terlalu lama tidak aktif atau server baru saja diperbarui. Cukup segarkan halaman untuk melanjutkan.
            </p>

            <a href="{{ route('login') }}" class="inline-flex items-center justify-center w-full px-5 py-3 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs transition duration-200 shadow-lg shadow-sky-500/20">
                <i class="fa-solid fa-rotate-right mr-1.5"></i> Masuk Kembali ke Akun
            </a>
        </div>
    </div>
</body>
</html>
