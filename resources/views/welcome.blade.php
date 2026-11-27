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
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            scroll-behavior: smooth;
        }
        [x-cloak] { display: none !important; }

        /* Animasi Mengambang Logo Kapal */
        @keyframes float-hero {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(0.5deg); }
        }
        .animate-float {
            animation: float-hero 5s ease-in-out infinite;
        }

        /* Gelombang Latar Belakang Hero */
        @keyframes wave-ambient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .hero-bg-glow {
            background: radial-gradient(circle at 50% 20%, rgba(2, 132, 199, 0.25) 0%, rgba(14, 22, 40, 0.85) 60%, #090e1a 100%);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-sky-500 selection:text-white">

    {{-- KOMPONEN UTAMA DENGAN ALPINE.JS STATE --}}
    <div x-data="{
        scrolledPastHero: false,
        showLoginModal: false,
        selectedRole: null, // null = Pilih Role; 'admin' | 'operator' | 'customer' = Form Login
        emailInput: '',
        passwordInput: '',
        roleConfig: {
            admin: {
                name: 'Administrator',
                badge: 'KONSOL SISTEM & AUDIT',
                icon: 'fa-shield-halved',
                color: 'purple',
                borderActive: 'border-purple-500',
                badgeBg: 'bg-purple-100 text-purple-800 border-purple-200',
                btnBg: 'bg-purple-700 hover:bg-purple-800',
                defaultEmail: 'admin@cargotrace.com',
                desc: 'Akses penuh kendali master armada, konfigurasi maskapai, lokasi pelabuhan, dan log keamanan audit.'
            },
            operator: {
                name: 'Operator Logistik',
                badge: 'OPERASIONAL & ARMADA',
                icon: 'fa-anchor',
                color: 'amber',
                borderActive: 'border-amber-500',
                badgeBg: 'bg-amber-100 text-amber-800 border-amber-200',
                btnBg: 'bg-amber-600 hover:bg-amber-700',
                defaultEmail: 'operator@cargotrace.com',
                desc: 'Pengelolaan manifes kapal harian, pembaruan status pelayaran AIS, dan mitigasi kendala lapangan.'
            },
            customer: {
                name: 'Pelanggan / Customer',
                badge: 'VISIBILITAS PEMILIK KARGO',
                icon: 'fa-box-open',
                color: 'emerald',
                borderActive: 'border-emerald-500',
                badgeBg: 'bg-emerald-100 text-emerald-800 border-emerald-200',
                btnBg: 'bg-emerald-600 hover:bg-emerald-700',
                defaultEmail: 'zahra@cargotrace.com',
                desc: 'Visibilitas real-time pelacakan kontainer kapal kargo Anda, perkiraan kedatangan (ETA), dan manifes kargo.'
            }
        },
        openRole(role) {
            this.selectedRole = role;
            this.emailInput = this.roleConfig[role].defaultEmail;
            this.passwordInput = 'password';
        },
        resetRole() {
            this.selectedRole = null;
            this.emailInput = '';
            this.passwordInput = '';
        }
    }"
    @scroll.window="scrolledPastHero = (window.pageYOffset > window.innerHeight * 0.65)"
    class="relative">

        {{-- 1. HERO SECTION (HALAMAN PENUH / FULL-SCREEN TANPA NAVBAR SAMA SEKALI DI ATAS) --}}
        <section id="hero-section" class="relative w-full h-screen min-h-[700px] flex flex-col justify-between items-center text-center px-4 sm:px-6 lg:px-8 text-white hero-bg-glow overflow-hidden">
            
            {{-- Elemen Dekorasi Cahaya Samudra --}}
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[700px] h-[700px] bg-sky-500/15 rounded-full blur-[140px] pointer-events-none"></div>
            <div class="absolute bottom-0 inset-x-0 h-40 bg-gradient-to-t from-slate-50 to-transparent pointer-events-none"></div>

            {{-- Ruang Kosong Paling Atas (Menjamin tidak ada navigasi apapun di puncak hero) --}}
            <div class="pt-8"></div>

            {{-- Konten Utama Hero Section --}}
            <div class="max-w-4xl mx-auto flex flex-col items-center z-10 space-y-6 my-auto">
                
                {{-- Logo Utama "Cargo Trash" dengan Animasi Halus & Lencana Kapal Kargo --}}
                <div class="animate-float relative">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-gradient-to-br from-sky-400 via-blue-600 to-indigo-900 p-0.5 shadow-[0_0_50px_rgba(14,165,233,0.45)] flex items-center justify-center">
                        <div class="w-full h-full bg-[#0a1122] rounded-[22px] flex items-center justify-center text-sky-400 text-4xl sm:text-5xl shadow-inner">
                            <i class="fa-solid fa-anchor"></i>
                        </div>
                    </div>
                    {{-- Ping Beacon di Pojok Logo --}}
                    <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-emerald-400 border-4 border-[#0a1122] animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-emerald-400 border-4 border-[#0a1122]"></span>
                </div>

                {{-- Judul Merek "Cargo Trash" --}}
                <div class="space-y-2">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-[11px] font-extrabold font-mono tracking-widest uppercase bg-sky-500/10 text-sky-300 border border-sky-400/30 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-sky-400 animate-pulse"></span>
                        MARITIME CARGO LOGISTICS TELEMETRY
                    </span>

                    <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight text-white">
                        Cargo <span class="bg-gradient-to-r from-sky-400 via-cyan-300 to-blue-400 bg-clip-text text-transparent">Trash</span>
                    </h1>
                </div>

                {{-- Teks Penjelasan Lengkap Mengenai Sistem & Kegunaan untuk Kapal Kargo --}}
                <p class="text-sm sm:text-base lg:text-lg text-slate-300 max-w-3xl leading-relaxed font-normal">
                    Pusat kendali intelijen dan visibilitas maritim modern untuk armada kapal kargo global. 
                    Dirancang untuk memonitor siklus pelayaran peti kemas secara <em>real-time</em>, mendeteksi potensi anomali 
                    keterlambatan cuaca ekstrem (*SLA Delay Engine*), integrasi koordinat GIS maritim pelabuhan internasional, 
                    serta memberikan kendali operasional komprehensif bagi Administrator, Operator Armada, dan Pemilik Muatan.
                </p>

                {{-- Tombol Cepat Aksi di Hero --}}
                <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                    <button @click="showLoginModal = true; resetRole()" class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-sky-500 via-blue-600 to-sky-600 hover:from-sky-400 hover:to-blue-500 text-white font-extrabold text-sm shadow-[0_10px_30px_rgba(2,132,199,0.4)] hover:shadow-[0_15px_35px_rgba(2,132,199,0.6)] transition-all transform hover:-translate-y-0.5 flex items-center gap-2.5">
                        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                        <span>Buka Portal Login</span>
                    </button>

                    <a href="#fitur-section" class="px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/15 text-slate-200 hover:text-white font-bold text-sm border border-white/20 transition-all flex items-center gap-2 backdrop-blur-md">
                        <i class="fa-solid fa-compass text-sky-400"></i>
                        <span>Eksplorasi Fitur Sistem</span>
                    </a>
                </div>
            </div>

            {{-- Indikator Scroll di Bagian Bawah Hero --}}
            <div class="pb-10 z-10">
                <a href="#fitur-section" class="flex flex-col items-center gap-2 text-slate-400 hover:text-sky-300 transition group text-xs font-mono">
                    <span class="tracking-wider uppercase text-[10px]">Gulir ke Bawah</span>
                    <div class="w-7 h-10 rounded-full border-2 border-slate-500/80 group-hover:border-sky-400 flex items-start justify-center p-1.5 transition">
                        <div class="w-1.5 h-2 bg-sky-400 rounded-full animate-bounce"></div>
                    </div>
                </a>
            </div>
        </section>

        {{-- 2. STICKY NAVBAR (HANYA MUNCUL KETIKA PENGGUNA MENGGULIR KE BAWAH MELEWATI HERO SECTION) --}}
        <header 
            class="fixed top-0 inset-x-0 z-50 transition-all duration-500 transform"
            :class="scrolledPastHero ? 'translate-y-0 opacity-100 pointer-events-auto' : '-translate-y-full opacity-0 pointer-events-none'">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-3">
                <div class="bg-white/95 backdrop-blur-xl rounded-2xl border border-slate-200/90 shadow-lg px-6 py-3.5 flex items-center justify-between">
                    
                    {{-- Sisi Kiri Navbar: Logo & Nama "Cargo Trash" --}}
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#1b1f3b] text-sky-400 flex items-center justify-center text-base font-bold shadow-xs">
                            <i class="fa-solid fa-anchor"></i>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-black tracking-tight text-slate-900">Cargo <span class="text-sky-600">Trash</span></span>
                            <span class="hidden sm:inline-block px-2 py-0.5 rounded text-[9px] font-mono font-bold bg-sky-50 text-sky-700 border border-sky-200">MARITIM</span>
                        </div>
                    </div>

                    {{-- 3. ISI NAVBAR: KOSONG TANPA MENU STANDAR, HANYA SATU TOMBOL LOGIN MENONJOL DI KANAN --}}
                    <div>
                        <button 
                            @click="showLoginModal = true; resetRole()" 
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 via-blue-600 to-sky-700 hover:from-sky-500 hover:to-blue-500 text-white font-extrabold text-xs sm:text-sm shadow-[0_4px_16px_rgba(2,132,199,0.35)] hover:shadow-[0_6px_22px_rgba(2,132,199,0.5)] transition-all duration-200 transform hover:-translate-y-0.5 flex items-center gap-2">
                            <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                            <span>Login</span>
                        </button>
                    </div>

                </div>
            </div>
        </header>

        {{-- 4 & 5. MODAL MULTI-ROLE (POP-UP MELAYANG DENGAN PEMILIHAN ROLE & ALUR FORM LOGIN) --}}
        <div 
            x-show="showLoginModal" 
            x-cloak
            class="fixed inset-0 z-[99999] overflow-y-auto"
            style="display: none;"
            @keydown.escape.window="showLoginModal = false">
            
            {{-- Backdrop Transparan Gelap dengan Blur --}}
            <div 
                x-show="showLoginModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity" 
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
                    class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all w-full max-w-lg border border-slate-200">
                    
                    {{-- Header Modal --}}
                    <div class="bg-[#1b1f3b] px-6 py-5 flex items-center justify-between text-white border-b border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-lg border border-sky-400/30">
                                <i class="fa-solid fa-anchor"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-black tracking-tight text-white">Portal Akses Cargo Trash</h3>
                                <p class="text-xs text-slate-300 mt-0.5">Sistem Autentikasi Multi-Peran Terpadu</p>
                            </div>
                        </div>
                        <button type="button" @click="showLoginModal = false" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">
                            <i class="fa-solid fa-xmark text-base"></i>
                        </button>
                    </div>

                    {{-- LANGKAH 1: PEMILIHAN ROLE (TIDAK LANGSUNG MENAMPILKAN FORM LOGIN) --}}
                    <div x-show="selectedRole === null" class="p-6 sm:p-8 space-y-4">
                        <div class="text-center space-y-1 mb-5">
                            <h4 class="text-base font-extrabold text-slate-900">Pilih Peran Akun Anda</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">Silakan tentukan hak akses peran sebelum memasukkan kredensial login:</p>
                        </div>

                        {{-- 3 Kartu Tombol Pilihan Role --}}
                        <div class="space-y-3">
                            
                            {{-- Pilihan 1: Administrator --}}
                            <button 
                                @click="openRole('admin')" 
                                class="w-full text-left p-4 rounded-2xl border border-slate-200 hover:border-purple-400 bg-purple-50/30 hover:bg-purple-50/70 transition-all duration-200 shadow-2xs group flex items-start gap-4">
                                <div class="w-11 h-11 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h5 class="font-extrabold text-sm text-slate-900 group-hover:text-purple-700 transition">Login sebagai Admin</h5>
                                        <span class="text-[9px] font-extrabold font-mono px-2 py-0.5 rounded bg-purple-100 text-purple-800 border border-purple-200">MASTER</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1 leading-snug">Kendali master armada kargo, kelola maskapai carrier, data pelabuhan, dan audit keamanan sistem.</p>
                                </div>
                            </button>

                            {{-- Pilihan 2: Operator Logistik --}}
                            <button 
                                @click="openRole('operator')" 
                                class="w-full text-left p-4 rounded-2xl border border-slate-200 hover:border-amber-400 bg-amber-50/30 hover:bg-amber-50/70 transition-all duration-200 shadow-2xs group flex items-start gap-4">
                                <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition">
                                    <i class="fa-solid fa-anchor"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h5 class="font-extrabold text-sm text-slate-900 group-hover:text-amber-700 transition">Login sebagai Operator</h5>
                                        <span class="text-[9px] font-extrabold font-mono px-2 py-0.5 rounded bg-amber-100 text-amber-800 border border-amber-200">DISPATCH</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1 leading-snug">Manajemen keberangkatan pelayaran kapal, input kargo baru, pembaruan status AIS, dan mitigasi kendala lapangan.</p>
                                </div>
                            </button>

                            {{-- Pilihan 3: Pelanggan / Customer --}}
                            <button 
                                @click="openRole('customer')" 
                                class="w-full text-left p-4 rounded-2xl border border-slate-200 hover:border-emerald-400 bg-emerald-50/30 hover:bg-emerald-50/70 transition-all duration-200 shadow-2xs group flex items-start gap-4">
                                <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition">
                                    <i class="fa-solid fa-box-open"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h5 class="font-extrabold text-sm text-slate-900 group-hover:text-emerald-700 transition">Login sebagai Customer</h5>
                                        <span class="text-[9px] font-extrabold font-mono px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-200">CLIENT</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1 leading-snug">Lacak status kontainer kargo milik Anda secara transparan, lihat estimasi tiba (ETA), dan riwayat dokumen.</p>
                                </div>
                            </button>

                        </div>
                    </div>

                    {{-- 6. LANGKAH 2: FORM INPUT LOGIN KHUSUS PERAN TERPILIH --}}
                    <div x-show="selectedRole !== null" class="p-6 sm:p-8 space-y-5">
                        
                        {{-- Baris Header Sub-Formulir & Tombol Kembali --}}
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <button 
                                type="button" 
                                @click="resetRole()" 
                                class="text-xs font-bold text-sky-600 hover:text-sky-800 flex items-center gap-1.5 transition">
                                <i class="fa-solid fa-arrow-left text-[11px]"></i>
                                <span>Ganti Pilihan Role</span>
                            </button>

                            <template x-if="selectedRole">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold font-mono uppercase" :class="roleConfig[selectedRole].badgeBg">
                                    <span x-text="roleConfig[selectedRole].name"></span>
                                </span>
                            </template>
                        </div>

                        {{-- Formulir Autentikasi --}}
                        <form method="POST" action="{{ route('login') }}" class="space-y-4 text-left">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email / Username</label>
                                <div class="relative">
                                    <i class="fa-regular fa-envelope absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                                    <input 
                                        type="email" 
                                        name="email" 
                                        required 
                                        x-model="emailInput"
                                        placeholder="Masukkan email..." 
                                        class="w-full text-xs rounded-xl border-slate-300 pl-9 pr-3.5 py-2.5 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white shadow-2xs">
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block text-xs font-bold text-slate-700">Kata Sandi</label>
                                    <span class="text-[10px] text-slate-400 font-mono">Demo: password</span>
                                </div>
                                <div class="relative">
                                    <i class="fa-solid fa-lock absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                                    <input 
                                        type="password" 
                                        name="password" 
                                        required 
                                        x-model="passwordInput"
                                        placeholder="••••••••" 
                                        class="w-full text-xs rounded-xl border-slate-300 pl-9 pr-3.5 py-2.5 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white shadow-2xs">
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-xs pt-1">
                                <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                    <span class="text-[11px]">Ingat saya di perangkat ini</span>
                                </label>
                            </div>

                            <div class="pt-3">
                                <button 
                                    type="submit" 
                                    class="w-full py-3 rounded-xl text-white font-extrabold text-xs shadow-md transition-all flex items-center justify-center gap-2"
                                    :class="selectedRole ? roleConfig[selectedRole].btnBg : 'bg-sky-600'">
                                    <span>Masuk ke Sistem Sekarang</span>
                                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        {{-- 7. KONTEN LANDING PAGE DI BAWAH HERO SECTION (AGAR TERJADI SCROLL & MUNCULKAN STICKY NAVBAR) --}}
        <main id="fitur-section" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-24">

            {{-- Bagian 1: 3 Pilar Kapabilitas Utama Cargo Trash --}}
            <section class="space-y-12">
                <div class="text-center max-w-2xl mx-auto space-y-3">
                    <span class="px-3 py-1 rounded-full text-xs font-bold font-mono uppercase bg-sky-100 text-sky-800 border border-sky-200">
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
                    <div class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-sm hover:border-sky-400 hover:shadow-md transition-all duration-300 space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl font-bold">
                            <i class="fa-solid fa-satellite-dish"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900">Radar & Telemetri AIS 24/7</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Pelacakan koordinat lintang dan bujur posisi kapal kontainer secara real-time yang terintegrasi dengan peta GIS dan model bola dunia 3D.
                        </p>
                    </div>

                    {{-- Fitur 2 --}}
                    <div class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-sm hover:border-rose-400 hover:shadow-md transition-all duration-300 space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl font-bold">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900">Deteksi Keterlambatan SLA</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Algoritma otomatis yang menghitung deviasi cuaca ekstrem, kecepatan knot, dan memicu peringatan dini terhadap jadwal tiba kargo.
                        </p>
                    </div>

                    {{-- Fitur 3 --}}
                    <div class="p-6 rounded-3xl bg-white border border-slate-200/90 shadow-sm hover:border-emerald-400 hover:shadow-md transition-all duration-300 space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                            <i class="fa-solid fa-box-archive"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900">Siklus Manifes Multi-Moda</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Transisi status kargo yang terkunci aman dari pelabuhan asal (Created, Booked, In Transit, hingga Delivered) dengan proteksi akses IDOR.
                        </p>
                    </div>
                </div>
            </section>

            {{-- Bagian 2: Jaringan Hub Pelabuhan Maritim Dunia --}}
            <section class="bg-gradient-to-br from-[#11192e] to-[#0c1322] rounded-3xl p-8 sm:p-12 text-white border border-slate-800 shadow-xl flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="space-y-4 max-w-xl">
                    <span class="px-3 py-1 rounded-full text-[11px] font-mono font-bold bg-sky-500/20 text-sky-400 border border-sky-400/30">
                        GLOBAL MARITIME NETWORK
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight">
                        Menghubungkan Jalur Samudra dari Nusantara ke Benua Eropa & Pasifik
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Terkoneksi langsung dengan pelabuhan strategis utama seperti Tanjung Priok Jakarta, Port of Rotterdam, Tanjung Perak Surabaya, Port of Hamburg, dan Port Botany Sydney.
                    </p>
                </div>
                <div class="shrink-0">
                    <button @click="showLoginModal = true; resetRole()" class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-extrabold text-sm shadow-[0_0_25px_rgba(14,165,233,0.4)] transition">
                        Mulai Akses Monitoring &rarr;
                    </button>
                </div>
            </section>

        </main>

        {{-- 8. FOOTER OPERASIONAL SISTEM --}}
        <footer class="bg-white border-t border-slate-200 py-8 px-4 sm:px-6 lg:px-8 text-xs text-slate-500">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-lg bg-[#1b1f3b] text-sky-400 flex items-center justify-center text-xs font-bold">
                        <i class="fa-solid fa-anchor text-[10px]"></i>
                    </div>
                    <span class="font-black text-slate-800">Cargo Trash &copy; 2026</span>
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
