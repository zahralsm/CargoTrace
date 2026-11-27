<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak | CargoTrace</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex flex-col justify-center items-center px-4 py-12 text-slate-100 bg-[#0c1021]">
    <div class="max-w-xl w-full text-center">
        <!-- Logo Header -->
        <div class="inline-flex items-center gap-2.5 mb-8">
            <div class="w-10 h-10 rounded-xl bg-sky-500 flex items-center justify-center text-white shadow-lg">
                <i class="fa-solid fa-anchor text-xl"></i>
            </div>
            <span class="text-2xl font-black tracking-tight text-white">CargoTrace</span>
        </div>

        <!-- Card Container -->
        <div class="bg-[#1b1f3b] border border-rose-500/30 rounded-2xl p-8 sm:p-10 shadow-2xl relative overflow-hidden text-left">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 flex items-center justify-center text-2xl shrink-0">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-500/20 text-rose-300 border border-rose-500/30">
                        HTTP 403 • FORBIDDEN
                    </span>
                    <h1 class="text-xl sm:text-2xl font-black text-white mt-1">Akses Halaman Dibatasi</h1>
                </div>
            </div>

            <p class="text-slate-300 text-sm leading-relaxed mb-6">
                {{ $exception->getMessage() ?: 'Anda tidak memiliki hak otorisasi yang cukup untuk mengakses URL atau modul ini sesuai kebijakan hak akses (Role-Based Access Control) CargoTrace.' }}
            </p>

            @auth
                <!-- Info Akun Aktif -->
                <div class="bg-[#242b4d] rounded-xl p-4 border border-[#343e6d] mb-6">
                    <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Informasi Akun Anda:</div>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-sky-600 flex items-center justify-center text-xs font-bold text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-xs font-bold text-white">{{ Auth::user()->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-black uppercase tracking-wide
                            {{ Auth::user()->isAdmin() ? 'bg-purple-900/80 text-purple-300 border border-purple-500' : (Auth::user()->isOperator() ? 'bg-amber-900/80 text-amber-300 border border-amber-500' : 'bg-emerald-900/80 text-emerald-300 border border-emerald-500') }}">
                            Peran: {{ Auth::user()->role === 'admin' ? 'Administrator' : (Auth::user()->role === 'operator' ? 'Operator' : 'Pelanggan') }}
                        </span>
                    </div>
                </div>
            @endauth

            <!-- Panduan Hak Akses Role -->
            <div class="text-xs text-slate-400 space-y-2 border-t border-[#292f54] pt-4 mb-8">
                <div class="font-bold text-slate-300 text-[11px] uppercase tracking-wider mb-2">Ketentuan Hak Akses CargoTrace:</div>
                <div class="flex items-start gap-2">
                    <i class="fa-solid fa-circle-check text-purple-400 text-[10px] mt-1 shrink-0"></i>
                    <span><strong>Administrator:</strong> Akses penuh ke seluruh sistem, termasuk Konsol Master Data (Armada Carrier, Pelabuhan Lokasi, dan Audit Log).</span>
                </div>
                <div class="flex items-start gap-2">
                    <i class="fa-solid fa-circle-check text-amber-400 text-[10px] mt-1 shrink-0"></i>
                    <span><strong>Operator Logistik:</strong> Akses operasional armada pelayaran, pendaftaran manifest kargo, pencatatan checkpoint telemetri, dan eskalasi exception. (Tidak memiliki hak akses Konsol Master Data).</span>
                </div>
                <div class="flex items-start gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-[10px] mt-1 shrink-0"></i>
                    <span><strong>Pelanggan (Customer):</strong> Akses pelacakan real-time, monitoring status kargo pribadi, dan pemesanan booking kontainer. (Tidak dapat mengakses konsol admin atau kargo milik pihak lain).</span>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto flex-1 text-center px-5 py-3 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs transition duration-200 shadow-lg shadow-sky-500/20">
                    <i class="fa-solid fa-house mr-1.5"></i> Kembali ke Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full px-5 py-3 rounded-xl bg-transparent hover:bg-[#252b4d] text-slate-300 hover:text-white border border-[#343e6d] font-bold text-xs transition duration-200">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-1.5"></i> Ganti Akun
                    </button>
                </form>
            </div>
        </div>

        <p class="text-xs text-slate-500 mt-6">
            &copy; {{ date('Y') }} CargoTrace Global Freight Management System. All rights reserved.
        </p>
    </div>
</body>
</html>
