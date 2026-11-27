<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cargo Trash — Sistem Visibilitas & Pelacakan Kargo Maritim Global</title>

    <!-- Favicon Logo Jangkar -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><rect width='512' height='512' rx='128' fill='%230284c7'/><path fill='white' d='M256 112c-26.5 0-48 21.5-48 48s21.5 48 48 48 48-21.5 48-48-21.5-48-48-48zm-32 144v-16h64v16c61.9 0 112 50.1 112 112h32c0-79.5-64.5-144-144-144v-16c44.2 0 80-35.8 80-80s-35.8-80-80-80-80 35.8-80 80c0 44.2 35.8 80 80 80v16c-79.5 0-144 64.5-144 144h32c0-61.9 50.1-112 112-112zM144 384c0 61.9 50.1 112 112 112s112-50.1 112-112h48c0 88.4-71.6 160-160 160S96 472.4 96 384h48z'/></svg>">

    <!-- Google Fonts: Inter -->
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
                        mono: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <style>
        body, button, input, select, textarea, p, h1, h2, h3, h4, h5, h6, span, a, div, td, th, li, label {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            scroll-behavior: smooth;
        }
        .fa, .fas, .far, .fal, .fad, .fab, .fa-solid, .fa-regular, .fa-brands, [class*="fa-"], [class*="fa-"]::before {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
        }
        [x-cloak] { display: none !important; }

        /* Animasi Mengambang Logo Kapal */
        @keyframes float-hero {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(0.5deg); }
        }
        .animate-float {
            animation: float-hero 4.5s ease-in-out infinite;
        }

        /* Latar Belakang Hero Putih Bersih Bernuansa Maritim (Sama dengan Tema Dashboard) */
        .hero-light-gradient {
            background: radial-gradient(circle at 50% 25%, rgba(2, 132, 199, 0.08) 0%, rgba(241, 245, 249, 0.9) 55%, #f1f5f9 100%);
        }

        /* Animasi Reveal Password ala Column (Design Spells) */
        @keyframes spell-reveal {
            0% { filter: blur(2.5px); opacity: 0.7; transform: translateY(-0.5px); }
            100% { filter: blur(0); opacity: 1; transform: translateY(0); }
        }
        @keyframes spell-mask {
            0% { filter: blur(2px); opacity: 0.7; }
            100% { filter: blur(0); opacity: 1; }
        }
        .spell-anim-reveal {
            animation: spell-reveal 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .spell-anim-mask {
            animation: spell-mask 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .spell-eye-toggle {
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.2s ease;
        }
        .spell-eye-toggle:active {
            transform: scale(0.85);
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased selection:bg-sky-500 selection:text-white">

    {{-- KOMPONEN UTAMA DENGAN ALPINE.JS STATE --}}
    <div x-data="{
        scrolledPastHero: false,
        showLoginModal: false
    }"
    @scroll.window="scrolledPastHero = (window.pageYOffset > window.innerHeight * 0.65)"
    class="relative min-h-screen">

        {{-- 1. HERO SECTION (HALAMAN PENUH / FULL-SCREEN - TEMA PUTIH BERSIH PERSIS DASHBOARD - TANPA NAVBAR DI ATAS) --}}
        <section id="hero-section" class="relative w-full h-screen min-h-[700px] flex flex-col justify-between items-center text-center px-4 sm:px-6 lg:px-8 hero-light-gradient overflow-hidden border-b border-slate-200/80">
            
            {{-- Elemen Dekorasi Cahaya & Aksen Grid Halus --}}
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[700px] h-[700px] bg-sky-400/10 rounded-full blur-[130px] pointer-events-none"></div>
            <div class="absolute bottom-0 inset-x-0 h-36 bg-gradient-to-t from-slate-100 to-transparent pointer-events-none"></div>

            {{-- Ruang Kosong Paling Atas (Menjamin tidak ada navigasi apapun di puncak hero) --}}
            <div class="pt-8"></div>

            {{-- Konten Utama Hero Section --}}
            <div class="max-w-4xl mx-auto flex flex-col items-center z-10 space-y-6 my-auto">
                
                {{-- Logo Utama "Cargo Trash" (Sama dengan Gaya Kartu Dashboard) --}}
                <div class="animate-float relative">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-white p-2 shadow-[0_12px_36px_rgba(2,132,199,0.18)] border border-slate-200/90 flex items-center justify-center">
                        <div class="w-full h-full bg-[#1b1f3b] rounded-[20px] flex items-center justify-center text-sky-400 text-4xl sm:text-5xl shadow-md">
                            <i class="fa-solid fa-anchor"></i>
                        </div>
                    </div>
                    {{-- Ping Beacon di Pojok Logo --}}
                    <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 border-4 border-white animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 border-4 border-white shadow-xs"></span>
                </div>

                {{-- Judul Merek "Cargo Trash" --}}
                <div class="space-y-2.5">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-[11px] font-extrabold tracking-widest uppercase bg-sky-50 text-sky-700 border border-sky-200 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
                        MARITIME CARGO LOGISTICS TELEMETRY
                    </span>

                    <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight text-slate-900">
                        Cargo <span class="text-sky-600">Trash</span>
                    </h1>
                </div>

                {{-- Teks Penjelasan Lengkap Mengenai Sistem & Kegunaan untuk Kapal Kargo --}}
                <p class="text-sm sm:text-base lg:text-lg text-slate-600 max-w-3xl leading-relaxed font-normal">
                    Pusat kendali intelijen dan visibilitas maritim modern untuk armada kapal kargo global. 
                    Dirancang untuk memonitor siklus pelayaran peti kemas secara <em>real-time</em>, mendeteksi potensi anomali 
                    keterlambatan cuaca ekstrem (*SLA Delay Engine*), integrasi koordinat GIS maritim pelabuhan internasional, 
                    serta memberikan kendali operasional komprehensif bagi Administrator, Operator Armada, dan Pemilik Muatan.
                </p>

                {{-- Tombol Cepat Aksi di Hero --}}
                <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                    <button @click="showLoginModal = true" class="px-7 py-3.5 rounded-2xl bg-[#1b1f3b] hover:bg-sky-600 text-white font-extrabold text-sm shadow-md hover:shadow-sky-500/25 transition-all duration-200 transform hover:-translate-y-0.5 flex items-center gap-2.5 cursor-pointer">
                        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                        <span>Buka Portal Login</span>
                    </button>

                    <a href="#fitur-section" class="px-6 py-3.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm border border-slate-200/90 shadow-xs hover:border-sky-300 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-compass text-sky-600"></i>
                        <span>Eksplorasi Fitur Sistem</span>
                    </a>
                </div>
            </div>

            {{-- Indikator Scroll di Bagian Bawah Hero --}}
            <div class="pb-10 z-10">
                <a href="#fitur-section" class="flex flex-col items-center gap-2 text-slate-400 hover:text-sky-600 transition group text-xs ">
                    <span class="tracking-wider uppercase text-[10px] font-bold">Gulir ke Bawah</span>
                    <div class="w-7 h-10 rounded-full border-2 border-slate-300 group-hover:border-sky-500 flex items-start justify-center p-1.5 transition bg-white/70 shadow-2xs">
                        <div class="w-1.5 h-2 bg-sky-500 rounded-full animate-bounce"></div>
                    </div>
                </a>
            </div>
        </section>

        {{-- 2. STICKY NAVBAR (HANYA MUNCUL KETIKA PENGGUNA MENGGULIR KE BAWAH MELEWATI HERO SECTION) --}}
        <header 
            class="fixed top-0 inset-x-0 z-50 transition-all duration-500 transform"
            :class="scrolledPastHero ? 'translate-y-0 opacity-100 pointer-events-auto' : '-translate-y-full opacity-0 pointer-events-none'">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-3">
                <div class="bg-white/95 backdrop-blur-xl rounded-2xl border border-slate-200/90 shadow-md px-6 py-3.5 flex items-center justify-between">
                    
                    {{-- Sisi Kiri Navbar: Logo & Nama "Cargo Trash" --}}
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#1b1f3b] text-sky-400 flex items-center justify-center text-base font-bold shadow-xs">
                            <i class="fa-solid fa-anchor"></i>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-black tracking-tight text-slate-900">Cargo <span class="text-sky-600">Trash</span></span>
                            <span class="hidden sm:inline-block px-2 py-0.5 rounded text-[9px] font-bold bg-sky-50 text-sky-700 border border-sky-200">MARITIM</span>
                        </div>
                    </div>

                    {{-- 3. ISI NAVBAR: KOSONG TANPA MENU STANDAR, HANYA SATU TOMBOL LOGIN MENONJOL DI KANAN --}}
                    <div>
                        <button 
                            @click="showLoginModal = true" 
                            class="px-6 py-2.5 rounded-xl bg-[#1b1f3b] hover:bg-sky-600 text-white font-extrabold text-xs sm:text-sm shadow-md hover:shadow-sky-500/25 transition-all duration-200 transform hover:-translate-y-0.5 flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                            <span>Login</span>
                        </button>
                    </div>

                </div>
            </div>
        </header>

        {{-- 4, 5, 6. MODAL LOGIN LANGSUNG (FORM USERNAME & PASSWORD PERSIS SEPERTI DESAIN LOGIN SEBELUMNYA) --}}
        <div 
            x-show="showLoginModal" 
            x-cloak
            class="fixed inset-0 z-[99999] overflow-y-auto"
            style="display: none;"
            @keydown.escape.window="showLoginModal = false">
            
            {{-- Backdrop Transparan Ringan dengan Blur --}}
            <div 
                x-show="showLoginModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
                @click="showLoginModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
                <div 
                    x-show="showLoginModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all w-full max-w-md border border-slate-200/90">
                    
                    {{-- Header Modal: Elegan Navy #1b1f3b (Seragam dengan Komponen Dashboard) --}}
                    <div class="bg-[#1b1f3b] px-6 py-4 flex items-center justify-between text-white border-b border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-base border border-sky-400/30">
                                <i class="fa-solid fa-anchor"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black tracking-tight text-white">Portal Masuk Cargo Trash</h3>
                                <p class="text-[11px] text-slate-300">Sistem Pelacakan Kapal Maritim</p>
                            </div>
                        </div>
                        <button type="button" @click="showLoginModal = false" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    {{-- Isi Modal: FORM INPUT USERNAME / EMAIL & PASSWORD LANGSUNG --}}
                    <div class="p-6 sm:p-7 space-y-5">
                        
                        {{-- Ikon & Header Form Masuk --}}
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 mb-2 border border-sky-100 shadow-xs">
                                <i class="fa-solid fa-right-to-bracket text-lg"></i>
                            </div>
                            <h4 class="text-lg font-black text-slate-900 tracking-tight">Masuk ke Portal</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Masukkan kredensial akun Anda untuk mengakses dashboard</p>
                        </div>

                        {{-- Formulir Autentikasi Langsung --}}
                        <form method="POST" action="{{ route('login') }}" class="space-y-4 text-left">
                            @csrf

                            {{-- Input Email / Username --}}
                            <div>
                                <label for="modal-email" class="block font-bold text-xs text-slate-700 mb-1.5">Alamat Email / Username</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-regular fa-envelope text-xs"></i>
                                    </span>
                                    <input id="modal-email" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           autofocus 
                                           placeholder="nama@cargotrace.com"
                                           class="w-full pl-10 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-900 transition shadow-2xs placeholder-slate-400" />
                                </div>
                            </div>

                            {{-- Input Kata Sandi dengan Animasi Reveal Column (Design Spells) --}}
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="modal-password" class="block font-bold text-xs text-slate-700">Kata Sandi</label>
                                    @if (Route::has('password.request'))
                                        <a class="text-[11px] text-sky-600 hover:text-sky-800 font-semibold transition" href="{{ route('password.request') }}">
                                            Lupa kata sandi?
                                        </a>
                                    @endif
                                </div>
                                <div x-data="{ showPass: false }" class="relative group/pass">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within/pass:text-sky-600 transition-colors duration-200">
                                        <i class="fa-solid fa-lock text-xs"></i>
                                    </span>
                                    <input id="modal-password" 
                                           :type="showPass ? 'text' : 'password'" 
                                           name="password" 
                                           required 
                                           placeholder="••••••••"
                                           class="w-full pl-10 pr-11 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-900 transition-all duration-200 shadow-2xs placeholder-slate-400 font-sans"
                                           :class="showPass ? 'spell-anim-reveal tracking-normal font-medium' : 'spell-anim-mask tracking-widest'" />

                                    <!-- Tombol Reveal Animasi Column Design Spells -->
                                    <button type="button" 
                                            @click="showPass = !showPass" 
                                            title="Tampilkan / Sembunyikan Kata Sandi"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-sky-600 focus:outline-none transition-colors duration-200 group/btn cursor-pointer">
                                        <div class="spell-eye-toggle relative w-7 h-7 rounded-lg flex items-center justify-center hover:bg-slate-100 group-hover/btn:text-sky-600 transition-all duration-200">
                                            <!-- Icon Mata Terbuka -->
                                            <svg x-show="showPass"
                                                 x-cloak
                                                 x-transition:enter="transition ease-out duration-300"
                                                 x-transition:enter-start="opacity-0 scale-50 rotate-45"
                                                 x-transition:enter-end="opacity-100 scale-100 rotate-0"
                                                 x-transition:leave="transition ease-in duration-150"
                                                 x-transition:leave-start="opacity-100 scale-100 rotate-0"
                                                 x-transition:leave-end="opacity-0 scale-50 -rotate-45"
                                                 class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>

                                            <!-- Icon Mata Tertutup / Slash -->
                                            <svg x-show="!showPass"
                                                 x-transition:enter="transition ease-out duration-300"
                                                 x-transition:enter-start="opacity-0 scale-50 -rotate-45"
                                                 x-transition:enter-end="opacity-100 scale-100 rotate-0"
                                                 x-transition:leave="transition ease-in duration-150"
                                                 x-transition:leave-start="opacity-100 scale-100 rotate-0"
                                                 x-transition:leave-end="opacity-0 scale-50 rotate-45"
                                                 class="w-4 h-4 text-slate-400 group-hover/btn:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            {{-- Ingat Saya --}}
                            <div class="flex items-center pt-1">
                                <input id="modal-remember" type="checkbox" name="remember" checked class="w-4 h-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500 transition cursor-pointer">
                                <label for="modal-remember" class="ms-2 text-xs text-slate-600 font-medium cursor-pointer">Ingat saya di perangkat ini</label>
                            </div>

                            {{-- Tombol Masuk Utama --}}
                            <div class="pt-2">
                                <button type="submit" class="w-full py-3 px-4 bg-[#1b1f3b] hover:bg-sky-600 text-white font-bold rounded-xl text-xs shadow-md hover:shadow-sky-500/25 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer group">
                                    <span>Masuk ke Sistem</span>
                                    <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>

        {{-- 7. KONTEN LANDING PAGE DI BAWAH HERO SECTION (TEMA BENTO GRID PUTIH BERSIH) --}}
        <main id="fitur-section" class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-16">

            {{-- Bagian 1: 3 Pilar Kapabilitas Utama Cargo Trash --}}
            <section class="space-y-10">
                <div class="text-center max-w-2xl mx-auto space-y-2.5">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-sky-50 text-sky-700 border border-sky-200">
                        KEUNGGULAN OPERASIONAL
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Arsitektur Logistik Modern untuk Kapal Kargo
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Menghubungkan rantai pasok maritim dengan teknologi telemetri presisi tinggi untuk meminimalisir risiko operasional di lautan lepas.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Fitur 1 --}}
                    <div class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-xs hover:border-sky-400 hover:shadow-md transition-all duration-300 space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl font-bold border border-sky-100">
                            <i class="fa-solid fa-satellite-dish"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900">Radar & Telemetri AIS 24/7</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Pelacakan koordinat lintang dan bujur posisi kapal kontainer secara real-time yang terintegrasi dengan peta GIS dan model bola dunia 3D.
                        </p>
                    </div>

                    {{-- Fitur 2 --}}
                    <div class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-xs hover:border-rose-400 hover:shadow-md transition-all duration-300 space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl font-bold border border-rose-100">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900">Deteksi Keterlambatan SLA</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Algoritma otomatis yang menghitung deviasi cuaca ekstrem, kecepatan knot, dan memicu peringatan dini terhadap jadwal tiba kargo.
                        </p>
                    </div>

                    {{-- Fitur 3 --}}
                    <div class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-xs hover:border-emerald-400 hover:shadow-md transition-all duration-300 space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold border border-emerald-100">
                            <i class="fa-solid fa-box-archive"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900">Siklus Manifes Multi-Moda</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Transisi status kargo yang terkunci aman dari pelabuhan asal (Created, Booked, In Transit, hingga Delivered) dengan proteksi akses IDOR.
                        </p>
                    </div>
                </div>
            </section>

            {{-- Bagian 2: Jaringan Hub Pelabuhan Maritim Dunia (Bento Card Putih Elegan) --}}
            <section class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200/90 shadow-xs hover:border-sky-300 transition-all flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="space-y-3.5 max-w-xl">
                    <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                        GLOBAL MARITIME NETWORK
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Menghubungkan Jalur Samudra dari Nusantara ke Benua Eropa & Pasifik
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Terkoneksi langsung dengan pelabuhan strategis utama seperti Tanjung Priok Jakarta, Port of Rotterdam, Tanjung Perak Surabaya, Port of Hamburg, dan Port Botany Sydney.
                    </p>
                </div>
                <div class="shrink-0">
                    <button @click="showLoginModal = true" class="px-7 py-3.5 rounded-2xl bg-[#1b1f3b] hover:bg-sky-600 text-white font-extrabold text-sm shadow-md hover:shadow-sky-500/25 transition cursor-pointer">
                        Mulai Akses Monitoring &rarr;
                    </button>
                </div>
            </section>

        </main>

        {{-- 8. FOOTER OPERASIONAL SISTEM (PERSIS SAMA DENGAN FOOTER DASHBOARD) --}}
        <footer class="bg-white border-t border-slate-200 py-6 px-4 sm:px-6 lg:px-8 text-xs text-slate-500">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-lg bg-[#1b1f3b] text-sky-400 flex items-center justify-center text-xs font-bold">
                        <i class="fa-solid fa-anchor text-[10px]"></i>
                    </div>
                    <span class="font-extrabold text-slate-800">Cargo Trash &copy; 2026</span>
                    <span class="text-slate-300">•</span>
                    <span>Sistem Visibilitas & Pelacakan Kargo Maritim Global</span>
                </div>
                <div class="flex items-center gap-3 text-[11px]">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Server AIS Online (100% Operational)
                    </span>
                    <span class="text-slate-400">UAS Web Programming 2</span>
                </div>
            </div>
        </footer>

    </div>

</body>
</html>
