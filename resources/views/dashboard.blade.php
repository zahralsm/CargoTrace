<x-app-layout>
    {{-- Styling Khusus: Bento Grid Maritime Deck (Light Theme / Nuansa Putih Elegan) --}}
    <style>
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Marker Kapal Berdenyut (Beacon Pulse) */
        .beacon-pulse {
            width: 14px;
            height: 14px;
            background-color: #0284c7;
            border: 2px solid #ffffff;
            border-radius: 50%;
            position: relative;
            box-shadow: 0 0 10px rgba(2, 132, 199, 0.8);
        }
        .beacon-pulse::after {
            content: '';
            position: absolute;
            top: -6px;
            left: -6px;
            width: 22px;
            height: 22px;
            border: 2px solid #38bdf8;
            border-radius: 50%;
            animation: pulse-ring 2s infinite ease-in-out;
        }
        .beacon-pulse-delayed {
            background-color: #e11d48 !important;
            box-shadow: 0 0 10px rgba(225, 29, 72, 0.8) !important;
        }
        .beacon-pulse-delayed::after {
            border-color: #f43f5e !important;
        }
        .beacon-pulse-delivered {
            background-color: #059669 !important;
            box-shadow: 0 0 10px rgba(5, 150, 105, 0.8) !important;
        }
        .beacon-pulse-delivered::after {
            border-color: #10b981 !important;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.9; }
            50% { transform: scale(1.8); opacity: 0.15; }
            100% { transform: scale(0.8); opacity: 0.9; }
        }

        /* Efek Gelombang Isometrik Halus Kapal Flagship */
        @keyframes wave-flow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-4px); }
        }
        .vessel-float {
            animation: wave-flow 4s ease-in-out infinite;
        }

        [x-cloak] { display: none !important; }
    </style>

    {{-- KANVAS DECK MARITIM UTAMA (TEMA PUTIH BERSIH / EXECUTIVE LIGHT MODE) --}}
    <div x-data="{ showCreateModal: false, showBookingModal: false }" class="bg-slate-100 text-slate-800 min-h-screen py-5 px-3 sm:px-6 lg:px-8">
        <div class="max-w-[1600px] mx-auto space-y-5">

            {{-- 1. PITA PUSAT KENDALI TELEMETRI MARITIM --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs px-6 py-3.5 flex flex-wrap items-center justify-between gap-4">
                {{-- Kiri: Judul Deck Maritim & Waktu UTC --}}
                <div class="flex items-center gap-3.5">
                    <div class="relative w-11 h-11 rounded-xl bg-[#1b1f3b] text-sky-400 flex items-center justify-center text-lg font-bold shadow-xs">
                        <i class="fa-solid fa-satellite-dish"></i>
                        <span class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-emerald-500 border-2 border-white animate-ping"></span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h1 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">{{ Auth::user()->isCustomer() ? 'Pusat Pelacakan Kargo Maritim' : 'Pusat Kendali Pelayaran Maritim' }}</h1>
                            <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-sky-50 text-sky-700 border border-sky-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span>
                                {{ Auth::user()->isCustomer() ? 'MONITORING KARGO AKTIF' : 'TELEMETRI BENTO AKTIF' }}
                            </span>
                        </div>
                        <div class="text-xs text-slate-500 flex flex-wrap items-center gap-3 mt-0.5">
                            <span class="text-sky-700 font-semibold"><i class="fa-regular fa-clock mr-1 text-sky-600"></i> <strong id="utc-time-hud">--:--:-- UTC</strong></span>
                            <span class="text-slate-300">•</span>
                            <span>Satelit AIS: <strong class="text-emerald-700 font-bold">TERKONEKSI (100% ONLINE)</strong></span>
                            <span class="text-slate-300">•</span>
                            <span>Peran: <strong class="text-slate-700 uppercase font-bold">{{ Auth::user()->role === 'admin' ? 'Administrator' : (Auth::user()->role === 'operator' ? 'Operator Logistik' : 'Pelanggan') }}</strong></span>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Aksi Registrasi Kargo & Status Ringkas --}}
                <div class="flex items-center gap-3">
                    @if(!Auth::user()->isCustomer())
                        <button @click="showCreateModal = true" class="px-4 py-2 bg-[#1b1f3b] hover:bg-sky-600 text-white rounded-xl text-xs font-bold shadow-xs flex items-center gap-2 transition duration-200">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span>Daftarkan Manifest Kargo</span>
                        </button>
                    @else
                        <button @click="showBookingModal = true" class="px-4 py-2 bg-[#1b1f3b] hover:bg-sky-600 text-white rounded-xl text-xs font-bold shadow-xs flex items-center gap-2 transition duration-200">
                            <i class="fa-solid fa-cart-flatbed text-xs text-sky-400"></i>
                            <span>Booking Kontainer Baru</span>
                        </button>
                    @endif

                    <div class="hidden md:flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 ">
                        <i class="fa-solid fa-location-crosshairs text-sky-600"></i>
                        <span>GIS WGS-84</span>
                    </div>
                </div>
            </div>

            {{-- Pesan Notifikasi Sukses --}}
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl text-xs font-bold text-emerald-800 shadow-xs flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            {{-- 2. BENTO GRID BARIS 1: KAPAL ISOMETRIK 3D + KOMPAS OSEANOGRAFI + RING METRIK TELEMETRI --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-5">

                {{-- BENTO TILE 1 (5 Kolom): KARTU KAPAL ISOMETRIK 3D & PELAYARAN FLAGSHIP --}}
                @php
                    $flagship = $shipments->first();
                @endphp
                <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/90 hover:border-sky-400 hover:shadow-md transition-all duration-300 p-6 flex flex-col justify-between shadow-xs relative overflow-hidden group">
                    {{-- Aksen Latar Belakang Biru Lembut --}}
                    <div class="absolute -top-16 -right-16 w-52 h-52 bg-sky-50 rounded-full blur-2xl pointer-events-none group-hover:bg-sky-100/70 transition-all duration-500"></div>

                    <div>
                        {{-- Header Tile 1 --}}
                        <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-sky-50 text-sky-700 border border-sky-200 tracking-wider uppercase flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-sky-500 animate-ping"></span>
                                    {{ Auth::user()->isCustomer() ? 'KAPAL PEMBAWA KARGO ANDA' : 'KAPAL FLAGSHIP AKTIF' }}
                                </span>
                            </div>
                            <span class="text-[11px] text-slate-400">{{ Auth::user()->isCustomer() ? 'STATUS PELAYARAN AKTIF' : 'IMO CALLSIGN: 9463047' }}</span>
                        </div>

                        {{-- Ilustrasi Kapal Kontainer Isometrik 3D Modern --}}
                        <div class="my-4 py-2 flex items-center justify-center relative">
                            <div class="w-full max-w-[340px] vessel-float">
                                <svg viewBox="0 0 400 210" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto drop-shadow-[0_10px_20px_rgba(2,132,199,0.18)]">
                                    <defs>
                                        <linearGradient id="hullGradLight" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="#0284c7" />
                                            <stop offset="60%" stop-color="#1e293b" />
                                            <stop offset="100%" stop-color="#0f172a" />
                                        </linearGradient>
                                        <linearGradient id="deckGradLight" x1="0%" y1="0%" x2="100%" y2="0%">
                                            <stop offset="0%" stop-color="#38bdf8" />
                                            <stop offset="100%" stop-color="#0284c7" />
                                        </linearGradient>
                                        <linearGradient id="waterRipplesLight" x1="0%" y1="0%" x2="100%" y2="0%">
                                            <stop offset="0%" stop-color="#0284c7" stop-opacity="0" />
                                            <stop offset="50%" stop-color="#0284c7" stop-opacity="0.25" />
                                            <stop offset="100%" stop-color="#0284c7" stop-opacity="0" />
                                        </linearGradient>
                                    </defs>

                                    <!-- Riak Gelombang Air Laut Isometrik -->
                                    <ellipse cx="200" cy="170" rx="170" ry="32" fill="url(#waterRipplesLight)" />
                                    <path d="M 60 172 Q 200 195 340 172" stroke="#0284c7" stroke-width="1.8" stroke-dasharray="6 8" opacity="0.6" />
                                    <path d="M 90 182 Q 200 202 310 182" stroke="#38bdf8" stroke-width="1.2" stroke-dasharray="4 6" opacity="0.4" />

                                    <!-- Lambung Bawah Kapal (Garis Cat Bawah Air) -->
                                    <polygon points="65,160 190,188 335,145 320,132 190,158 85,142" fill="#e11d48" opacity="0.9" />

                                    <!-- Lambung Utama Kapal (Isometric Vessel Hull) -->
                                    <polygon points="65,160 190,188 335,145 340,118 190,145 55,128" fill="url(#hullGradLight)" stroke="#0284c7" stroke-width="1" />

                                    <!-- Geladak Kapal (Deck Surface) -->
                                    <polygon points="55,128 190,145 340,118 205,102" fill="url(#deckGradLight)" opacity="0.9" />

                                    <!-- Haluan Runcing Kapal (Bow Bulbous) -->
                                    <polygon points="55,128 65,160 85,142 75,119" fill="#0369a1" />

                                    <!-- Tumpukan Kontainer Isometrik Baris 1 (Depan) -->
                                    <g transform="translate(100, 75)">
                                        <!-- Box 1 Cyan/Biru Laut -->
                                        <polygon points="0,20 28,30 28,52 0,42" fill="#0284c7" />
                                        <polygon points="28,30 68,18 68,40 28,52" fill="#0369a1" />
                                        <polygon points="0,20 40,8 68,18 28,30" fill="#38bdf8" />
                                    </g>
                                    <g transform="translate(132, 65)">
                                        <!-- Box 2 Oranye / Logistik Darat -->
                                        <polygon points="0,20 28,30 28,52 0,42" fill="#d97706" />
                                        <polygon points="28,30 68,18 68,40 28,52" fill="#b45309" />
                                        <polygon points="0,20 40,8 68,18 28,30" fill="#fbbf24" />
                                    </g>
                                    <g transform="translate(164, 55)">
                                        <!-- Box 3 Hijau Pertanian -->
                                        <polygon points="0,20 28,30 28,52 0,42" fill="#059669" />
                                        <polygon points="28,30 68,18 68,40 28,52" fill="#047857" />
                                        <polygon points="0,20 40,8 68,18 28,30" fill="#34d399" />
                                    </g>

                                    <!-- Tumpukan Kontainer Tingkat 2 -->
                                    <g transform="translate(116, 50)">
                                        <polygon points="0,20 28,30 28,42 0,32" fill="#475569" />
                                        <polygon points="28,30 68,18 68,30 28,42" fill="#334155" />
                                        <polygon points="0,20 40,8 68,18 28,30" fill="#94a3b8" />
                                    </g>
                                    <g transform="translate(148, 40)">
                                        <polygon points="0,20 28,30 28,42 0,32" fill="#0284c7" />
                                        <polygon points="28,30 68,18 68,30 28,42" fill="#0369a1" />
                                        <polygon points="0,20 40,8 68,18 28,30" fill="#38bdf8" />
                                    </g>

                                    <!-- Anjungan Kapal / Superstructure (Bridge) -->
                                    <g transform="translate(240, 52)">
                                        <polygon points="0,35 24,43 24,78 0,70" fill="#e2e8f0" />
                                        <polygon points="24,43 55,34 55,69 24,78" fill="#cbd5e1" />
                                        <polygon points="0,35 31,26 55,34 24,43" fill="#f8fafc" />

                                        <!-- Jendela Kaca Anjungan -->
                                        <polygon points="2,41 22,48 22,54 2,47" fill="#0284c7" opacity="0.9" />
                                        <polygon points="25,48 53,39 53,45 25,54" fill="#0369a1" opacity="0.9" />

                                        <!-- Tiang Radar & Cerobong -->
                                        <polygon points="35,16 45,13 45,34 35,37" fill="#be123c" />
                                        <line x1="16" y1="26" x2="16" y2="10" stroke="#0284c7" stroke-width="2" />
                                        <circle cx="16" cy="10" r="3" fill="#e11d48">
                                            <animate attributeName="opacity" values="1;0.2;1" dur="1.2s" repeatCount="indefinite" />
                                        </circle>
                                    </g>
                                </svg>
                            </div>
                        </div>

                        {{-- Metadata Kapal Flagship & Rute --}}
                        <div class="space-y-3 mt-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-base font-black text-slate-900 group-hover:text-sky-600 transition tracking-tight">
                                        {{ $flagship ? $flagship->shipment_number : 'CTG-2026-0081' }}
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        <i class="fa-solid fa-ship text-sky-600 mr-1.5"></i>
                                        {{ $flagship?->carrier->name ?? 'Maersk Line Intercontinental' }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 rounded-lg text-xs font-bold {{ ($flagship?->delay_status === 'DELAYED') ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                    {{ $flagship?->delay_status === 'DELAYED' ? '+'.$flagship->delay_hours.' JAM TERLAMBAT' : 'TEPAT WAKTU' }}
                                </span>
                            </div>

                            {{-- Lintasan Koridor Pelabuhan --}}
                            <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200/80 flex items-center justify-between text-xs">
                                <div>
                                    <div class="text-[10px] text-slate-400 ">PELABUHAN ASAL</div>
                                    <div class="font-extrabold text-slate-800 text-sm mt-0.5">{{ $flagship?->origin->city ?? 'Jakarta' }}</div>
                                    <div class="text-[10px] text-sky-600 font-bold">{{ $flagship?->origin->code ?? 'IDTPP' }}</div>
                                </div>
                                <div class="flex-1 px-4 flex flex-col items-center">
                                    <div class="w-full flex items-center">
                                        <span class="w-2.5 h-2.5 rounded-full bg-sky-500 shadow-xs"></span>
                                        <div class="flex-1 border-t-2 border-dashed border-sky-300 mx-1"></div>
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-xs"></span>
                                    </div>
                                    <span class="text-[9px] text-slate-400 mt-1">KORIDOR MARITIM INTERNASIONAL</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-[10px] text-slate-400 ">TUJUAN AKHIR</div>
                                    <div class="font-extrabold text-slate-800 text-sm mt-0.5">{{ $flagship?->destination->city ?? 'Rotterdam' }}</div>
                                    <div class="text-[10px] text-emerald-600 font-bold">{{ $flagship?->destination->code ?? 'NLRTM' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Tile 1: Telemetri GPS & Tautan Kendali --}}
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs mt-4">
                        <div class="flex items-center gap-3 text-slate-500 text-[11px]">
                            <span><i class="fa-solid fa-gauge-high text-sky-600 mr-1"></i> <strong>19.4 Knots</strong></span>
                            <span>•</span>
                            <span><i class="fa-solid fa-box text-slate-400 mr-1"></i> <strong class="text-slate-700">{{ Str::limit($flagship?->cargo_type ?? 'Grade-1 Arabica Coffee Beans', 20) }}</strong></span>
                        </div>
                        @if($flagship)
                            <a href="{{ route('shipments.show', $flagship) }}" class="inline-flex items-center gap-1.5 font-bold text-sky-600 hover:text-sky-800 transition group/link">
                                <span>{{ Auth::user()->isCustomer() ? 'Lacak Rute & Status' : 'Detail Kendali' }}</span>
                                <i class="fa-solid fa-arrow-right text-[11px] group-hover/link:translate-x-1 transition"></i>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- BENTO TILE 2 (3 Kolom): WIDGET KOMPAS MARITIM & OSEANOGRAFI --}}
                <div class="lg:col-span-3 bg-white rounded-3xl border border-slate-200/90 hover:border-sky-400 hover:shadow-md transition-all duration-300 p-6 flex flex-col justify-between shadow-xs relative">
                    <div>
                        {{-- Header Kompas --}}
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-compass text-sky-600 text-sm"></i>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Kompas & Cuaca Laut</h3>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-sky-50 text-sky-700 border border-sky-200">GYRO 042°</span>
                        </div>

                        {{-- Dial Kompas Melingkar SVG (Light Theme) --}}
                        <div class="my-5 flex flex-col items-center justify-center">
                            <div class="relative w-44 h-44 flex items-center justify-center">
                                <svg viewBox="0 0 200 200" class="w-full h-full drop-shadow-sm">
                                    <!-- Ring Luar Kompas -->
                                    <circle cx="100" cy="100" r="92" stroke="#e2e8f0" stroke-width="4" fill="#f8fafc" />
                                    <circle cx="100" cy="100" r="84" stroke="#cbd5e1" stroke-width="1.2" stroke-dasharray="2 6" fill="none" />
                                    
                                    <!-- Lingkaran Skala Derajat -->
                                    <circle cx="100" cy="100" r="76" stroke="#0284c7" stroke-width="0.8" opacity="0.3" fill="none" />

                                    <!-- Indikator Arah Mata Angin (N, E, S, W) - Font Inter Tebal & Jelas -->
                                    <text x="100" y="26" text-anchor="middle" font-size="12" font-weight="900" fill="#e11d48" font-family="Inter, sans-serif">N</text>
                                    <text x="180" y="104" text-anchor="middle" font-size="11" font-weight="900" fill="#0284c7" font-family="Inter, sans-serif">E</text>
                                    <text x="100" y="184" text-anchor="middle" font-size="11" font-weight="900" fill="#64748b" font-family="Inter, sans-serif">S</text>
                                    <text x="20" y="104" text-anchor="middle" font-size="11" font-weight="900" fill="#64748b" font-family="Inter, sans-serif">W</text>

                                    <!-- Garis-Garis Skala Derajat Halus -->
                                    @for($deg = 0; $deg < 360; $deg += 30)
                                        <line x1="100" y1="14" x2="100" y2="18" stroke="#0284c7" stroke-width="1.5" transform="rotate({{ $deg }} 100 100)" opacity="0.6" />
                                    @endfor

                                    <!-- Jarum Kompas Maritim Berorientasi 042° NE -->
                                    <g transform="rotate(42 100 100)">
                                        <!-- Jarum Utara (Merah Menyala) -->
                                        <polygon points="100,32 106,100 94,100" fill="#e11d48" />
                                        <polygon points="100,32 100,100 94,100" fill="#be123c" />

                                        <!-- Jarum Selatan (Baja Biru) -->
                                        <polygon points="100,168 106,100 94,100" fill="#0284c7" />
                                        <polygon points="100,168 100,100 94,100" fill="#0369a1" />

                                        <!-- Titik Poros Tengah -->
                                        <circle cx="100" cy="100" r="7" fill="#ffffff" stroke="#1e293b" stroke-width="2" />
                                        <circle cx="100" cy="100" r="3" fill="#e11d48" />
                                    </g>
                                </svg>

                                <!-- Badge Haluan Digital Tengah -->
                                <div class="absolute -bottom-2 bg-white px-2.5 py-0.5 rounded-full border border-slate-300 text-[10px] font-extrabold text-sky-700 shadow-xs">
                                    HALUAN 042° NE
                                </div>
                            </div>
                        </div>

                        {{-- Grid Sensor Oseanografi (4 Indikator) --}}
                        <div class="grid grid-cols-2 gap-2 text-xs pt-2">
                            <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                                <div class="text-[10px] text-slate-500 flex items-center gap-1 font-bold">
                                    <i class="fa-solid fa-wind text-sky-600"></i> ANGIN
                                </div>
                                <div class="font-extrabold text-slate-800 text-xs mt-1">14.2 Knots</div>
                                <div class="text-[9px] text-slate-400">Beaufort 4 (Moderat)</div>
                            </div>

                            <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                                <div class="text-[10px] text-slate-500 flex items-center gap-1 font-bold">
                                    <i class="fa-solid fa-water text-cyan-600"></i> GELOMBANG
                                </div>
                                <div class="font-extrabold text-slate-800 text-xs mt-1">1.8 Meter</div>
                                <div class="text-[9px] text-slate-400">Swell Aman</div>
                            </div>

                            <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                                <div class="text-[10px] text-slate-500 flex items-center gap-1 font-bold">
                                    <i class="fa-solid fa-temperature-three-quarters text-emerald-600"></i> SUHU LAUT
                                </div>
                                <div class="font-extrabold text-slate-800 text-xs mt-1">26.4°C</div>
                                <div class="text-[9px] text-slate-400">Tropis Stabil</div>
                            </div>

                            <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200/80">
                                <div class="text-[10px] text-slate-500 flex items-center gap-1 font-bold">
                                    <i class="fa-solid fa-cloud-sun text-amber-600"></i> TEKANAN
                                </div>
                                <div class="font-extrabold text-slate-800 text-xs mt-1">1013 hPa</div>
                                <div class="text-[9px] text-slate-400">Barometer Normal</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BENTO TILE 3 (4 Kolom): RING METERAN BERCAHAYA (METRIK TELEMETRI & KPI) --}}
                <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/90 hover:border-sky-400 hover:shadow-md transition-all duration-300 p-6 flex flex-col justify-between shadow-xs relative">
                    <div>
                        {{-- Header Tile 3 --}}
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-chart-pie text-emerald-600 text-sm"></i>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">{{ Auth::user()->isCustomer() ? 'Ringkasan Status Kargo Saya' : 'Telemetri Armada & SLA' }}</h3>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">{{ Auth::user()->isCustomer() ? 'STATUS SAYA' : 'LIVE METRICS' }}</span>
                        </div>

                        {{-- 3 Circular Radial Progress Gauges (Tema Terang) --}}
                        <div class="grid grid-cols-3 gap-3 my-4">
                            {{-- Gauge 1: SLA On-Time Rate --}}
                            @php
                                $slaDashOffset = round(220 - (220 * $onTimeRate / 100));
                            @endphp
                            <div class="flex flex-col items-center">
                                <div class="relative w-24 h-24 flex items-center justify-center">
                                    <svg viewBox="0 0 80 80" class="w-full h-full transform -rotate-90">
                                        <circle cx="40" cy="40" r="35" stroke="#e2e8f0" stroke-width="6" fill="transparent" />
                                        <circle cx="40" cy="40" r="35" stroke="#10b981" stroke-width="6" stroke-dasharray="220" stroke-dashoffset="{{ $slaDashOffset }}" stroke-linecap="round" fill="transparent" />
                                    </svg>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                        <span class="text-sm font-extrabold text-slate-900 ">{{ $onTimeRate }}%</span>
                                    </div>
                                </div>
                                <span class="text-[11px] font-bold text-slate-700 mt-2 text-center">{{ Auth::user()->isCustomer() ? 'Ketepatan Waktu' : 'SLA On-Time' }}</span>
                                <span class="text-[9px] text-emerald-700 font-bold">Target: &gt;95%</span>
                            </div>

                            {{-- Gauge 2: Dalam Pelayaran --}}
                            @php
                                $transitPct = $totalShipments > 0 ? round(($inTransitCount / $totalShipments) * 100) : 0;
                                $transitDashOffset = round(220 - (220 * $transitPct / 100));
                            @endphp
                            <div class="flex flex-col items-center">
                                <div class="relative w-24 h-24 flex items-center justify-center">
                                    <svg viewBox="0 0 80 80" class="w-full h-full transform -rotate-90">
                                        <circle cx="40" cy="40" r="35" stroke="#e2e8f0" stroke-width="6" fill="transparent" />
                                        <circle cx="40" cy="40" r="35" stroke="#f59e0b" stroke-width="6" stroke-dasharray="220" stroke-dashoffset="{{ $transitDashOffset }}" stroke-linecap="round" fill="transparent" />
                                    </svg>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                        <span class="text-sm font-extrabold text-slate-900 ">{{ $inTransitCount }}</span>
                                    </div>
                                </div>
                                <span class="text-[11px] font-bold text-slate-700 mt-2 text-center">{{ Auth::user()->isCustomer() ? 'Sedang Berlayar' : 'Berlayar' }}</span>
                                <span class="text-[9px] text-amber-700 font-bold">{{ Auth::user()->isCustomer() ? 'Dalam Rute' : 'Aktif di Laut' }}</span>
                            </div>

                            {{-- Gauge 3: Total Manifest --}}
                            <div class="flex flex-col items-center">
                                <div class="relative w-24 h-24 flex items-center justify-center">
                                    <svg viewBox="0 0 80 80" class="w-full h-full transform -rotate-90">
                                        <circle cx="40" cy="40" r="35" stroke="#e2e8f0" stroke-width="6" fill="transparent" />
                                        <circle cx="40" cy="40" r="35" stroke="#0284c7" stroke-width="6" stroke-dasharray="220" stroke-dashoffset="0" stroke-linecap="round" fill="transparent" />
                                    </svg>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                        <span class="text-sm font-extrabold text-slate-900 ">{{ $totalShipments }}</span>
                                    </div>
                                </div>
                                <span class="text-[11px] font-bold text-slate-700 mt-2 text-center">{{ Auth::user()->isCustomer() ? 'Total Kargo' : 'Manifest' }}</span>
                                <span class="text-[9px] text-sky-700 font-bold">{{ Auth::user()->isCustomer() ? 'Milik Anda' : 'Total Kargo' }}</span>
                            </div>
                        </div>

                        {{-- Strip Peringatan Operasional Lapangan --}}
                        <div class="space-y-2 mt-4 pt-4 border-t border-slate-100">
                            {{-- Lencana Keterlambatan --}}
                            <div class="p-3 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2 text-rose-800">
                                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                                    <span class="font-bold">{{ Auth::user()->isCustomer() ? 'Keterlambatan Kargo Saya' : 'Keterlambatan Armada' }}</span>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-lg bg-rose-100 text-rose-800 font-extrabold border border-rose-200">
                                    {{ $delayedCount }} {{ Auth::user()->isCustomer() ? 'Kargo' : 'Kapal' }}
                                </span>
                            </div>

                            {{-- Lencana Kendala Lapangan --}}
                            <div class="p-3 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2 text-amber-800">
                                    <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                                    <span class="font-bold">{{ Auth::user()->isCustomer() ? 'Kendala Pengiriman Kargo' : 'Kendala Lapangan (Exception)' }}</span>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-lg bg-amber-100 text-amber-800 font-extrabold border border-amber-200">
                                    {{ $openExceptionsCount }} Kasus
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 3. BENTO GRID BARIS 2: RADAR MARITIM GLOBAL (7 Kolom) + ANTREAN PELAYARAN KARGO (5 Kolom) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

                {{-- BENTO TILE 4 (7 Kolom): RADAR MARITIM 2D/3D GLOBE MORPHING (GAYA DESIGN SPELLS / VERCEL REGIONS) --}}
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden relative flex flex-col h-[585px]">
                    {{-- Header Bar Peta Radar dengan Toggle Kontrol Vercel Style --}}
                    <div class="p-4 border-b border-slate-200 bg-slate-50 flex flex-wrap items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-sky-500 animate-ping"></span>
                            <div>
                                <h3 class="font-extrabold text-slate-800 uppercase tracking-wider">{{ Auth::user()->isCustomer() ? 'Peta Rute & Wilayah Pelabuhan' : 'Radar Maritim Global & Jaringan Armada' }}</h3>
                                <p class="text-[10px] text-slate-400 ">{{ Auth::user()->isCustomer() ? 'Visualisasi Interaktif Rute Pelayaran dan Pelabuhan Muatan Anda' : 'Proyeksi Morphing 2D/3D (Vercel Network Architecture Style)' }}</p>
                            </div>
                        </div>

                        {{-- Tombol Segmented Toggle: 2D Peta Datar / 3D Globe / Satelit GIS --}}
                        <div class="flex items-center bg-slate-200/80 p-1 rounded-xl border border-slate-300/80 text-xs font-bold shadow-2xs">
                            <button id="btn-mode-flat" onclick="switchMapMode('flat')" title="Lihat wilayah pada peta 2D datar" class="px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition bg-white text-slate-900 shadow-xs">
                                <svg class="w-3.5 h-3.5 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="M21 15l-5-5L5 21"/>
                                </svg>
                                <span>Peta 2D</span>
                            </button>
                            <button id="btn-mode-globe" onclick="switchMapMode('globe')" title="Lihat wilayah pada bola dunia 3D interaktif" class="px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition text-slate-600 hover:text-slate-900">
                                <i class="fa-solid fa-earth-americas text-xs text-sky-600"></i>
                                <span>Globe 3D</span>
                            </button>
                            <button id="btn-mode-leaflet" onclick="switchMapMode('leaflet')" title="Beralih ke peta satelit GIS OSM" class="px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition text-slate-600 hover:text-slate-900">
                                <i class="fa-solid fa-satellite text-xs text-slate-500"></i>
                                <span>Satelit GIS</span>
                            </button>
                        </div>
                    </div>

                    {{-- Konten Utama: Sidebar Wilayah Pelabuhan (Kiri) + Kanvas Peta Morphing / GIS (Kanan) --}}
                    <div class="flex-1 flex flex-col md:flex-row overflow-hidden relative">
                        
                        {{-- Panel Sisi Kiri: Daftar Wilayah Pelabuhan (Vercel Regions Sidebar) --}}
                        <div id="regions-sidebar" class="hidden md:flex flex-col w-56 border-r border-slate-200 bg-slate-50/70 p-3 space-y-2 overflow-y-auto custom-scroll text-xs transition-all duration-200">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                                <span class="font-bold text-[11px] text-slate-700 uppercase tracking-wider">{{ Auth::user()->isCustomer() ? 'Pelabuhan Kargo Anda' : 'Wilayah Pelabuhan' }}</span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-sky-100 text-sky-800 font-bold ">{{ Auth::user()->isCustomer() ? '5 Hub Kargo' : '6 Hub' }}</span>
                            </div>

                            {{-- Item Wilayah Pelabuhan --}}
                            <div class="space-y-1.5">
                                <button onclick="selectPortFromList('IDTPP')" class="w-full text-left p-2 rounded-xl border border-slate-200 bg-white hover:border-sky-400 hover:bg-sky-50/40 transition group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
                                            <strong class="text-slate-800 text-[11px] group-hover:text-sky-600">Tanjung Priok</strong>
                                        </div>
                                        <span class="text-[9px] text-slate-400">IDTPP</span>
                                    </div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">Jakarta, Indonesia</div>
                                </button>

                                <button onclick="selectPortFromList('NLRTM')" class="w-full text-left p-2 rounded-xl border border-slate-200 bg-white hover:border-sky-400 hover:bg-sky-50/40 transition group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                                            <strong class="text-slate-800 text-[11px] group-hover:text-sky-600">Port of Rotterdam</strong>
                                        </div>
                                        <span class="text-[9px] text-slate-400">NLRTM</span>
                                    </div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">Rotterdam, Belanda</div>
                                </button>

                                <button onclick="selectPortFromList('SGSIN')" class="w-full text-left p-2 rounded-xl border border-slate-200 bg-white hover:border-sky-400 hover:bg-sky-50/40 transition group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            <strong class="text-slate-800 text-[11px] group-hover:text-sky-600">Port of Singapore</strong>
                                        </div>
                                        <span class="text-[9px] text-slate-400">SGSIN</span>
                                    </div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">PSA Singapore, Hub Transit</div>
                                </button>

                                @if(!Auth::user()->isCustomer())
                                <button onclick="selectPortFromList('DEHAM')" class="w-full text-left p-2 rounded-xl border border-slate-200 bg-white hover:border-sky-400 hover:bg-sky-50/40 transition group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                                            <strong class="text-slate-800 text-[11px] group-hover:text-sky-600">Port of Hamburg</strong>
                                        </div>
                                        <span class="text-[9px] text-slate-400">DEHAM</span>
                                    </div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">Hamburg, Jerman</div>
                                </button>
                                @endif

                                <button onclick="selectPortFromList('AUBOT')" class="w-full text-left p-2 rounded-xl border border-slate-200 bg-white hover:border-sky-400 hover:bg-sky-50/40 transition group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                                            <strong class="text-slate-800 text-[11px] group-hover:text-sky-600">Port Botany</strong>
                                        </div>
                                        <span class="text-[9px] text-slate-400">AUBOT</span>
                                    </div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">Sydney, Australia</div>
                                </button>

                                <button onclick="selectPortFromList('IDSUB')" class="w-full text-left p-2 rounded-xl border border-slate-200 bg-white hover:border-sky-400 hover:bg-sky-50/40 transition group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            <strong class="text-slate-800 text-[11px] group-hover:text-sky-600">Tanjung Perak</strong>
                                        </div>
                                        <span class="text-[9px] text-slate-400">IDSUB</span>
                                    </div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">Surabaya, Indonesia</div>
                                </button>
                            </div>

                            <div class="pt-2 text-[10px] text-slate-400 leading-tight">
                                <i class="fa-solid fa-circle-info mr-1"></i> Klik wilayah untuk rotasi & fokus otomatis pada peta/globe.
                            </div>
                        </div>

                        {{-- Area Kanvas Utama: Kanvas Morphing 2D/3D & Peta Leaflet --}}
                        <div class="flex-1 relative bg-white overflow-hidden h-full">
                            
                            {{-- 1. Kanvas Morphing 2D Peta Datar & 3D Globe --}}
                            <div id="morph-canvas-wrapper" class="w-full h-full absolute inset-0 z-10 transition-opacity duration-300 opacity-100 pointer-events-auto">
                                <canvas id="morph-canvas" class="w-full h-full cursor-grab active:cursor-grabbing block"></canvas>
                                
                                {{-- Petunjuk Interaktif Drag 3D Globe --}}
                                <div id="globe-drag-hint" class="hidden absolute top-4 left-4 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl border border-slate-200 text-[11px] text-slate-600 shadow-sm pointer-events-none">
                                    <i class="fa-solid fa-hand-pointer text-sky-600 mr-1.5"></i> Geser mouse untuk memutar Globe 3D
                                </div>
                            </div>

                            {{-- 2. Kanvas Peta Leaflet Satelit GIS (Tidak display:none agar render ukuran akurat) --}}
                            <div id="radar-canvas-wrapper" class="w-full h-full absolute inset-0 z-0 transition-opacity duration-300 opacity-0 pointer-events-none">
                                <div id="radar-canvas" class="w-full h-full bg-[#f2efe9]"></div>
                            </div>

                            {{-- Floating Glass HUD: Ringkasan Telemetri Peta Melayang --}}
                            <div class="absolute bottom-4 left-4 z-[400] bg-white/95 backdrop-blur-md p-3.5 rounded-2xl border border-slate-200 shadow-lg max-w-xs text-xs space-y-2 pointer-events-auto">
                                <div class="flex items-center justify-between gap-4">
                                    <strong class="text-slate-900 font-extrabold flex items-center gap-2">
                                        <i class="fa-solid fa-shield-heart text-emerald-600"></i> {{ Auth::user()->isCustomer() ? 'Ketepatan Kargo Saya' : 'SLA Ketepatan Armada' }}
                                    </strong>
                                    <span class="px-2 py-0.5 rounded-lg font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200 text-[11px]">{{ $onTimeRate }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-emerald-500 to-sky-500 h-full rounded-full" style="width: {{ $onTimeRate }}%;"></div>
                                </div>
                                <p class="text-[10px] text-slate-500 leading-relaxed">
                                    {{ $totalShipments - $delayedCount }} dari {{ $totalShipments }} kargo {{ Auth::user()->isCustomer() ? 'Anda tepat waktu' : 'berlayar tepat waktu' }}. Putar globe 3D atau klik wilayah untuk inspeksi.
                                </p>
                            </div>

                            {{-- Floating Detail Popover Saat Wilayah Dipilih --}}
                            <div id="selected-port-card" class="hidden absolute top-4 right-4 z-[400] bg-white/95 backdrop-blur-md p-3.5 rounded-2xl border border-sky-300 shadow-xl max-w-xs text-xs space-y-1.5 transition-all">
                                <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-1.5">
                                    <span id="port-card-code" class="px-2 py-0.5 rounded bg-sky-100 text-sky-800 font-bold text-[10px]">IDTPP</span>
                                    <button onclick="closePortCard()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div id="port-card-title" class="font-extrabold text-slate-900 text-sm">Port of Tanjung Priok</div>
                                <div id="port-card-city" class="text-slate-500 text-[11px]">Jakarta, Indonesia</div>
                                <div id="port-card-coords" class="text-sky-700 text-[10px] pt-1">LAT: -6.1038 • LNG: 106.8827</div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- BENTO TILE 5 (5 Kolom): ANTREAN MANIFEST PELAYARAN KARGO --}}
                <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/90 shadow-sm flex flex-col h-[585px] overflow-hidden">
                    
                    {{-- Header Antrean Kargo & Filter Tab --}}
                    <div class="p-4 border-b border-slate-200 bg-slate-50 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-boxes-stacked text-sky-600 text-sm"></i>
                                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">{{ Auth::user()->isCustomer() ? 'Daftar Kargo Pengiriman Saya' : 'Antrean Manifest Pelayaran' }}</h3>
                            </div>
                            <span class="text-xs text-sky-700 font-bold">{{ $shipments->total() }} {{ Auth::user()->isCustomer() ? 'Kargo Terdaftar' : 'Data Tercatat' }}</span>
                        </div>

                        {{-- Kolom Pencarian --}}
                        <form method="GET" action="{{ route('dashboard') }}" class="space-y-2">
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Resi atau Komoditas kargo..." class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl border border-slate-300 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 bg-white text-slate-800 placeholder-slate-400">
                            </div>

                            {{-- Filter Tabs Status --}}
                            <div class="flex flex-wrap items-center gap-1.5 text-[11px] pt-1">
                                <a href="{{ route('dashboard') }}" class="px-3 py-1 rounded-lg font-bold transition {{ !request('status') && !request('delay_status') ? 'bg-[#1b1f3b] text-white' : 'bg-slate-200/80 text-slate-700 hover:bg-slate-300' }}">
                                    Semua
                                </a>
                                <a href="{{ route('dashboard', ['status' => 'IN_TRANSIT']) }}" class="px-3 py-1 rounded-lg font-bold transition {{ request('status') === 'IN_TRANSIT' ? 'bg-amber-500 text-white' : 'bg-amber-100 text-amber-800 hover:bg-amber-200' }}">
                                    Berlayar ({{ $inTransitCount }})
                                </a>
                                <a href="{{ route('dashboard', ['delay_status' => 'DELAYED']) }}" class="px-3 py-1 rounded-lg font-bold transition {{ request('delay_status') === 'DELAYED' ? 'bg-rose-500 text-white' : 'bg-rose-100 text-rose-800 hover:bg-rose-200' }}">
                                    Terlambat ({{ $delayedCount }})
                                </a>
                                <a href="{{ route('dashboard', ['status' => 'DELIVERED']) }}" class="px-3 py-1 rounded-lg font-bold transition {{ request('status') === 'DELIVERED' ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' }}">
                                    Tiba ({{ $deliveredCount }})
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- Daftar Kartu Manifest Kargo Pelayaran (Scrollable Bento Cards) --}}
                    <div class="flex-1 overflow-y-auto custom-scroll p-3.5 space-y-3">
                        @forelse($shipments as $s)
                            @php
                                $statusBadge = [
                                    'CREATED'    => 'bg-slate-200 text-slate-700',
                                    'BOOKED'     => 'bg-sky-500 text-white',
                                    'PICKED_UP'  => 'bg-purple-600 text-white',
                                    'IN_TRANSIT' => 'bg-amber-500 text-white animate-pulse',
                                    'DELIVERED'  => 'bg-emerald-600 text-white',
                                ];
                                $statusLabel = [
                                    'CREATED'    => 'DIBUAT',
                                    'BOOKED'     => 'DIPESAN',
                                    'PICKED_UP'  => 'DIMUAT',
                                    'IN_TRANSIT' => 'BERLAYAR',
                                    'DELIVERED'  => 'TIBA DI TUJUAN',
                                ];
                            @endphp
                            <div class="p-3.5 rounded-2xl border border-slate-200 bg-white hover:border-sky-400 hover:bg-sky-50/30 transition-all duration-200 shadow-2xs group cursor-pointer" onclick="focusOnShipment({{ $s->id }}, {{ $s->latestEvent?->latitude ?? $s->origin->latitude }}, {{ $s->latestEvent?->longitude ?? $s->origin->longitude }})">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-extrabold text-sm text-slate-900 group-hover:text-sky-600 transition ">{{ $s->shipment_number }}</span>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $statusBadge[$s->current_status] ?? 'bg-slate-200 text-slate-800' }}">
                                                {{ $statusLabel[$s->current_status] ?? $s->current_status }}
                                            </span>
                                        </div>
                                        <div class="text-[11px] text-slate-500 mt-1">
                                            <i class="fa-solid fa-ship text-sky-600 mr-1.5"></i> {{ $s->carrier->name }} ({{ $s->carrier->code }})
                                        </div>
                                    </div>

                                    {{-- Lencana Status Keterlambatan --}}
                                    <div>
                                        @if($s->delay_status === 'DELAYED')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200 flex items-center gap-1 ">
                                                <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> +{{ $s->delay_hours }} Jam
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 ">
                                                TEPAT WAKTU
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Garis Koridor Lintasan Rute --}}
                                <div class="mt-3 p-2 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                                    <div class="text-left">
                                        <div class="font-bold text-slate-800">{{ $s->origin->city }}</div>
                                        <div class="text-[10px] text-slate-400 ">{{ $s->origin->code }}</div>
                                    </div>
                                    <div class="flex-1 px-3 flex flex-col items-center">
                                        <div class="w-full flex items-center">
                                            <div class="w-2 h-2 rounded-full bg-sky-500"></div>
                                            <div class="flex-1 border-t-2 border-dashed border-sky-300 mx-1"></div>
                                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                        </div>
                                        <span class="text-[9px] text-slate-400 mt-0.5">KORIDOR LAUT</span>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-slate-800">{{ $s->destination->city }}</div>
                                        <div class="text-[10px] text-slate-400 ">{{ $s->destination->code }}</div>
                                    </div>
                                </div>

                                {{-- Footer Informasi Komoditas & Tautan --}}
                                <div class="mt-2.5 flex items-center justify-between text-[11px] text-slate-500 pt-2 border-t border-slate-100">
                                    <span class="truncate max-w-[210px]" title="{{ $s->cargo_type }}"><i class="fa-solid fa-box-open mr-1 text-slate-400"></i> {{ $s->cargo_type }}</span>
                                    <a href="{{ route('shipments.show', $s) }}" class="font-bold text-sky-600 hover:text-sky-800 flex items-center gap-1 group/btn">
                                        <span>{{ Auth::user()->isCustomer() ? 'Lacak & Riwayat' : 'Detail & Kendali' }}</span>
                                        <i class="fa-solid fa-chevron-right text-[10px] group-hover/btn:translate-x-0.5 transition"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-inbox text-3xl mb-2 text-slate-300"></i>
                                <p>Tidak ada kargo pengiriman yang cocok dengan pencarian.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="p-3 border-t border-slate-200 bg-slate-50 text-xs">
                        {{ $shipments->links() }}
                    </div>
                </div>

            </div>

            {{-- 4. BENTO GRID BARIS 3: DIAGRAM CHART.JS & PRIORITAS GANGGUAN OPERASIONAL --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- BENTO TILE 6: GRAFIK BATANG CHART.JS --}}
                <div class="bg-white p-5 rounded-3xl border border-slate-200/90 shadow-sm">
                    <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-chart-column text-sky-600 text-sm"></i>
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">{{ Auth::user()->isCustomer() ? 'Status Pelayaran Kargo Saya' : 'Distribusi Volume Armada per Status' }}</h4>
                        </div>
                        <span class="text-[10px] px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-bold">Chart.js Engine</span>
                    </div>
                    <div class="h-44 relative">
                        <canvas id="fleetStatusBarChart"></canvas>
                    </div>
                </div>

                {{-- BENTO TILE 7: PRIORITAS GANGGUAN & RESPON ANOMALI --}}
                <div class="bg-white p-5 rounded-3xl border border-slate-200/90 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-triangle-exclamation text-rose-600 text-sm"></i>
                                <h4 class="text-xs font-bold text-rose-800 uppercase tracking-wider">{{ Auth::user()->isCustomer() ? 'Peringatan Operasional & Kendala Kargo' : 'Prioritas Gangguan & Resolusi Kendala' }}</h4>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700">LEVEL 1 ALERT</span>
                        </div>
                        <div class="space-y-2.5 text-xs">
                            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900">
                                <div class="font-bold flex items-center justify-between">
                                    <span class="text-slate-900 ">KARGO #{{ $flagship ? $flagship->shipment_number : 'CTG-2026-0081' }}</span>
                                    <span class="text-[10px] bg-rose-200 text-rose-800 px-2 py-0.5 rounded-md font-bold">BADAI MONSUN</span>
                                </div>
                                <p class="text-[11px] text-rose-700 mt-2 leading-relaxed">
                                    Peringatan cuaca ekstrem di perairan Samudra Hindia bagian timur. Kecepatan kapal dibatasi 14 knot untuk mencegah deformasi kontainer & pergeseran muatan.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-right">
                        @if($flagship)
                            <a href="{{ route('shipments.show', $flagship) }}" class="text-xs font-bold text-rose-700 hover:text-rose-900 flex items-center justify-end gap-1.5 transition">
                                <span>{{ Auth::user()->isCustomer() ? 'Lihat Informasi Kendala & Rute Kargo' : 'Tinjau Prosedur Investigasi Lapangan' }}</span>
                                <i class="fa-solid fa-arrow-right text-[11px]"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 5. MODAL TAMBAH KARGO BARU (Khusus Administrator & Operator) --}}
            @if(!Auth::user()->isCustomer())
                <div x-show="showCreateModal" 
                     x-cloak 
                     class="fixed inset-0 z-[99999] overflow-y-auto" 
                     style="display: none;"
                     @keydown.escape.window="showCreateModal = false">
                    
                    {{-- Backdrop Gelap dengan Blur --}}
                    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity" @click="showCreateModal = false"></div>

                    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
                        <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-slate-200">
                            
                            {{-- Header Modal --}}
                            <div class="bg-[#1b1f3b] px-6 py-5 flex items-center justify-between text-white border-b border-[#292f54]">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-sky-500/20 border border-sky-400/40 text-sky-400 flex items-center justify-center text-lg">
                                        <i class="fa-solid fa-anchor"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-white tracking-tight">Daftarkan Manifest Kargo Baru</h3>
                                        <p class="text-xs text-slate-300 mt-0.5">Siklus Awal: <span class="px-2 py-0.5 rounded bg-sky-500/20 text-sky-300 font-bold border border-sky-400/30">DIBUAT (CREATED)</span></p>
                                    </div>
                                </div>
                                <button type="button" @click="showCreateModal = false" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">
                                    <i class="fa-solid fa-xmark text-base"></i>
                                </button>
                            </div>

                            {{-- Formulir Registrasi Kargo --}}
                            <form method="POST" action="{{ route('shipments.store') }}" class="p-6 sm:p-7 space-y-5 text-xs text-slate-700">
                                @csrf

                                {{-- Bagian 1: Mitra & Maskapai --}}
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 space-y-3">
                                    <div class="text-[11px] font-bold text-sky-700 uppercase tracking-wider flex items-center gap-2">
                                        <i class="fa-solid fa-users text-sky-600"></i>
                                        <span>Pihak Pengirim & Maskapai Armada</span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {{-- Pelanggan --}}
                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1.5">Pelanggan / Pemilik Kargo</label>
                                            <select name="customer_id" required class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-800">
                                                <option value="">-- Pilih Pelanggan --</option>
                                                @foreach($customers as $cust)
                                                    <option value="{{ $cust->id }}">{{ $cust->name }} ({{ $cust->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Maskapai --}}
                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1.5">Maskapai Pelayaran (Carrier)</label>
                                            <select name="carrier_id" required class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-800">
                                                <option value="">-- Pilih Maskapai Kapal --</option>
                                                @foreach($carriers as $c)
                                                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bagian 2: Rute Pelabuhan --}}
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 space-y-3">
                                    <div class="text-[11px] font-bold text-sky-700 uppercase tracking-wider flex items-center gap-2">
                                        <i class="fa-solid fa-route text-sky-600"></i>
                                        <span>Rute Koridor Pelabuhan</span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {{-- Asal --}}
                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1.5">Pelabuhan Keberangkatan (Asal)</label>
                                            <select name="origin_id" required class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-800">
                                                <option value="">-- Pilih Pelabuhan Asal --</option>
                                                @foreach($locations as $loc)
                                                    <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->city }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Tujuan --}}
                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1.5">Pelabuhan Pembongkaran (Tujuan)</label>
                                            <select name="destination_id" required class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-800">
                                                <option value="">-- Pilih Pelabuhan Tujuan --</option>
                                                @foreach($locations as $loc)
                                                    <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->city }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bagian 3: Spesifikasi Muatan Kargo --}}
                                <div class="space-y-4">
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1.5">Nama Muatan / Komoditas Kargo</label>
                                        <input type="text" name="cargo_type" required placeholder="Contoh: Kopi Robusta Lampung, Pupuk Organik, Biji Kakao..." class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-800 placeholder-slate-400">
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1.5">Total Berat Muatan (Kilogram)</label>
                                            <div class="relative">
                                                <input type="number" step="0.01" name="weight_kg" required placeholder="18500" class="w-full text-xs rounded-xl border border-slate-300 py-2.5 pl-3 pr-10 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-800">
                                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 font-bold pointer-events-none">KG</span>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1.5">Volume Muatan (Meter Kubik)</label>
                                            <div class="relative">
                                                <input type="number" step="0.01" name="volume_cbm" required placeholder="45.5" class="w-full text-xs rounded-xl border border-slate-300 py-2.5 pl-3 pr-12 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-800">
                                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 font-bold pointer-events-none">CBM</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bagian 4: Jadwal Pelayaran --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1.5">Rencana Keberangkatan (ETD)</label>
                                        <input type="datetime-local" name="expected_departure" required value="{{ date('Y-m-d\TH:i') }}" class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-800">
                                    </div>
                                    <div>
                                        <label class="block font-bold text-slate-700 mb-1.5">Estimasi Tiba di Tujuan (ETA)</label>
                                        <input type="datetime-local" name="expected_arrival" required value="{{ date('Y-m-d\TH:i', strtotime('+14 days')) }}" class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-800">
                                    </div>
                                </div>

                                {{-- Bagian 5: Catatan Operasional --}}
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1.5">Catatan Operasional (Opsional)</label>
                                    <textarea name="notes" rows="2" placeholder="Catatan segel kontainer, temperatur pendingin reefer, petunjuk penanganan khusus..." class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-800 placeholder-slate-400"></textarea>
                                </div>

                                {{-- Tombol Aksi Footer --}}
                                <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                                    <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#1b1f3b] hover:bg-sky-600 text-white font-bold shadow-md transition flex items-center gap-2">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Daftarkan & Simpan Kargo</span>
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            @endif

            {{-- 6. MODAL PENGAJUAN BOOKING KONTAINER (Khusus Pelanggan / Customer) --}}
            @if(Auth::user()->isCustomer())
                <div x-show="showBookingModal" 
                     x-cloak 
                     class="fixed inset-0 z-[99999] overflow-y-auto" 
                     style="display: none;"
                     @keydown.escape.window="showBookingModal = false">
                    
                    {{-- Backdrop Gelap dengan Blur --}}
                    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity" @click="showBookingModal = false"></div>

                    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6" x-data="{ bookedSuccess: false, originPort: '', destPort: '', cargoType: '', weight: '' }">
                        <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                            
                            {{-- Header Modal --}}
                            <div class="bg-[#1b1f3b] px-6 py-5 flex items-center justify-between text-white border-b border-[#292f54]">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-sky-500/20 border border-sky-400/40 text-sky-400 flex items-center justify-center text-lg">
                                        <i class="fa-solid fa-ship"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-white tracking-tight">Booking & Pengajuan Kargo Baru</h3>
                                        <p class="text-xs text-slate-300 mt-0.5">Reservasi Kontainer Maritim (FCL & LCL) & Dispatcher Service</p>
                                    </div>
                                </div>
                                <button type="button" @click="showBookingModal = false" class="text-slate-400 hover:text-white transition">
                                    <i class="fa-solid fa-xmark text-lg"></i>
                                </button>
                            </div>

                            {{-- Isi Modal --}}
                            <div class="p-6 space-y-5">
                                {{-- Kotak Edukasi / Alur SOP --}}
                                <div class="p-4 rounded-2xl bg-sky-50 border border-sky-200 flex items-start gap-3 text-xs text-sky-900 leading-relaxed">
                                    <i class="fa-solid fa-circle-info text-sky-600 text-base mt-0.5"></i>
                                    <div>
                                        <strong class="font-bold text-sky-950">Alur Resmi Pemesanan Kontainer:</strong>
                                        <p class="mt-1 text-sky-800">Sesuai SOP pelabuhan & arsitektur sistem CargoTrace, manifest kontainer dan nomor pelacakan resmi diterbitkan langsung oleh <strong>Tim Dispatcher / Operator Logistik</strong> setelah slot kapal dan dokumen muatan Anda diverifikasi.</p>
                                    </div>
                                </div>

                                <template x-if="bookedSuccess">
                                    <div class="p-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-center space-y-3">
                                        <div class="w-12 h-12 mx-auto rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </div>
                                        <h4 class="font-extrabold text-slate-900 text-sm">Permintaan Booking Berhasil Dikirim!</h4>
                                        <p class="text-xs text-slate-600 max-w-md mx-auto">
                                            Tiket reservasi kontainer Anda telah diteruskan ke Operator Dispatcher Pelabuhan. Operator akan menyiapkan slot manifest kargo Anda dan menghubungi Anda sesaat lagi.
                                        </p>
                                        <button @click="bookedSuccess = false; showBookingModal = false" class="mt-2 px-5 py-2.5 rounded-xl bg-[#1b1f3b] text-white text-xs font-bold hover:bg-sky-600 transition">
                                            Tutup & Kembali ke Dashboard
                                        </button>
                                    </div>
                                </template>

                                <template x-if="!bookedSuccess">
                                    <form @submit.prevent="bookedSuccess = true" class="space-y-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pelabuhan Muat (Asal)</label>
                                                <select x-model="originPort" required class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-white text-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                                                    <option value="">Pilih Pelabuhan Asal...</option>
                                                    <option value="Jakarta (IDTPP)">Tanjung Priok, Jakarta (IDTPP)</option>
                                                    <option value="Surabaya (IDSUB)">Tanjung Perak, Surabaya (IDSUB)</option>
                                                    <option value="Singapore (SGSIN)">Port of Singapore (SGSIN)</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pelabuhan Bongkar (Tujuan)</label>
                                                <select x-model="destPort" required class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-white text-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                                                    <option value="">Pilih Pelabuhan Tujuan...</option>
                                                    <option value="Rotterdam (NLRTM)">Port of Rotterdam, Belanda (NLRTM)</option>
                                                    <option value="Sydney (AUBOT)">Port Botany, Sydney (AUBOT)</option>
                                                    <option value="Singapore (SGSIN)">Port of Singapore (SGSIN)</option>
                                                    <option value="Los Angeles (USLAX)">Port of Los Angeles (USLAX)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Komoditas Barang</label>
                                                <input type="text" x-model="cargoType" required placeholder="Contoh: Biji Kopi Robusta / Elektronik" class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-white text-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Estimasi Bobot Muatan (Kg)</label>
                                                <input type="number" x-model="weight" required min="100" placeholder="Contoh: 18500" class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3 bg-white text-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                                            </div>
                                        </div>

                                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 flex flex-wrap items-center justify-between gap-2">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-headset text-sky-600"></i>
                                                <span>Layanan Dispatcher & Call Center:</span>
                                            </div>
                                            <span class="font-extrabold text-sky-700">+62 21 8000-CARGO (24/7)</span>
                                        </div>

                                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                                            <button type="button" @click="showBookingModal = false" class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-100 transition">
                                                Batal
                                            </button>
                                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-sky-600 text-white text-xs font-bold hover:bg-sky-700 transition flex items-center gap-2 shadow-xs">
                                                <i class="fa-solid fa-paper-plane text-xs"></i>
                                                <span>Kirim Permintaan Booking</span>
                                            </button>
                                        </div>
                                    </form>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- FOOTER OPERASIONAL MARITIM --}}
            <footer class="pt-6 pb-4 border-t border-slate-200 text-xs text-slate-500">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 rounded-lg bg-[#1b1f3b] text-sky-400 flex items-center justify-center text-xs font-bold">
                            <i class="fa-solid fa-anchor text-[11px]"></i>
                        </div>
                        <span class="font-extrabold text-slate-800">CargoTrace &copy; 2026</span>
                        <span class="text-slate-300">•</span>
                        <span>Sistem Visibilitas & Pelacakan Kargo Maritim Global (Bento Deck)</span>
                    </div>
                    <div class="flex items-center gap-3 text-[11px]">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Sistem & API Sanctum Aktif (100% Operational)
                        </span>
                        <span class="text-slate-400 ">UAS Pemrograman Web 2</span>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    {{-- SCRIPT: JAM UTC, KANVAS MORPHING 2D/3D (VERCEL DESIGN SPELLS), LEAFLET SATELIT, & CHART.JS --}}
    <script>
        let radarMap;
        const markersMap = {};
        let initialBounds = [];

        // 1. Jam UTC Waktu Nyata
        function updateUtcClock() {
            const now = new Date();
            const hours = String(now.getUTCHours()).padStart(2, '0');
            const minutes = String(now.getUTCMinutes()).padStart(2, '0');
            const seconds = String(now.getUTCSeconds()).padStart(2, '0');
            const el = document.getElementById('utc-time-hud');
            if (el) el.innerText = `${hours}:${minutes}:${seconds} UTC`;
        }
        setInterval(updateUtcClock, 1000);
        updateUtcClock();

        // 2. Data Wilayah Pelabuhan & Koridor Maritim (Vercel Regions Inspired)
        const maritimeRegions = [
            { id: 1, code: 'IDTPP', city: 'Jakarta', name: 'Port of Tanjung Priok', country: 'Indonesia', lat: -6.1038, lng: 106.8827, role: '{{ Auth::user()->isCustomer() ? "Pelabuhan Muat Utama" : "Hub Utama (Flagship)" }}', cargo: 'Arabica Coffee, Textile', isDelayed: true },
            { id: 2, code: 'NLRTM', city: 'Rotterdam', name: 'Port of Rotterdam', country: 'Belanda', lat: 51.9496, lng: 4.1453, role: '{{ Auth::user()->isCustomer() ? "Pelabuhan Tujuan Kargo" : "Gateway Eropa Barat" }}', cargo: 'Crude Oil, Electronics', isDelayed: false },
            { id: 3, code: 'SGSIN', city: 'Singapura', name: 'Port of Singapore', country: 'Singapura', lat: 1.2644, lng: 103.8228, role: '{{ Auth::user()->isCustomer() ? "Pelabuhan Tujuan Kargo" : "Hub Transit Malaka" }}', cargo: 'Transshipment Cargo', isDelayed: false },
            @if(!Auth::user()->isCustomer())
            { id: 4, code: 'DEHAM', city: 'Hamburg', name: 'Port of Hamburg', country: 'Jerman', lat: 53.5353, lng: 9.9872, role: 'Koridor Baltik', cargo: 'Machinery & Steel', isDelayed: false },
            @endif
            { id: 5, code: 'AUBOT', city: 'Sydney', name: 'Port Botany', country: 'Australia', lat: -33.9744, lng: 151.2181, role: '{{ Auth::user()->isCustomer() ? "Pelabuhan Tujuan Kargo" : "Terminal Pasifik Selatan" }}', cargo: 'Agricultural Grains', isDelayed: false },
            { id: 6, code: 'IDSUB', city: 'Surabaya', name: 'Port of Tanjung Perak', country: 'Indonesia', lat: -7.1982, lng: 112.7339, role: '{{ Auth::user()->isCustomer() ? "Pelabuhan Muat Kargo" : "Feeder Domestik Timur" }}', cargo: 'Fertilizer, Cocoa', isDelayed: false }
        ];

        // Rute Koridor Kapal
        const getPort = (code) => maritimeRegions.find(p => p.code === code);
        const activeCorridors = [
            { from: getPort('IDTPP'), to: getPort('NLRTM'), label: 'Jakarta - Rotterdam', isDelayed: true },
            { from: getPort('IDTPP'), to: getPort('AUBOT'), label: 'Jakarta - Sydney', isDelayed: false },
            @if(!Auth::user()->isCustomer())
            { from: getPort('IDTPP'), to: getPort('DEHAM'), label: 'Jakarta - Hamburg', isDelayed: false },
            @endif
            { from: getPort('IDSUB'), to: getPort('SGSIN'), label: 'Surabaya - Singapore', isDelayed: false }
        ].filter(c => c.from && c.to);

        // Titik Benua (Stippled World Dots)
        let worldPoints = [];
        fetch('{{ asset("data/world_points.json") }}')
            .then(res => res.json())
            .then(data => { worldPoints = data; })
            .catch(() => {
                // Fallback titik grid jika fetch gagal
                worldPoints = [
                    [55,-130],[40,-100],[30,-90],[10,-70],[-20,-50],[-35,-60],[50,10],[45,2],[55,30],
                    [20,20],[0,20],[-30,25],[30,75],[20,80],[35,105],[50,120],[35,135],[0,105],
                    [-7,110],[-30,135],[-35,145],[52,4],[53,10],[-6,106],[-33,151],[1,103]
                ];
            });

        // 3. Engine Kanvas Morphing 2D Peta Datar & 3D Globe
        let currentMapMode = 'flat'; // 'flat' | 'globe' | 'leaflet'
        let morphT = 0;             // 0 = 2D Flat, 1 = 3D Globe
        let targetMorphT = 0;
        let globeRotationPhi = 95;   // Orientasi longitude awal (fokus Nusantara & Samudra Hindia)
        let globeRotationTheta = 12; // Tilt elevasi
        let isDraggingGlobe = false;
        let lastMousePos = { x: 0, y: 0 };
        let selectedPort = null;
        let pulseTimer = 0;

        const morphCanvas = document.getElementById('morph-canvas');
        const ctx = morphCanvas ? morphCanvas.getContext('2d') : null;

        function resizeMorphCanvas() {
            if (!morphCanvas) return;
            const rect = morphCanvas.parentElement.getBoundingClientRect();
            const dpr = window.devicePixelRatio || 1;
            morphCanvas.width = rect.width * dpr;
            morphCanvas.height = rect.height * dpr;
            ctx.scale(dpr, dpr);
        }
        window.addEventListener('resize', resizeMorphCanvas);
        setTimeout(resizeMorphCanvas, 100);

        // Kontrol Mouse Drag untuk Globe 3D
        if (morphCanvas) {
            morphCanvas.addEventListener('mousedown', (e) => {
                if (currentMapMode === 'globe' || morphT > 0.5) {
                    isDraggingGlobe = true;
                    lastMousePos = { x: e.clientX, y: e.clientY };
                }
            });

            window.addEventListener('mousemove', (e) => {
                if (isDraggingGlobe) {
                    const dx = e.clientX - lastMousePos.x;
                    const dy = e.clientY - lastMousePos.y;
                    globeRotationPhi -= dx * 0.4;
                    globeRotationTheta = Math.max(-45, Math.min(45, globeRotationTheta - dy * 0.4));
                    lastMousePos = { x: e.clientX, y: e.clientY };
                }
            });

            window.addEventListener('mouseup', () => { isDraggingGlobe = false; });

            // Touch events untuk layar sentuh / mobile
            morphCanvas.addEventListener('touchstart', (e) => {
                if ((currentMapMode === 'globe' || morphT > 0.5) && e.touches.length === 1) {
                    isDraggingGlobe = true;
                    lastMousePos = { x: e.touches[0].clientX, y: e.touches[0].clientY };
                }
            });
            window.addEventListener('touchmove', (e) => {
                if (isDraggingGlobe && e.touches.length === 1) {
                    const dx = e.touches[0].clientX - lastMousePos.x;
                    const dy = e.touches[0].clientY - lastMousePos.y;
                    globeRotationPhi -= dx * 0.4;
                    globeRotationTheta = Math.max(-45, Math.min(45, globeRotationTheta - dy * 0.4));
                    lastMousePos = { x: e.touches[0].clientX, y: e.touches[0].clientY };
                }
            });
            window.addEventListener('touchend', () => { isDraggingGlobe = false; });
        }

        // Loop Animasi Render Morphing 60 FPS
        function renderMorphFrame() {
            if (!ctx || currentMapMode === 'leaflet') {
                requestAnimationFrame(renderMorphFrame);
                return;
            }

            const w = morphCanvas.width / (window.devicePixelRatio || 1);
            const h = morphCanvas.height / (window.devicePixelRatio || 1);
            pulseTimer += 0.04;

            // Transisi halus morphing
            morphT += (targetMorphT - morphT) * 0.08;

            // Auto-rotasi lembut pada Globe 3D jika sedang tidak ditarik mouse
            if ((currentMapMode === 'globe' || morphT > 0.6) && !isDraggingGlobe) {
                globeRotationPhi += 0.12;
            }

            // Bersihkan kanvas putih
            ctx.clearRect(0, 0, w, h);

            const cx = w / 2;
            const cy = h / 2;
            const globeRadius = Math.min(w, h) * 0.38;

            // Latar Belakang Bola Globe 3D saat transisi atau di mode globe
            if (morphT > 0.02) {
                ctx.save();
                ctx.globalAlpha = morphT;
                // Bayangan bola dunia
                const globeGrad = ctx.createRadialGradient(cx - globeRadius * 0.3, cy - globeRadius * 0.3, globeRadius * 0.1, cx, cy, globeRadius);
                globeGrad.addColorStop(0, '#ffffff');
                globeGrad.addColorStop(0.8, '#f1f5f9');
                globeGrad.addColorStop(1, '#e2e8f0');

                ctx.beginPath();
                ctx.arc(cx, cy, globeRadius, 0, Math.PI * 2);
                ctx.fillStyle = globeGrad;
                ctx.fill();
                ctx.lineWidth = 1.5;
                ctx.strokeStyle = '#cbd5e1';
                ctx.stroke();

                // Efek atmosfer halus
                ctx.beginPath();
                ctx.arc(cx, cy, globeRadius + 3, 0, Math.PI * 2);
                ctx.lineWidth = 3;
                ctx.strokeStyle = 'rgba(2, 132, 199, 0.15)';
                ctx.stroke();
                ctx.restore();
            }

            // Fungsi Proyeksi Geometris: 2D Equirectangular & 3D Orthographic
            function project(lat, lng) {
                // 1. Posisi 2D Datar
                const x2d = cx + (lng / 180) * (w * 0.44);
                const y2d = cy - (lat / 90) * (h * 0.42);

                // 2. Posisi 3D Bola Globe
                const rad = Math.PI / 180;
                const phi0 = globeRotationTheta * rad;
                const deltaLambda = (lng - globeRotationPhi) * rad;
                const phi = lat * rad;

                const cosPhi = Math.cos(phi);
                const sinPhi = Math.sin(phi);
                const cosDelta = Math.cos(deltaLambda);
                const sinDelta = Math.sin(deltaLambda);
                const cosPhi0 = Math.cos(phi0);
                const sinPhi0 = Math.sin(phi0);

                const x3d = cx + globeRadius * cosPhi * sinDelta;
                const y3d = cy - globeRadius * (cosPhi0 * sinPhi - sinPhi0 * cosPhi * cosDelta);
                const z3d = sinPhi0 * sinPhi + cosPhi0 * cosPhi * cosDelta; // z > 0 = tampak di depan bola

                // Interpolasi Morphing (Blend 2D -> 3D)
                const x = (1 - morphT) * x2d + morphT * x3d;
                const y = (1 - morphT) * y2d + morphT * y3d;
                const visible = morphT < 0.2 || z3d > 0;
                const depthAlpha = morphT > 0.5 ? Math.max(0, z3d) : 1;

                return { x, y, visible, depthAlpha, z3d };
            }

            // Gambar Titik-Titik Benua (Stippled Dots Matrix)
            ctx.fillStyle = '#94a3b8';
            worldPoints.forEach(pt => {
                const p = project(pt[0], pt[1]);
                if (p.visible) {
                    ctx.save();
                    ctx.globalAlpha = p.depthAlpha * 0.65;
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, 1.4, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.restore();
                }
            });

            // Gambar Garis Koridor Pelayaran Laut (Dashed Great-Circle Arcs)
            activeCorridors.forEach((route, idx) => {
                const pStart = project(route.from.lat, route.from.lng);
                const pEnd = project(route.to.lat, route.to.lng);

                if (pStart.visible || pEnd.visible) {
                    ctx.save();
                    ctx.beginPath();
                    const steps = 30;
                    let first = true;

                    for (let s = 0; s <= steps; s++) {
                        const t = s / steps;
                        // Interpolasi lat & lng sepanjang garis
                        const curLat = route.from.lat + (route.to.lat - route.from.lat) * t;
                        const curLng = route.from.lng + (route.to.lng - route.from.lng) * t;
                        const pt = project(curLat, curLng);

                        // Kelengkungan 3D ke atas permukaan globe
                        let px = pt.x;
                        let py = pt.y;
                        if (morphT > 0.2) {
                            const arcHeight = Math.sin(t * Math.PI) * 16 * morphT;
                            py -= arcHeight;
                        }

                        if (first) {
                            ctx.moveTo(px, py);
                            first = false;
                        } else {
                            ctx.lineTo(px, py);
                        }
                    }

                    ctx.lineWidth = route.isDelayed ? 2.2 : 1.8;
                    ctx.strokeStyle = route.isDelayed ? '#f43f5e' : '#0284c7';
                    ctx.setLineDash([5, 5]);
                    ctx.globalAlpha = Math.min(pStart.depthAlpha, pEnd.depthAlpha) * 0.8;
                    ctx.stroke();

                    // Simbol Kapal Berlayar Animatif di Lintasan
                    const shipProgress = (pulseTimer * 0.15 + idx * 0.25) % 1.0;
                    const shipLat = route.from.lat + (route.to.lat - route.from.lat) * shipProgress;
                    const shipLng = route.from.lng + (route.to.lng - route.from.lng) * shipProgress;
                    const pShip = project(shipLat, shipLng);

                    if (pShip.visible) {
                        ctx.fillStyle = route.isDelayed ? '#e11d48' : '#0284c7';
                        ctx.beginPath();
                        ctx.arc(pShip.x, pShip.y, 3, 0, Math.PI * 2);
                        ctx.fill();
                    }

                    ctx.restore();
                }
            });

            // Gambar Node Titik Wilayah Pelabuhan & LABEL TEKS NAMA WILAYAH
            maritimeRegions.forEach(port => {
                const p = project(port.lat, port.lng);
                if (p.visible && p.depthAlpha > 0.1) {
                    const isSelected = selectedPort && selectedPort.code === port.code;
                    const pulseRadius = 5 + (Math.sin(pulseTimer * 3) + 1) * 3;

                    ctx.save();
                    ctx.globalAlpha = p.depthAlpha;

                    // Lingkaran Berdenyut Transparan
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, pulseRadius, 0, Math.PI * 2);
                    ctx.strokeStyle = isSelected ? '#0284c7' : 'rgba(2, 132, 199, 0.4)';
                    ctx.lineWidth = 1.5;
                    ctx.stroke();

                    // Titik Inti Biru/Hijau Solid
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, 4.5, 0, Math.PI * 2);
                    ctx.fillStyle = port.isDelayed ? '#f43f5e' : '#0284c7';
                    ctx.fill();
                    ctx.strokeStyle = '#ffffff';
                    ctx.lineWidth = 1.5;
                    ctx.stroke();

                    // LABEL NAMA WILAYAH (TETAP TERTERAKAN & SANGAT JELAS DIBACA)
                    const labelText = `${port.city} (${port.code})`;
                    ctx.font = 'bold 11px Inter, sans-serif';
                    const textMetrics = ctx.measureText(labelText);
                    const boxW = textMetrics.width + 16;
                    const boxH = 20;
                    const boxX = p.x + 8;
                    const boxY = p.y - 10;

                    // Background Pill Badge Putih
                    ctx.fillStyle = isSelected ? '#0f172a' : 'rgba(255, 255, 255, 0.95)';
                    ctx.shadowColor = 'rgba(0, 0, 0, 0.12)';
                    ctx.shadowBlur = 6;
                    ctx.shadowOffsetY = 2;

                    ctx.beginPath();
                    ctx.roundRect(boxX, boxY, boxW, boxH, 6);
                    ctx.fill();

                    // Border Pill
                    ctx.shadowColor = 'transparent';
                    ctx.strokeStyle = isSelected ? '#0284c7' : '#cbd5e1';
                    ctx.lineWidth = 1;
                    ctx.stroke();

                    // Teks Nama Wilayah
                    ctx.fillStyle = isSelected ? '#ffffff' : '#0f172a';
                    ctx.fillText(labelText, boxX + 8, boxY + 14);

                    ctx.restore();
                }
            });

            requestAnimationFrame(renderMorphFrame);
        }
        requestAnimationFrame(renderMorphFrame);

        // 4. Tombol Pengalih Mode Peta (2D Flat / 3D Globe / Leaflet GIS)
        function switchMapMode(mode) {
            currentMapMode = mode;

            const btnFlat = document.getElementById('btn-mode-flat');
            const btnGlobe = document.getElementById('btn-mode-globe');
            const btnLeaflet = document.getElementById('btn-mode-leaflet');
            const morphWrapper = document.getElementById('morph-canvas-wrapper');
            const radarWrapper = document.getElementById('radar-canvas-wrapper');
            const regionsSidebar = document.getElementById('regions-sidebar');
            const dragHint = document.getElementById('globe-drag-hint');

            // Reset Kelas Tombol
            const activeClass = 'bg-white text-slate-900 shadow-xs';
            const inactiveClass = 'text-slate-600 hover:text-slate-900';

            [btnFlat, btnGlobe, btnLeaflet].forEach(b => {
                if (b) {
                    b.className = b.className.replace(activeClass, '').replace(inactiveClass, '') + ' ' + inactiveClass;
                }
            });

            if (mode === 'flat') {
                targetMorphT = 0;
                btnFlat.className = btnFlat.className.replace(inactiveClass, activeClass);
                if (regionsSidebar) regionsSidebar.style.display = '';
                if (morphWrapper) {
                    morphWrapper.style.display = 'block';
                    morphWrapper.classList.remove('opacity-0', 'pointer-events-none');
                    morphWrapper.classList.add('opacity-100', 'pointer-events-auto');
                }
                if (radarWrapper) {
                    radarWrapper.style.display = 'none';
                    radarWrapper.classList.add('opacity-0', 'pointer-events-none');
                    radarWrapper.classList.remove('opacity-100', 'pointer-events-auto');
                }
                if (dragHint) dragHint.classList.add('hidden');
                setTimeout(resizeMorphCanvas, 60);
            } else if (mode === 'globe') {
                targetMorphT = 1;
                btnGlobe.className = btnGlobe.className.replace(inactiveClass, activeClass);
                if (regionsSidebar) regionsSidebar.style.display = '';
                if (morphWrapper) {
                    morphWrapper.style.display = 'block';
                    morphWrapper.classList.remove('opacity-0', 'pointer-events-none');
                    morphWrapper.classList.add('opacity-100', 'pointer-events-auto');
                }
                if (radarWrapper) {
                    radarWrapper.style.display = 'none';
                    radarWrapper.classList.add('opacity-0', 'pointer-events-none');
                    radarWrapper.classList.remove('opacity-100', 'pointer-events-auto');
                }
                if (dragHint) dragHint.classList.remove('hidden');
                setTimeout(resizeMorphCanvas, 60);
            } else if (mode === 'leaflet') {
                btnLeaflet.className = btnLeaflet.className.replace(inactiveClass, activeClass);
                
                // Pada mode Satelit GIS, sembunyikan sidebar kiri agar peta OpenStreetMap tampil 100% penuh persis desain sebelumnya
                if (regionsSidebar) regionsSidebar.style.display = 'none';
                
                if (morphWrapper) {
                    morphWrapper.style.display = 'none';
                    morphWrapper.classList.add('opacity-0', 'pointer-events-none');
                    morphWrapper.classList.remove('opacity-100', 'pointer-events-auto');
                }
                if (radarWrapper) {
                    radarWrapper.style.display = 'block';
                    radarWrapper.classList.remove('opacity-0', 'pointer-events-none');
                    radarWrapper.classList.add('opacity-100', 'pointer-events-auto');
                }
                if (dragHint) dragHint.classList.add('hidden');

                // Render ulang ukuran & koordinat Leaflet OpenStreetMap secara instan
                if (radarMap) {
                    setTimeout(() => {
                        radarMap.invalidateSize(true);
                        if (initialBounds && initialBounds.length > 0) {
                            radarMap.fitBounds(initialBounds, { padding: [40, 40] });
                        }
                        // Buka popup kapal tertunda otomatis persis desain sebelumnya
                        const mapData = @json($mapShipments);
                        const delayedItem = mapData.find(d => d.delay_status === 'DELAYED');
                        if (delayedItem && markersMap[delayedItem.id]) {
                            markersMap[delayedItem.id].marker.openPopup();
                        }
                    }, 50);

                    setTimeout(() => {
                        if (radarMap) radarMap.invalidateSize(true);
                    }, 250);
                }
            }
        }

        // 5. Interaksi Klik Daftar Wilayah Pelabuhan di Sisi Kiri
        function selectPortFromList(code) {
            const port = maritimeRegions.find(p => p.code === code);
            if (!port) return;

            selectedPort = port;

            // Jika dalam mode Globe 3D, putar bola dunia menghadap ke pelabuhan ini
            if (currentMapMode === 'globe' || morphT > 0.5) {
                globeRotationPhi = port.lng;
                globeRotationTheta = Math.max(-30, Math.min(30, port.lat));
            }

            // Tampilkan Card Popover Wilayah
            const card = document.getElementById('selected-port-card');
            const titleEl = document.getElementById('port-card-title');
            const cityEl = document.getElementById('port-card-city');
            const coordsEl = document.getElementById('port-card-coords');
            const codeEl = document.getElementById('port-card-code');

            if (card && titleEl) {
                titleEl.innerText = port.name;
                cityEl.innerText = `${port.city}, ${port.country} • ${port.role}`;
                coordsEl.innerText = `LAT: ${port.lat} • LNG: ${port.lng}`;
                codeEl.innerText = port.code;
                card.classList.remove('hidden');
            }

            // Jika dalam mode Leaflet
            if (currentMapMode === 'leaflet' && radarMap) {
                radarMap.flyTo([port.lat, port.lng], 6, { duration: 1.2 });
            }
        }

        function closePortCard() {
            const card = document.getElementById('selected-port-card');
            if (card) card.classList.add('hidden');
            selectedPort = null;
        }

        // 6. Inisialisasi Peta Leaflet Resmi OpenStreetMap
        document.addEventListener('DOMContentLoaded', function () {
            const mapData = @json($mapShipments);
            const mapEl = document.getElementById('radar-canvas');

            if (mapEl) {
                radarMap = L.map('radar-canvas', {
                    zoomControl: true,
                    attributionControl: false
                }).setView([10.0, 75.0], 3);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(radarMap);

                initialBounds = [];

                mapData.forEach(item => {
                    const origin = [item.origin.lat, item.origin.lng];
                    const dest = [item.destination.lat, item.destination.lng];
                    const current = [item.current_location.lat, item.current_location.lng];

                    initialBounds.push(origin, dest, current);

                    const isDelayed = item.delay_status === 'DELAYED';
                    const isDelivered = item.status === 'DELIVERED';
                    const routeColor = isDelayed ? '#f43f5e' : (isDelivered ? '#10b981' : '#0284c7');

                    L.polyline([origin, current, dest], {
                        color: routeColor,
                        weight: 2.8,
                        dashArray: isDelivered ? null : '6, 8',
                        opacity: 0.85
                    }).addTo(radarMap);

                    L.circleMarker(origin, {
                        radius: 5,
                        color: '#0369a1',
                        fillColor: '#ffffff',
                        fillOpacity: 1,
                        weight: 2
                    }).addTo(radarMap).bindPopup(`<b>Pelabuhan Asal:</b> ${item.origin.name}<br><b>No. Kargo:</b> ${item.number}`);

                    L.circleMarker(dest, {
                        radius: 5,
                        color: '#059669',
                        fillColor: '#10b981',
                        fillOpacity: 1,
                        weight: 2
                    }).addTo(radarMap).bindPopup(`<b>Pelabuhan Tujuan:</b> ${item.destination.name}<br><b>No. Kargo:</b> ${item.number}`);

                    let beaconClass = 'beacon-pulse';
                    if (isDelayed) beaconClass += ' beacon-pulse-delayed';
                    if (isDelivered) beaconClass += ' beacon-pulse-delivered';

                    const customBeacon = L.divIcon({
                        className: 'custom-beacon-icon',
                        html: `<div class="${beaconClass}"></div>`,
                        iconSize: [14, 14],
                        iconAnchor: [7, 7]
                    });

                    const beaconMarker = L.marker(current, { icon: customBeacon }).addTo(radarMap);
                    beaconMarker.bindPopup(`
                        <div style="font-family: sans-serif; font-size: 11px; line-height: 1.5; min-width: 170px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                                <strong style="color: #0f172a; font-size: 12px; font-family: Inter, sans-serif;">${item.number}</strong>
                                <span style="font-size: 9px; padding: 1px 6px; border-radius: 4px; font-weight: bold; background: ${isDelayed ? '#ffe4e6' : (isDelivered ? '#d1fae5' : '#e0f2fe')}; color: ${isDelayed ? '#be123c' : (isDelivered ? '#047857' : '#0369a1')};">
                                    ${item.status}
                                </span>
                            </div>
                            <span style="color: #475569;">Maskapai: <b>${item.carrier}</b></span><br>
                            <span style="color: #475569;">Komoditas: ${item.cargo}</span>
                        </div>
                    `);

                    markersMap[item.id] = { marker: beaconMarker, latLng: current };

                    if (isDelayed) {
                        beaconMarker.openPopup();
                    }
                });

                if (initialBounds.length > 0) {
                    radarMap.fitBounds(initialBounds, { padding: [40, 40] });
                }
            }

            // 7. Diagram Batang Chart.js Tema Terang
            const chartCanvas = document.getElementById('fleetStatusBarChart');
            if (chartCanvas) {
                new Chart(chartCanvas, {
                    type: 'bar',
                    data: {
                        labels: ['Dipesan', 'Berlayar', 'Tiba di Tujuan', 'Terlambat'],
                        datasets: [{
                            data: [
                                {{ $totalShipments - $inTransitCount - $deliveredCount }}, 
                                {{ $inTransitCount }}, 
                                {{ $deliveredCount }}, 
                                {{ $delayedCount }}
                            ],
                            backgroundColor: ['#3b82f6', '#f59e0b', '#10b981', '#f43f5e'],
                            borderRadius: 8,
                            barThickness: 28
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                grid: { color: '#f1f5f9' }, 
                                ticks: { font: { size: 10 }, color: '#64748b' } 
                            },
                            x: { 
                                grid: { display: false }, 
                                ticks: { font: { size: 10, weight: 'bold' }, color: '#334155' } 
                            }
                        }
                    }
                });
            }
        });

        // 8. Interaksi Klik Kartu -> Otomatis Geser Peta Radar ke Posisi Kapal
        function focusOnShipment(id, lat, lng) {
            if (currentMapMode === 'leaflet' && radarMap && lat && lng) {
                radarMap.flyTo([lat, lng], 6, { duration: 1.2 });
                if (markersMap[id]) {
                    setTimeout(() => { markersMap[id].marker.openPopup(); }, 1200);
                }
            } else if (lat && lng) {
                // Dalam mode 2D / 3D Globe: putar globe menghadap kapal
                if (currentMapMode === 'globe' || morphT > 0.5) {
                    globeRotationPhi = lng;
                    globeRotationTheta = Math.max(-30, Math.min(30, lat));
                }
            }
        }

        // 9. Reset Tampilan Radar
        function resetRadarView() {
            if (currentMapMode === 'leaflet' && radarMap && initialBounds.length > 0) {
                radarMap.fitBounds(initialBounds, { padding: [40, 40] });
            } else {
                globeRotationPhi = 95;
                globeRotationTheta = 12;
            }
        }
    </script>
</x-app-layout>
