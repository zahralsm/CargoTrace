<x-app-layout>
    {{-- Custom Styles untuk Workbench Maritim --}}
    <style>
        .custom-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        /* Marker Kapal Berdenyut */
        .beacon-pulse {
            width: 12px;
            height: 12px;
            background-color: #0284c7;
            border: 2px solid #ffffff;
            border-radius: 50%;
            position: relative;
            box-shadow: 0 0 8px rgba(2, 132, 199, 0.8);
        }
        .beacon-pulse::after {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            width: 18px;
            height: 18px;
            border: 2px solid #38bdf8;
            border-radius: 50%;
            animation: pulse-ring 2s infinite ease-in-out;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.9); opacity: 0.9; }
            50% { transform: scale(1.6); opacity: 0.2; }
            100% { transform: scale(0.9); opacity: 0.9; }
        }
        [x-cloak] { display: none !important; }
    </style>

    <div x-data="{ showCreateModal: false }" class="bg-slate-100 min-h-screen py-4">
        <div class="max-w-[1500px] mx-auto px-4 space-y-4">

            {{-- Flash Message --}}
            @if(session('success'))
                <div class="p-3.5 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg text-xs font-bold text-emerald-800 shadow-xs flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            {{-- 1. UNIFIED TELEMETRY RIBBON --}}
            <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs px-5 py-3 flex flex-wrap items-center justify-between gap-4">
                {{-- Left: Platform Title & Time --}}
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#1b1f3b] text-sky-400 flex items-center justify-center text-sm font-bold shadow-xs">
                        <i class="fa-solid fa-radar"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-extrabold text-slate-900 tracking-tight">Freight Dispatch Workbench</h2>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                LIVE TELEMETRY
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-500 font-mono flex items-center gap-2 mt-0.5">
                            <span><i class="fa-regular fa-clock mr-1"></i> <strong id="utc-time-hud">--:--:-- UTC</strong></span>
                            <span class="text-slate-300">•</span>
                            <span>Role: <strong class="text-slate-700 uppercase font-sans font-bold">{{ Auth::user()->role }}</strong></span>
                        </div>
                    </div>
                </div>

                {{-- Right: Compact Industrial Metric Chips --}}
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    {{-- Total --}}
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200">
                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                        <span class="text-slate-500 font-medium">Manifest:</span>
                        <strong class="text-slate-900 font-mono">{{ $totalShipments }}</strong>
                    </div>

                    {{-- In Transit --}}
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-900">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        <span class="font-medium">In Transit:</span>
                        <strong class="font-mono">{{ $inTransitCount }}</strong>
                    </div>

                    {{-- Delivered --}}
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-900">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="font-medium">Delivered:</span>
                        <strong class="font-mono">{{ $deliveredCount }}</strong>
                    </div>

                    {{-- Delayed --}}
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-900">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                        <span class="font-medium">Delayed:</span>
                        <strong class="font-mono">{{ $delayedCount }}</strong>
                    </div>

                    {{-- Exceptions --}}
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-50 border border-red-200 text-red-900">
                        <i class="fa-solid fa-triangle-exclamation text-red-600 text-xs"></i>
                        <span class="font-medium">Exceptions:</span>
                        <strong class="font-mono">{{ $openExceptionsCount }}</strong>
                    </div>
                </div>
            </div>

            {{-- 2. ASYMMETRICAL WORKBENCH: LEFT DISPATCH FEED (40%) + RIGHT RADAR WORKSPACE (60%) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">

                {{-- PANE KIRI: DISPATCH QUEUE & ACTIVE VOYAGE CARDS (5 Kolom / 40%) --}}
                <div class="lg:col-span-5 bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col h-[700px] overflow-hidden">
                    
                    {{-- Header Feed & Filter Bar --}}
                    <div class="p-3.5 border-b border-slate-200 bg-slate-50 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-sky-600 text-sm"></i>
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Active Voyage Queue</h3>
                            </div>
                            <div class="flex items-center gap-2">
                                {{-- Tombol Tambah Shipment (Admin & Operator Only) --}}
                                @if(!Auth::user()->isCustomer())
                                    <button @click="showCreateModal = true" class="px-2.5 py-1 bg-sky-600 hover:bg-sky-500 text-white rounded-md text-[11px] font-bold shadow-xs flex items-center gap-1.5 transition">
                                        <i class="fa-solid fa-plus text-[10px]"></i>
                                        <span>Buat Kargo</span>
                                    </button>
                                @endif
                                <span class="text-[11px] text-slate-500 font-mono">{{ $shipments->total() }} Records</span>
                            </div>
                        </div>

                        {{-- Search & Status Chips --}}
                        <form method="GET" action="{{ route('dashboard') }}" class="space-y-2">
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Ref / Komoditas kargo..." class="w-full pl-8 pr-3 py-1.5 text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500 bg-white">
                            </div>

                            <div class="flex flex-wrap items-center gap-1.5 text-[11px]">
                                <a href="{{ route('dashboard') }}" class="px-2.5 py-1 rounded-md font-bold transition {{ !request('status') ? 'bg-[#1b1f3b] text-white' : 'bg-slate-200/80 text-slate-700 hover:bg-slate-300' }}">
                                    Semua
                                </a>
                                <a href="{{ route('dashboard', ['status' => 'IN_TRANSIT']) }}" class="px-2.5 py-1 rounded-md font-bold transition {{ request('status') === 'IN_TRANSIT' ? 'bg-amber-500 text-white' : 'bg-amber-100 text-amber-800 hover:bg-amber-200' }}">
                                    In Transit
                                </a>
                                <a href="{{ route('dashboard', ['delay_status' => 'DELAYED']) }}" class="px-2.5 py-1 rounded-md font-bold transition {{ request('delay_status') === 'DELAYED' ? 'bg-rose-500 text-white' : 'bg-rose-100 text-rose-800 hover:bg-rose-200' }}">
                                    Delayed
                                </a>
                                <a href="{{ route('dashboard', ['status' => 'DELIVERED']) }}" class="px-2.5 py-1 rounded-md font-bold transition {{ request('status') === 'DELIVERED' ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' }}">
                                    Delivered
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- Scrollable List of Voyage Cards --}}
                    <div class="flex-1 overflow-y-auto custom-scroll p-3 space-y-2.5">
                        @forelse($shipments as $s)
                            @php
                                $statusBadge = [
                                    'CREATED'    => 'bg-slate-200 text-slate-700',
                                    'BOOKED'     => 'bg-sky-500 text-white',
                                    'PICKED_UP'  => 'bg-purple-600 text-white',
                                    'IN_TRANSIT' => 'bg-amber-500 text-white animate-pulse',
                                    'DELIVERED'  => 'bg-emerald-500 text-white',
                                ];
                            @endphp
                            <div class="p-3.5 rounded-xl border border-slate-200 hover:border-sky-400 bg-white hover:bg-sky-50/30 transition shadow-xs group cursor-pointer" onclick="focusOnShipment({{ $s->id }}, {{ $s->latestEvent?->latitude ?? $s->origin->latitude }}, {{ $s->latestEvent?->longitude ?? $s->origin->longitude }})">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-extrabold text-sm text-slate-900 group-hover:text-sky-600 transition">{{ $s->shipment_number }}</span>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded shadow-2xs {{ $statusBadge[$s->current_status] ?? 'bg-slate-200 text-slate-800' }}">
                                                {{ $s->current_status }}
                                            </span>
                                        </div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">
                                            <i class="fa-solid fa-ship text-slate-400 mr-1"></i> {{ $s->carrier->name }} ({{ $s->carrier->code }})
                                        </div>
                                    </div>

                                    {{-- SLA / Delay Pill --}}
                                    <div>
                                        @if($s->delay_status === 'DELAYED')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200 flex items-center gap-1">
                                                <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> +{{ $s->delay_hours }}h
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                ON TIME
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Route Stepper Line --}}
                                <div class="mt-3 p-2 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                                    <div class="text-left">
                                        <div class="font-bold text-slate-800">{{ $s->origin->city }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $s->origin->code }}</div>
                                    </div>
                                    <div class="flex-1 px-3 flex flex-col items-center">
                                        <div class="w-full flex items-center">
                                            <div class="w-2 h-2 rounded-full bg-sky-500"></div>
                                            <div class="flex-1 border-t-2 border-dashed border-sky-300"></div>
                                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                        </div>
                                        <span class="text-[9px] text-slate-400 mt-0.5 font-mono">SEA CORRIDOR</span>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-slate-800">{{ $s->destination->city }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $s->destination->code }}</div>
                                    </div>
                                </div>

                                {{-- Cargo & Specs Footer --}}
                                <div class="mt-2.5 flex items-center justify-between text-[11px] text-slate-500 pt-2 border-t border-slate-100">
                                    <span class="truncate max-w-[200px]" title="{{ $s->cargo_type }}"><i class="fa-solid fa-box-open mr-1 text-slate-400"></i> {{ $s->cargo_type }}</span>
                                    <a href="{{ route('shipments.show', $s) }}" class="font-bold text-sky-600 hover:text-sky-800 flex items-center gap-1">
                                        <span>Buka Detail</span>
                                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-inbox text-3xl mb-2 text-slate-300"></i>
                                <p>Tidak ada manifest pengiriman yang cocok.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination Footer --}}
                    <div class="p-2.5 border-t border-slate-200 bg-slate-50 text-xs">
                        {{ $shipments->links() }}
                    </div>
                </div>

                {{-- PANE KANAN: INTERACTIVE RADAR STAGE + FLOATING HUD (7 Kolom / 60%) --}}
                <div class="lg:col-span-7 space-y-4">
                    
                    {{-- Radar Map Container with Floating HUD Overlays --}}
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden relative">
                        {{-- Map Header Bar --}}
                        <div class="p-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-sky-500 animate-ping"></div>
                                <span class="font-bold text-slate-800 uppercase tracking-wider">Live Ocean Radar & Fleet Trajectory</span>
                            </div>
                            <div class="flex items-center gap-3 text-slate-500">
                                <span><i class="fa-solid fa-location-crosshairs text-sky-600 mr-1"></i> Multi-Point GIS</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-sky-100 text-sky-800 font-bold">OpenStreetMap</span>
                            </div>
                        </div>

                        {{-- Leaflet Map Canvas (Tinggi 460px Lega & Interaktif!) --}}
                        <div id="radar-canvas" class="w-full h-[460px] bg-slate-900 relative"></div>

                        {{-- Floating Glass HUD: Performance Quick Metric --}}
                        <div class="absolute bottom-4 left-4 z-[400] bg-white/95 backdrop-blur-md p-3 rounded-xl border border-slate-200 shadow-lg max-w-xs text-xs space-y-1.5">
                            <div class="flex items-center justify-between gap-3">
                                <strong class="text-slate-900 font-bold flex items-center gap-1.5">
                                    <i class="fa-solid fa-shield-heart text-emerald-600"></i> SLA Reliability
                                </strong>
                                <span class="px-2 py-0.5 rounded font-mono font-bold bg-emerald-100 text-emerald-800 text-[10px]">{{ $onTimeRate }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $onTimeRate }}%;"></div>
                            </div>
                            <p class="text-[10px] text-slate-500">{{ $totalShipments - $delayedCount }} dari {{ $totalShipments }} kargo bergerak sesuai estimasi.</p>
                        </div>
                    </div>

                    {{-- Chart.js Breakdown Widget --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Chart.js Bar Chart --}}
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fa-solid fa-chart-column text-indigo-600"></i>
                                    <span>Fleet Volume by Status</span>
                                </h4>
                                <span class="text-[10px] font-mono text-slate-400">Chart.js</span>
                            </div>
                            <div class="h-36 relative">
                                <canvas id="fleetStatusBarChart"></canvas>
                            </div>
                        </div>

                        {{-- Active Operational Incidents / Exceptions --}}
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-xs font-bold text-rose-800 uppercase tracking-wider flex items-center gap-1.5">
                                        <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                                        <span>Incident Dispatch Priority</span>
                                    </h4>
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-rose-100 text-rose-700">Priority 1</span>
                                </div>
                                <div class="space-y-2 text-xs">
                                    <div class="p-2 rounded-lg bg-rose-50 border border-rose-200 text-rose-900">
                                        <div class="font-bold flex items-center justify-between">
                                            <span>Shipment #CTG-2026-0081</span>
                                            <span class="text-[10px] bg-rose-200 px-1.5 py-0.2 rounded font-bold">WEATHER</span>
                                        </div>
                                        <p class="text-[11px] text-rose-700 mt-1">Severe monsoon squall line in Indian Ocean. Speed capped at 14 knots.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 text-right">
                                <a href="{{ route('shipments.show', 1) }}" class="text-[11px] font-bold text-rose-700 hover:text-rose-900">Tinjau Exception &rarr;</a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        {{-- MODAL TAMBAH SHIPMENT BARU (Admin & Operator Only) --}}
        @if(!Auth::user()->isCustomer())
            <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-[999] overflow-y-auto" style="display: none;">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="showCreateModal = false"></div>

                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                        
                        {{-- Modal Header --}}
                        <div class="bg-[#1b1f3b] px-6 py-4 flex items-center justify-between text-white border-b border-[#292f54]">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-sky-500 flex items-center justify-center text-white">
                                    <i class="fa-solid fa-plus text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-extrabold text-white">Daftarkan Manifest Kargo Baru</h3>
                                    <p class="text-[11px] text-slate-300 font-mono">STATUS AWAL: CREATED (Registrasi Pelayaran)</p>
                                </div>
                            </div>
                            <button @click="showCreateModal = false" class="text-slate-400 hover:text-white text-lg">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        {{-- Form Input Shipment --}}
                        <form method="POST" action="{{ route('shipments.store') }}" class="p-6 space-y-4 text-xs font-sans">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Pilih Customer --}}
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Pelanggan / Pengirim (*Customer*)</label>
                                    <select name="customer_id" required class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500">
                                        <option value="">-- Pilih Customer --</option>
                                        @foreach($customers as $cust)
                                            <option value="{{ $cust->id }}">{{ $cust->name }} ({{ $cust->email }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Pilih Carrier --}}
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Armada Logistik (*Carrier*)</label>
                                    <select name="carrier_id" required class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500">
                                        <option value="">-- Pilih Maskapai / Kapal --</option>
                                        @foreach($carriers as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Origin Port --}}
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Pelabuhan Asal (*Origin*)</label>
                                    <select name="origin_id" required class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500">
                                        <option value="">-- Pilih Pelabuhan Asal --</option>
                                        @foreach($locations as $loc)
                                            <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->city }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Destination Port --}}
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Pelabuhan Tujuan (*Destination*)</label>
                                    <select name="destination_id" required class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500">
                                        <option value="">-- Pilih Pelabuhan Tujuan --</option>
                                        @foreach($locations as $loc)
                                            <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->city }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Cargo Type --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Nama Muatan / Komoditas Kargo</label>
                                <input type="text" name="cargo_type" required placeholder="Misal: Kopi Robusta Lampung, Pupuk Organik, Biji Kakao..." class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Berat Muatan (KG)</label>
                                    <input type="number" step="0.01" name="weight_kg" required placeholder="Contoh: 18500" class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Volume Kargo (CBM)</label>
                                    <input type="number" step="0.01" name="volume_cbm" required placeholder="Contoh: 45.5" class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Rencana Keberangkatan</label>
                                    <input type="datetime-local" name="expected_departure" required value="{{ date('Y-m-d\TH:i') }}" class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Estimasi Tiba di Tujuan</label>
                                    <input type="datetime-local" name="expected_arrival" required value="{{ date('Y-m-d\TH:i', strtotime('+14 days')) }}" class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500">
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" rows="2" placeholder="Catatan segel kontainer, pendingin reefer, dll..." class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500"></textarea>
                            </div>

                            {{-- Footer Buttons --}}
                            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-2.5">
                                <button type="button" @click="showCreateModal = false" class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition">
                                    Batal
                                </button>
                                <button type="submit" class="px-5 py-2 rounded-lg bg-[#1b1f3b] hover:bg-sky-600 text-white font-bold shadow-md transition flex items-center gap-2">
                                    <i class="fa-solid fa-check"></i> Simpan & Daftarkan Kargo
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- Script Jam UTC, Leaflet Radar, dan Chart.js --}}
    <script>
        let radarMap;
        const markersMap = {};

        // 1. Live UTC Clock
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

        // 2. Interactive Leaflet Radar Workbench (Slide 09)
        document.addEventListener('DOMContentLoaded', function () {
            const mapData = @json($mapShipments);
            const mapEl = document.getElementById('radar-canvas');

            if (mapEl) {
                radarMap = L.map('radar-canvas', {
                    zoomControl: true,
                    attributionControl: false
                }).setView([10.0, 75.0], 3);

                // OpenStreetMap Tile Layer Resmi (100% Free & Tanpa Watermark "API KEY REQUIRED")
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(radarMap);

                const bounds = [];

                mapData.forEach(item => {
                    const origin = [item.origin.lat, item.origin.lng];
                    const dest = [item.destination.lat, item.destination.lng];
                    const current = [item.current_location.lat, item.current_location.lng];

                    bounds.push(origin, dest, current);

                    const isDelayed = item.delay_status === 'DELAYED';
                    const routeColor = isDelayed ? '#f43f5e' : (item.status === 'DELIVERED' ? '#10b981' : '#0284c7');

                    // Garis Lintasan Laut (Polyline)
                    L.polyline([origin, current, dest], {
                        color: routeColor,
                        weight: 2.5,
                        dashArray: item.status === 'DELIVERED' ? null : '6, 8',
                        opacity: 0.8
                    }).addTo(radarMap);

                    // Marker Origin
                    L.circleMarker(origin, {
                        radius: 5,
                        color: '#0369a1',
                        fillColor: '#ffffff',
                        fillOpacity: 1,
                        weight: 2
                    }).addTo(radarMap).bindPopup(`<b>Origin:</b> ${item.origin.name}<br><b>Shipment:</b> ${item.number}`);

                    // Marker Destination
                    L.circleMarker(dest, {
                        radius: 5,
                        color: '#059669',
                        fillColor: '#10b981',
                        fillOpacity: 1,
                        weight: 2
                    }).addTo(radarMap).bindPopup(`<b>Destination:</b> ${item.destination.name}<br><b>Shipment:</b> ${item.number}`);

                    // Custom Beacon Marker untuk Kapal / Titik Terkini
                    const customBeacon = L.divIcon({
                        className: 'custom-beacon-icon',
                        html: `<div class="beacon-pulse" style="background-color: ${isDelayed ? '#e11d48' : '#0284c7'};"></div>`,
                        iconSize: [12, 12],
                        iconAnchor: [6, 6]
                    });

                    const beaconMarker = L.marker(current, { icon: customBeacon }).addTo(radarMap);
                    beaconMarker.bindPopup(`
                        <div style="font-family: sans-serif; font-size: 11px; line-height: 1.4;">
                            <strong style="color: #0f172a; font-size: 12px;">${item.number}</strong><br>
                            <span style="color: #64748b;">Carrier: ${item.carrier}</span><br>
                            <span style="color: #64748b;">Cargo: ${item.cargo}</span><br>
                            <div style="margin-top: 4px; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 10px; display: inline-block; background: ${isDelayed ? '#ffe4e6' : '#e0f2fe'}; color: ${isDelayed ? '#be123c' : '#0369a1'};">
                                ${item.status} ${isDelayed ? '• DELAYED' : ''}
                            </div>
                        </div>
                    `);

                    markersMap[item.id] = { marker: beaconMarker, latLng: current };

                    // Buka popup jika ada kapal delayed (prioritas operasional)
                    if (isDelayed) {
                        beaconMarker.openPopup();
                    }
                });

                if (bounds.length > 0) {
                    radarMap.fitBounds(bounds, { padding: [30, 30] });
                }
            }

            // 3. Chart.js Bar Chart
            const chartCanvas = document.getElementById('fleetStatusBarChart');
            if (chartCanvas) {
                new Chart(chartCanvas, {
                    type: 'bar',
                    data: {
                        labels: ['Booked', 'In Transit', 'Delivered', 'Delayed'],
                        datasets: [{
                            data: [{{ $totalShipments - $inTransitCount - $deliveredCount }}, {{ $inTransitCount }}, {{ $deliveredCount }}, {{ $delayedCount }}],
                            backgroundColor: ['#3b82f6', '#f59e0b', '#10b981', '#f43f5e'],
                            borderRadius: 6,
                            barThickness: 24
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } } },
                            x: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' } } }
                        }
                    }
                });
            }
        });

        // Fungsi klik card kargo langsung geser peta ke kapal tersebut (Interaktif!)
        function focusOnShipment(id, lat, lng) {
            if (radarMap && lat && lng) {
                radarMap.flyTo([lat, lng], 6, { duration: 1.2 });
                if (markersMap[id]) {
                    setTimeout(() => {
                        markersMap[id].marker.openPopup();
                    }, 1200);
                }
            }
        }
    </script>
</x-app-layout>
