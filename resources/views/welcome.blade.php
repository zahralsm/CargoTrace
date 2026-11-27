<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CargoTrace — Sistem Visibilitas & Pelacakan Kargo Maritim Global</title>

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
        .fa-solid, .fas {
            font-weight: 900 !important;
        }
        .fa-regular, .far {
            font-weight: 400 !important;
        }
        [x-cloak] { display: none !important; }

        /* Animasi Mengambang Logo Kapal */
        @keyframes float-hero {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(0.5deg); }
        }
        .animate-float {
            animation: float-hero 5s ease-in-out infinite;
        }

        /* Ambient Glow & Gradien Halus Latar Belakang Berthwise */
        .berthwise-bg {
            background: radial-gradient(circle at 50% 12%, rgba(2, 132, 199, 0.06) 0%, rgba(255, 255, 255, 1) 60%);
        }

        /* Masking Gradien Kabut Halus untuk Transisi Foto Lautan & Kapal Kargo */
        .ocean-fog-mask {
            -webkit-mask-image: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.08) 12%, rgba(0,0,0,0.6) 30%, black 55%);
            mask-image: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.08) 12%, rgba(0,0,0,0.6) 30%, black 55%);
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

        /* Animasi Launch Screen Ala Luma App (Design Spells #243) */
        @keyframes launch-ship-bob {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-7px) rotate(-0.5deg); }
        }
        .animate-launch-ship {
            animation: launch-ship-bob 3.2s ease-in-out infinite;
        }

        @keyframes wave-flow {
            0% { transform: translateX(0); }
            50% { transform: translateX(-12px); }
            100% { transform: translateX(0); }
        }
        .animate-waves {
            animation: wave-flow 3.5s ease-in-out infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.7); opacity: 0.9; }
            50% { opacity: 0.35; }
            100% { transform: scale(1.6); opacity: 0; }
        }
        .animate-pulse-ring {
            animation: pulse-ring 2.6s cubic-bezier(0.2, 0.8, 0.2, 1) infinite;
        }

        @keyframes radar-scan {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .animate-radar-scan {
            animation: radar-scan 4s linear infinite;
        }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased selection:bg-sky-500 selection:text-white">

    {{-- KOMPONEN UTAMA DENGAN ALPINE.JS STATE --}}
    <div x-data="{
        launchActive: true,
        launchProgress: 0,
        launchStatusText: 'Menginisialisasi Radar AIS...',
        showLoginModal: false,
        init() {
            this.runLaunchAnimation();
        },
        runLaunchAnimation() {
            this.launchActive = true;
            this.launchProgress = 0;
            this.launchStatusText = 'Mendeteksi Sinyal AIS Satelit Kapal...';
            
            let start = null;
            const duration = 1800; // 1.8 detik berjalan mulus di 60 FPS
            
            const step = (timestamp) => {
                if (!start) start = timestamp;
                const elapsed = timestamp - start;
                const progress = Math.min(Math.round((elapsed / duration) * 100), 100);
                this.launchProgress = progress;
                
                if (progress < 35) {
                    this.launchStatusText = 'Mendeteksi Sinyal AIS Satelit Kapal...';
                } else if (progress < 75) {
                    this.launchStatusText = 'Sinkronisasi Koridor Laut & Manifes Kontainer...';
                } else {
                    this.launchStatusText = 'Sistem Visibilitas Kargo Siap.';
                }
                
                if (elapsed < duration) {
                    requestAnimationFrame(step);
                } else {
                    setTimeout(() => {
                        this.launchActive = false;
                    }, 280);
                }
            };
            requestAnimationFrame(step);
        },
        triggerLaunchAnimation() {
            this.runLaunchAnimation();
        }
    }" class="relative min-h-screen flex flex-col">

        {{-- ANIMATED LAUNCH SCREEN ALA LUMA APP (DESIGN SPELLS #243) TEMA MARITIM CARGOTRACE --}}
        <div x-show="launchActive"
             x-cloak
             x-transition:leave="transition ease-out duration-700"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-110 filter blur-sm"
             class="fixed inset-0 z-[999999] flex flex-col items-center justify-center bg-[#070e1e] text-white overflow-hidden select-none">
            
            {{-- Latar Belakang Gradien Deep Ocean & Radial Glow --}}
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(2,132,199,0.20)_0%,rgba(7,14,30,0.98)_70%)] pointer-events-none"></div>
            
            {{-- Gelombang Radar Melingkar Memancar dari Kapal --}}
            <div class="absolute w-[460px] h-[460px] rounded-full border border-sky-500/20 animate-pulse-ring pointer-events-none"></div>
            <div class="absolute w-[320px] h-[320px] rounded-full border border-sky-400/25 pointer-events-none"></div>

            {{-- Tombol Lewati di Pojok Kanan Atas --}}
            <button @click="launchActive = false" 
                    class="absolute top-6 right-6 px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white text-xs font-semibold backdrop-blur-md transition cursor-pointer z-20">
                Lewati &times;
            </button>

            {{-- Kontainer Utama Animasi Kapal Kargo Maritim --}}
            <div class="relative z-10 flex flex-col items-center text-center max-w-sm sm:max-w-md px-6">
                
                {{-- Ilustrasi Vektor Kapal Kargo Kontainer Berlayar Membelah Ombak --}}
                <div class="animate-launch-ship relative mb-6">
                    
                    {{-- Efek Radar Scan Line --}}
                    <div class="absolute -inset-10 flex items-center justify-center pointer-events-none opacity-40">
                        <div class="w-48 h-48 rounded-full border border-dashed border-sky-400/40 animate-radar-scan"></div>
                    </div>

                    {{-- SVG Kapal Kargo & Kontainer Presisi Berwarna-warni --}}
                    <svg class="w-56 h-36 sm:w-64 sm:h-40 drop-shadow-[0_15px_30px_rgba(2,132,199,0.35)]" viewBox="0 0 320 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Air Laut & Riak Ombak Bawah -->
                        <path class="animate-waves" d="M10 165 C 60 155, 100 175, 160 165 C 220 155, 260 175, 310 165" stroke="#38bdf8" stroke-width="3" stroke-linecap="round" stroke-opacity="0.4" fill="none" />
                        <path d="M30 175 C 80 168, 120 182, 170 175 C 220 168, 250 182, 290 175" stroke="#0284c7" stroke-width="2.5" stroke-linecap="round" stroke-opacity="0.6" fill="none" />

                        <!-- Badan Kapal (Hull) Bagian Bawah Merah Maritim & Atas Navy Gelap -->
                        <path d="M40 145 L80 165 L240 165 L280 145 L255 125 L65 125 Z" fill="#e11d48" />
                        <path d="M40 145 L65 125 L255 125 L280 145 Z" fill="#1e293b" />
                        <!-- Garis Aksen Putih di Lambung Kapal -->
                        <line x1="50" y1="135" x2="270" y2="135" stroke="white" stroke-width="2" stroke-opacity="0.9" />

                        <!-- Menara Anjungan Kapal (Bridge & Superstructure) di Belakang -->
                        <rect x="210" y="80" width="38" height="45" rx="3" fill="#f8fafc" />
                        <rect x="215" y="85" width="28" height="8" rx="1.5" fill="#0284c7" />
                        <rect x="222" y="65" width="14" height="15" rx="2" fill="#cbd5e1" />
                        <!-- Tiang Radar & Sinyal Hijau/Biru -->
                        <line x1="229" y1="65" x2="229" y2="48" stroke="#f8fafc" stroke-width="2.5" />
                        <circle cx="229" cy="46" r="3.5" fill="#38bdf8" />
                        <circle cx="229" cy="46" r="6" stroke="#38bdf8" stroke-width="1.5" stroke-opacity="0.6" class="animate-ping" />

                        <!-- Tumpukan Peti Kemas (Cargo Containers) Penuh Warna ala Kargo Global -->
                        <!-- Baris Dasar -->
                        <rect x="75" y="105" width="30" height="20" rx="2" fill="#0284c7" stroke="#0369a1" stroke-width="1" />
                        <rect x="108" y="105" width="30" height="20" rx="2" fill="#f97316" stroke="#ea580c" stroke-width="1" />
                        <rect x="141" y="105" width="30" height="20" rx="2" fill="#10b981" stroke="#059669" stroke-width="1" />
                        <rect x="174" y="105" width="30" height="20" rx="2" fill="#eab308" stroke="#ca8a04" stroke-width="1" />

                        <!-- Baris Kedua -->
                        <rect x="80" y="82" width="30" height="20" rx="2" fill="#ef4444" stroke="#dc2626" stroke-width="1" />
                        <rect x="113" y="82" width="30" height="20" rx="2" fill="#06b6d4" stroke="#0891b2" stroke-width="1" />
                        <rect x="146" y="82" width="30" height="20" rx="2" fill="#6366f1" stroke="#4f46e5" stroke-width="1" />
                        <rect x="179" y="82" width="26" height="20" rx="2" fill="#f97316" stroke="#ea580c" stroke-width="1" />

                        <!-- Baris Ketiga (Puncak) -->
                        <rect x="95" y="60" width="30" height="20" rx="2" fill="#10b981" stroke="#059669" stroke-width="1" />
                        <rect x="128" y="60" width="30" height="20" rx="2" fill="#38bdf8" stroke="#0284c7" stroke-width="1" />
                        <rect x="161" y="60" width="30" height="20" rx="2" fill="#ec4899" stroke="#db2777" stroke-width="1" />

                        <!-- Haluan Kapal & Semprotan Buih Ombak Depan -->
                        <path d="M40 145 Q 30 155, 18 162" stroke="white" stroke-width="3" stroke-linecap="round" stroke-opacity="0.8" />
                        <path d="M42 148 Q 28 160, 12 167" stroke="#bae6fd" stroke-width="2" stroke-linecap="round" stroke-opacity="0.6" />
                    </svg>
                </div>

                {{-- Judul Merek & Sub-judul Telemetri --}}
                <div class="space-y-1 mb-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-sky-500/10 text-sky-400 border border-sky-400/20 mb-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>MARITIME TELEMETRY LAUNCH</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Cargo<span class="text-sky-400">Trace</span>
                    </h2>
                    <p class="text-[11px] text-slate-400 tracking-wide font-medium" x-text="launchStatusText">
                        Menghubungkan Sinyal AIS Satelit...
                    </p>
                </div>

                {{-- Progress Bar Ramping Bercahaya (0% - 100%) --}}
                <div class="w-full max-w-xs space-y-2">
                    <div class="w-full h-1.5 rounded-full bg-white/10 overflow-hidden p-0.5 border border-white/5">
                        <div class="h-full rounded-full bg-gradient-to-r from-sky-400 via-cyan-300 to-emerald-400 transition-all duration-100 ease-out shadow-[0_0_12px_rgba(56,189,248,0.7)]" 
                             :style="'width: ' + launchProgress + '%'"></div>
                    </div>
                    <div class="flex items-center justify-between text-[10px] font-mono font-bold text-slate-400">
                        <span class="tracking-widest uppercase text-[9px] text-slate-500">AIS TELEMETRY READY</span>
                        <span class="text-sky-400" x-text="launchProgress + '%'">0%</span>
                    </div>
                </div>

            </div>

        </div>

        {{-- 1. NAVBAR MINIMALIS BERTHWISE-INSPIRED (BERSIH TANPA MENU & TANPA LOGIN/BUKA PORTAL GANDA) --}}
        <header class="sticky top-0 inset-x-0 z-40 bg-white/85 backdrop-blur-xl border-b border-slate-100 transition-all">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                
                {{-- Sisi Kiri: Logo Utama & Nama CargoTrace --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#1b1f3b] text-sky-400 flex items-center justify-center text-lg font-bold shadow-sm ring-1 ring-slate-900/10">
                        <i class="fa-solid fa-anchor"></i>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-black tracking-tight text-slate-900">Cargo<span class="text-sky-600">Trace</span></span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-sky-50 text-sky-700 border border-sky-200">MARITIM</span>
                    </div>
                </div>

                {{-- Sisi Kanan: Status AIS Online & Tombol Putar Ulang Animasi Launch --}}
                <div class="flex items-center gap-3">
                    <button @click="triggerLaunchAnimation()" 
                            title="Putar Ulang Animasi Launch Maritim" 
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-600 hover:text-sky-600 hover:bg-sky-50 border border-slate-200 hover:border-sky-200 transition cursor-pointer">
                        <i class="fa-solid fa-play text-[10px] text-sky-500"></i>
                        <span>Animasi Launch</span>
                    </button>
                    
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="hidden sm:inline">AIS Online</span>
                    </span>
                </div>

            </div>
        </header>

        {{-- 2. HERO SECTION DENGAN TATA LETAK REFERENSI BERTHWISE --}}
        <section class="relative w-full berthwise-bg pt-12 sm:pt-16 pb-0 overflow-hidden border-b border-slate-200/80">
            
            {{-- Cahaya Halus Dekoratif --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-sky-400/10 rounded-full blur-[140px] pointer-events-none"></div>

            {{-- Konten Teks Hero (Atas): Terpusat, Tenang, dan Bersih --}}
            <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center space-y-6 relative z-10">
                
                {{-- Logo Utama "CargoTrace" Mengambang (Sesuai Desain Lama) --}}
                <div class="inline-flex justify-center mb-1">
                    <div class="animate-float relative">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white p-1.5 shadow-[0_10px_30px_rgba(2,132,199,0.15)] border border-slate-200/90 flex items-center justify-center">
                            <div class="w-full h-full bg-[#1b1f3b] rounded-xl flex items-center justify-center text-sky-400 text-2xl sm:text-3xl shadow-sm">
                                <i class="fa-solid fa-anchor"></i>
                            </div>
                        </div>
                        <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white animate-ping"></span>
                        <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white shadow-xs"></span>
                    </div>
                </div>

                {{-- Pill Badge Terinspirasi Berthwise ("✦ Terminal operations, with AI") --}}
                <div>
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-[11px] font-extrabold tracking-widest uppercase bg-sky-50 text-sky-700 border border-sky-200/80 shadow-xs">
                        <span class="text-sky-500 text-xs">✦</span>
                        <span>MARITIME CARGO LOGISTICS TELEMETRY</span>
                    </span>
                </div>

                {{-- Judul Utama (Headings) --}}
                <div class="space-y-2">
                    <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight text-slate-900 leading-[1.08]">
                        Cargo <span class="text-sky-600">Trace</span>
                    </h1>
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-800 tracking-tight">
                        Siklus kargo, radar AIS & telemetri, dalam satu layar tenang.
                    </p>
                </div>

                {{-- Teks Penjelasan Lengkap (Persis Kata-Kata Desain Lama) --}}
                <p class="text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed font-normal pt-1">
                    Pusat kendali intelijen dan visibilitas maritim modern untuk armada kapal kargo global. 
                    Dirancang untuk memonitor siklus pelayaran peti kemas secara <em>real-time</em>, mendeteksi potensi anomali 
                    keterlambatan cuaca ekstrem (<em>SLA Delay Engine</em>), integrasi koordinat GIS maritim pelabuhan internasional, 
                    serta memberikan kendali operasional komprehensif bagi Administrator, Operator Armada, dan Pemilik Muatan.
                </p>

                {{-- Tombol Cepat Aksi (CTA) --}}
                <div class="flex flex-wrap items-center justify-center gap-4 pt-3 pb-8">
                    <button @click="showLoginModal = true" class="px-6 py-3 rounded-full bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-600 hover:to-cyan-600 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 transition-all duration-200 transform hover:-translate-y-0.5 flex items-center gap-2 cursor-pointer">
                        <span>Buka Portal Login</span>
                        <span>↗</span>
                    </button>

                    <a href="#fitur-section" class="px-6 py-3 rounded-full bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm border border-slate-200/90 shadow-xs hover:border-sky-300 transition-all flex items-center gap-2">
                        <span>Eksplorasi Fitur Sistem</span>
                        <i class="fa-solid fa-arrow-down text-xs text-sky-600"></i>
                    </a>
                </div>

            </div>

            {{-- 3. AREA VISUAL KAPAL KARGO LAUT LEPAS + FLOATING HUD TELEMETRI (PERSIS REFERENSI BERTHWISE) --}}
            <div class="relative w-full h-[460px] sm:h-[580px] lg:h-[660px] max-w-7xl mx-auto mt-4 overflow-hidden rounded-t-[40px]">
                
                {{-- Foto Kapal Kontainer Aerial Resolusi Tinggi --}}
                <div class="absolute inset-0 ocean-fog-mask">
                    <img src="{{ asset('images/berthwise-cargo-ship.jpg') }}" 
                         alt="Kapal Kontainer Berlayar di Samudra" 
                         class="w-full h-full object-cover object-center transform scale-105 transition-transform duration-1000 ease-out hover:scale-100" />
                </div>

                {{-- Lapisan Gradien Kabut Putih Halus di Atas Air --}}
                <div class="absolute inset-x-0 top-0 h-44 bg-gradient-to-b from-white via-white/80 to-transparent pointer-events-none"></div>

                {{-- FLOATING HUD KIRI BAWAH: KARTU TELEMETRI GELAP (DARK GLASSMORPHISM SEPERTI REFERENSI) --}}
                <div class="absolute bottom-6 sm:bottom-10 left-4 sm:left-8 lg:left-12 z-20 max-w-[280px] sm:max-w-sm w-full">
                    
                    {{-- Badges di Atas Kartu: LIVE & Status Cuaca/Gelombang --}}
                    <div class="flex items-center gap-2 mb-2.5">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-500/90 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-wider shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                            <span>LIVE</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-slate-900/60 backdrop-blur-md text-white/90 text-[10px] font-bold border border-white/10 shadow-sm">
                            <i class="fa-solid fa-water text-[9px] text-sky-400"></i>
                            <span>31°C · swell 0.6 m</span>
                        </span>
                    </div>

                    {{-- Badan Kartu Kaca Gelap (Dark HUD) --}}
                    <div class="bg-[#0b1329]/85 backdrop-blur-xl border border-white/15 rounded-2xl p-4 sm:p-5 shadow-2xl text-white space-y-3.5 ring-1 ring-black/20">
                        <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                            <div>
                                <h4 class="text-xs sm:text-sm font-black text-white tracking-tight">Berth 3 — MSC Aurora</h4>
                                <p class="text-[10px] text-sky-300/80">Tanjung Priok &rarr; Port Botany</p>
                            </div>
                            <span class="text-[11px] font-extrabold text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20">
                                ETD 18:40
                            </span>
                        </div>

                        {{-- Progress Bar: Discharge --}}
                        <div class="space-y-1">
                            <div class="flex justify-between text-[11px] font-semibold text-slate-300">
                                <span>Discharge</span>
                                <span class="font-extrabold text-white">82%</span>
                            </div>
                            <div class="w-full h-1.5 rounded-full bg-white/10 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-sky-400 to-cyan-300 rounded-full" style="width: 82%"></div>
                            </div>
                        </div>

                        {{-- Progress Bar: Load --}}
                        <div class="space-y-1">
                            <div class="flex justify-between text-[11px] font-semibold text-slate-300">
                                <span>Load</span>
                                <span class="font-extrabold text-white">46%</span>
                            </div>
                            <div class="w-full h-1.5 rounded-full bg-white/10 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-indigo-400 to-sky-400 rounded-full" style="width: 46%"></div>
                            </div>
                        </div>

                        {{-- Progress Bar: Crane Moves / Speed --}}
                        <div class="space-y-1">
                            <div class="flex justify-between text-[11px] font-semibold text-slate-300">
                                <span>Crane moves / hr</span>
                                <span class="font-extrabold text-sky-300">31.4</span>
                            </div>
                            <div class="w-full h-1.5 rounded-full bg-white/10 overflow-hidden">
                                <div class="h-full bg-cyan-400 rounded-full" style="width: 65%"></div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- FLOATING HUD KANAN BAWAH: STACK KARTU METRIK PUTIH (SEPERTI REFERENSI BERTHWISE) --}}
                <div class="absolute bottom-6 sm:bottom-10 right-4 sm:right-8 lg:right-12 z-20 flex flex-col gap-2 max-w-[200px] sm:max-w-[220px] w-full">
                    
                    {{-- Kartu 1: Wind --}}
                    <div class="bg-white/95 backdrop-blur-md rounded-2xl p-3 sm:p-3.5 border border-slate-200/90 shadow-lg text-slate-900 transition-all hover:scale-102">
                        <div class="flex items-center justify-between text-[10px] text-slate-500 font-bold mb-1">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-wind text-sky-500"></i>
                                <span>Wind</span>
                            </span>
                            <span class="text-[9px] text-emerald-600 font-extrabold bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-200">safe</span>
                        </div>
                        <div class="text-base sm:text-lg font-black text-slate-900">
                            14 km/h
                        </div>
                    </div>

                    {{-- Kartu 2: Yard Occupancy --}}
                    <div class="bg-white/95 backdrop-blur-md rounded-2xl p-3 sm:p-3.5 border border-slate-200/90 shadow-lg text-slate-900 transition-all hover:scale-102">
                        <div class="text-[10px] text-slate-500 font-bold mb-1 flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-boxes-stacked text-amber-500"></i>
                                <span>Yard occupancy</span>
                            </span>
                            <span class="text-[9px] font-extrabold text-emerald-600">+5.1%</span>
                        </div>
                        <div class="text-base sm:text-lg font-black text-slate-900">
                            74%
                        </div>
                    </div>

                    {{-- Kartu 3: Crane Moves / hr --}}
                    <div class="bg-white/95 backdrop-blur-md rounded-2xl p-3 sm:p-3.5 border border-slate-200/90 shadow-lg text-slate-900 transition-all hover:scale-102">
                        <div class="text-[10px] text-slate-500 font-bold mb-1 flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-gears text-indigo-500"></i>
                                <span>Crane moves / hr</span>
                            </span>
                            <span class="text-[9px] font-extrabold text-emerald-600">+1.4</span>
                        </div>
                        <div class="text-base sm:text-lg font-black text-slate-900">
                            28.9
                        </div>
                    </div>

                    {{-- Kartu 4: Throughput TEU --}}
                    <div class="bg-white/95 backdrop-blur-md rounded-2xl p-3 sm:p-3.5 border border-slate-200/90 shadow-lg text-slate-900 transition-all hover:scale-102">
                        <div class="text-[10px] text-slate-500 font-bold mb-1 flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-ship text-sky-600"></i>
                                <span>Throughput, TEU</span>
                            </span>
                            <span class="text-[9px] font-extrabold text-emerald-600">+6.2%</span>
                        </div>
                        <div class="text-base sm:text-lg font-black text-slate-900">
                            4,812
                        </div>
                    </div>

                </div>

            </div>

        </section>

        {{-- 4. MODAL LOGIN LANGSUNG (DENGAN ANIMASI REVEAL COLUMN DESIGN SPELLS & IKON VEKTOR PRESISI) --}}
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
                                <h3 class="text-sm font-black tracking-tight text-white">Portal Masuk CargoTrace</h3>
                                <p class="text-[11px] text-slate-300">Sistem Pelacakan Kapal Maritim</p>
                            </div>
                        </div>
                        <button type="button" @click="showLoginModal = false" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition cursor-pointer">
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
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
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
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
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

        {{-- 5. KONTEN LANDING PAGE DI BAWAH HERO (3 PILAR KAPABILITAS PERSIS DESAIN ASLI) --}}
        <main id="fitur-section" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-20">

            {{-- Bagian 1: 3 Pilar Kapabilitas Utama CargoTrace --}}
            <section class="space-y-10">
                <div class="text-center max-w-2xl mx-auto space-y-2.5">
                    <span class="px-3.5 py-1 rounded-full text-xs font-extrabold uppercase bg-sky-50 text-sky-700 border border-sky-200">
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
                    <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200/90 shadow-xs hover:border-sky-400 hover:shadow-lg transition-all duration-300 space-y-3.5 group">
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl font-bold border border-sky-100 group-hover:bg-sky-600 group-hover:text-white transition-colors duration-300">
                            <i class="fa-solid fa-satellite-dish"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900">Radar & Telemetri AIS 24/7</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Pelacakan koordinat lintang dan bujur posisi kapal kontainer secara real-time yang terintegrasi dengan peta GIS dan model bola dunia 3D.
                        </p>
                    </div>

                    {{-- Fitur 2 --}}
                    <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200/90 shadow-xs hover:border-rose-400 hover:shadow-lg transition-all duration-300 space-y-3.5 group">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl font-bold border border-rose-100 group-hover:bg-rose-600 group-hover:text-white transition-colors duration-300">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900">Deteksi Keterlambatan SLA</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Algoritma otomatis yang menghitung deviasi cuaca ekstrem, kecepatan knot, dan memicu peringatan dini terhadap jadwal tiba kargo.
                        </p>
                    </div>

                    {{-- Fitur 3 --}}
                    <div class="p-6 sm:p-7 rounded-3xl bg-white border border-slate-200/90 shadow-xs hover:border-emerald-400 hover:shadow-lg transition-all duration-300 space-y-3.5 group">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
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
            <section id="jaringan-section" class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200/90 shadow-xs hover:border-sky-300 transition-all flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="space-y-3.5 max-w-xl">
                    <span class="px-3.5 py-1 rounded-full text-[11px] font-extrabold uppercase bg-sky-50 text-sky-700 border border-sky-200">
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
                    <button @click="showLoginModal = true" class="px-7 py-3.5 rounded-full bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-600 hover:to-cyan-600 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 transition-all duration-200 transform hover:-translate-y-0.5 cursor-pointer">
                        Mulai Akses Monitoring &rarr;
                    </button>
                </div>
            </section>

        </main>

        {{-- 6. FOOTER OPERASIONAL SISTEM (PERSIS SAMA DENGAN FOOTER DASHBOARD) --}}
        <footer class="bg-white border-t border-slate-200 py-6 px-4 sm:px-6 lg:px-8 text-xs text-slate-500 mt-auto">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-lg bg-[#1b1f3b] text-sky-400 flex items-center justify-center text-xs font-bold">
                        <i class="fa-solid fa-anchor text-[10px]"></i>
                    </div>
                    <span class="font-extrabold text-slate-800">CargoTrace &copy; 2026</span>
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
