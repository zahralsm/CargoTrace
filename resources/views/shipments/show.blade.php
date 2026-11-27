<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <a href="{{ route('dashboard') }}" class="text-xs font-bold text-sky-600 hover:text-sky-800 flex items-center gap-1.5 mb-1.5 transition">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                </a>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                        {{ $shipment->shipment_number }}
                    </h2>
                    @php
                        $pillStyles = [
                            'CREATED'    => 'bg-slate-200 text-slate-800',
                            'BOOKED'     => 'bg-sky-500 text-white',
                            'PICKED_UP'  => 'bg-purple-600 text-white',
                            'IN_TRANSIT' => 'bg-amber-500 text-white animate-pulse',
                            'DELIVERED'  => 'bg-emerald-500 text-white',
                        ];
                        $statusLabels = [
                            'CREATED'    => 'DIBUAT',
                            'BOOKED'     => 'DIPESAN',
                            'PICKED_UP'  => 'DIMUAT',
                            'IN_TRANSIT' => 'BERLAYAR',
                            'DELIVERED'  => 'TIBA DI TUJUAN',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-md text-xs font-bold shadow-xs {{ $pillStyles[$shipment->current_status] ?? 'bg-slate-200 text-slate-800' }}">
                        {{ $statusLabels[$shipment->current_status] ?? $shipment->current_status }}
                    </span>
                </div>
            </div>

            {{-- Indikator Status Keterlambatan (SLA) --}}
            <div>
                @if($shipment->delay_status === 'DELAYED')
                    <div class="px-3.5 py-2 rounded-xl bg-rose-50 border border-rose-300 text-rose-800 text-xs font-bold flex items-center gap-2 shadow-xs">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                        <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                        <span>PERINGATAN KETERLAMBATAN: +{{ $shipment->delay_hours }} Jam (Tingkat: {{ $shipment->delay_severity === 'LOW' ? 'Rendah' : ($shipment->delay_severity === 'MEDIUM' ? 'Sedang' : 'Tinggi') }})</span>
                    </div>
                @else
                    <div class="px-3.5 py-2 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs font-bold flex items-center gap-2 shadow-xs">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>JADWAL TEPAT WAKTU TERKONFIRMASI</span>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Notifikasi Sukses / Gagal --}}
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg text-xs font-bold text-emerald-800 shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if($errors->has('tracking_error'))
                <div class="p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-lg text-xs font-bold text-rose-800 shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 text-sm"></i>
                    <span>{{ $errors->first('tracking_error') }}</span>
                </div>
            @endif

            {{-- 1. STEPPER TAHAPAN SIKLUS HIDUP KARGO (Slide 06) --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs">
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-route text-sky-600"></i>
                    <span>Tahapan Siklus Hidup Pengiriman Kargo</span>
                </div>
                @php
                    $steps = ['CREATED', 'BOOKED', 'PICKED_UP', 'IN_TRANSIT', 'DELIVERED'];
                    $stepNames = [
                        'CREATED'    => '1. DIBUAT',
                        'BOOKED'     => '2. DIPESAN',
                        'PICKED_UP'  => '3. DIMUAT',
                        'IN_TRANSIT' => '4. BERLAYAR',
                        'DELIVERED'  => '5. SELESAI',
                    ];
                    $currentIndex = array_search($shipment->current_status, $steps);
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-center">
                    @foreach($steps as $idx => $step)
                        @php
                            $isPassed = $idx <= $currentIndex;
                            $isCurrent = $idx === $currentIndex;
                        @endphp
                        <div class="p-3.5 rounded-xl border transition {{ $isCurrent ? 'bg-sky-500 text-white border-sky-600 shadow-md ring-2 ring-sky-300' : ($isPassed ? 'bg-emerald-50 border-emerald-300 text-emerald-800' : 'bg-slate-50 border-slate-200 text-slate-400') }}">
                            <div class="text-[10px] {{ $isCurrent ? 'text-sky-100' : ($isPassed ? 'text-emerald-600' : 'text-slate-400') }}">Tahap 0{{ $idx + 1 }}</div>
                            <div class="text-xs font-extrabold mt-1 tracking-tight">
                                {{ $stepNames[$step] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 2. KONSOL PENGENDALI DISPATCH (MODE OPERATOR - HERO COMMAND DECK) --}}
            {{-- Diposisikan secara prestisius dan luas tepat di atas seluruh data operasional --}}
            @if(!Auth::user()->isCustomer())
                <div class="bg-[#151932] text-white p-6 sm:p-7 rounded-2xl border border-[#2b335c] shadow-xl space-y-5">
                    {{-- Header Konsol Dispatch --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-[#2b335c] gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center text-lg font-bold shadow-xs">
                                <i class="fa-solid fa-sliders"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2.5">
                                    <h3 class="text-sm sm:text-base font-black uppercase tracking-wider text-white">Konsol Pengendali Dispatch</h3>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-sky-500/20 text-sky-300 border border-sky-400/30">MODE OPERATOR</span>
                                </div>
                                <p class="text-xs text-slate-300 mt-0.5">Pusat Kendali Dispatcher • Input Pembaruan Titik Checkpoint & Status Kargo Real-Time</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-slate-400 font-medium">Status Kargo Saat Ini:</span>
                            <span class="px-3 py-1 rounded-lg text-xs font-black shadow-xs {{ $pillStyles[$shipment->current_status] ?? 'bg-slate-200 text-slate-800' }}">
                                {{ $statusLabels[$shipment->current_status] ?? $shipment->current_status }}
                            </span>
                        </div>
                    </div>

                    {{-- Formulir Pembaruan Dispatch --}}
                    <form method="POST" action="{{ route('shipments.tracking.store', $shipment) }}" class="space-y-4">
                        @csrf

                        {{-- Baris 1: Grid Parameter Utama (4 Kolom Luas) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            {{-- Field 1: Status Baru --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-200 mb-1.5 uppercase tracking-wider">
                                    <i class="fa-solid fa-flag text-sky-400 mr-1"></i> Peristiwa / Status Baru
                                </label>
                                <select name="event_type" required class="w-full text-xs sm:text-sm py-2.5 px-3.5 rounded-xl bg-[#222748] border border-[#373e6d] text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                                    <option value="BOOKED" {{ $shipment->current_status === 'CREATED' ? 'selected' : '' }}>BOOKED (Pemesanan Dikonfirmasi)</option>
                                    <option value="PICKED_UP" {{ $shipment->current_status === 'BOOKED' ? 'selected' : '' }}>PICKED_UP (Kontainer Dimuat di Dermaga)</option>
                                    <option value="IN_TRANSIT" {{ $shipment->current_status === 'PICKED_UP' ? 'selected' : '' }}>IN_TRANSIT (Sedang Berlayar di Samudra)</option>
                                    <option value="DEPARTED">DEPARTED (Kapal Bertolak dari Pelabuhan)</option>
                                    <option value="CUSTOMS_HOLD">CUSTOMS_HOLD (Tertahan Bea Cukai)</option>
                                    <option value="DELIVERED" {{ $shipment->current_status === 'IN_TRANSIT' ? 'selected' : '' }}>DELIVERED (Kargo Tiba di Tujuan)</option>
                                </select>
                            </div>

                            {{-- Field 2: Pelabuhan / Lokasi --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-200 mb-1.5 uppercase tracking-wider">
                                    <i class="fa-solid fa-anchor text-sky-400 mr-1"></i> Pelabuhan / Titik Singgah
                                </label>
                                <select name="location_id" class="w-full text-xs sm:text-sm py-2.5 px-3.5 rounded-xl bg-[#222748] border border-[#373e6d] text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
                                    <option value="">-- Koordinat Bebas / Laut Lepas --</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->city }})</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Field 3: Koordinat GPS (Dual Input Lat & Lng) --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-200 mb-1.5 uppercase tracking-wider">
                                    <i class="fa-solid fa-location-crosshairs text-sky-400 mr-1"></i> Koordinat GPS (WGS-84)
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" name="latitude" placeholder="Lat: -6.103" class="w-full text-xs sm:text-sm py-2.5 px-3 rounded-xl bg-[#222748] border border-[#373e6d] text-white placeholder-slate-400 focus:ring-2 focus:ring-sky-500 transition">
                                    <input type="text" name="longitude" placeholder="Lng: 106.882" class="w-full text-xs sm:text-sm py-2.5 px-3 rounded-xl bg-[#222748] border border-[#373e6d] text-white placeholder-slate-400 focus:ring-2 focus:ring-sky-500 transition">
                                </div>
                            </div>

                            {{-- Field 4: Waktu Kejadian UTC --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-200 mb-1.5 uppercase tracking-wider">
                                    <i class="fa-regular fa-clock text-sky-400 mr-1"></i> Waktu Kejadian (UTC)
                                </label>
                                <input type="datetime-local" name="event_time" required value="{{ date('Y-m-d\TH:i') }}" class="w-full text-xs sm:text-sm py-2.5 px-3.5 rounded-xl bg-[#222748] border border-[#373e6d] text-white focus:ring-2 focus:ring-sky-500 transition">
                            </div>
                        </div>

                        {{-- Baris 2: Catatan Operasional & Tombol Aksi Simpan Checkpoint --}}
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end pt-1">
                            <div class="lg:col-span-9">
                                <label class="block text-xs font-bold text-slate-200 mb-1.5 uppercase tracking-wider">
                                    <i class="fa-regular fa-clipboard text-sky-400 mr-1"></i> Catatan Operasional / Log Despatch
                                </label>
                                <input type="text" name="description" required placeholder="Tuliskan catatan kondisi muatan, nomor segel kontainer, kecepatan kapal, atau informasi navigasi..." class="w-full text-xs sm:text-sm py-2.5 px-3.5 rounded-xl bg-[#222748] border border-[#373e6d] text-white placeholder-slate-400 focus:ring-2 focus:ring-sky-500 transition">
                            </div>

                            <div class="lg:col-span-3">
                                <button type="submit" class="w-full py-2.5 px-4 bg-sky-600 hover:bg-sky-500 text-white font-black rounded-xl text-xs sm:text-sm shadow-md hover:shadow-sky-500/30 transition flex items-center justify-center gap-2 cursor-pointer h-[42px]">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                    <span>Catat Titik Checkpoint</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            {{-- 3. BARIS 1: PETA RADAR LINTASAN (KIRI) vs TELEMETRI CUACA ASAL & TUJUAN (KANAN - PASS DENGAN PETA) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">

                {{-- KOLOM KIRI (7 Kolom): PETA RADAR LINTASAN PELAYARAN --}}
                <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden flex flex-col">
                    <div class="p-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-compass text-sky-600"></i>
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Radar Lintasan Rute Kargo</h3>
                        </div>
                        <span class="text-xs text-slate-500">Lintasan pelayaran laut dan titik koordinat singgah</span>
                    </div>
                    <div id="shipment-map" class="w-full bg-slate-900 flex-1 min-h-[320px] h-[340px]"></div>
                </div>

                {{-- KOLOM KANAN (5 Kolom): DUA KARTU TELEMETRI CUACA (Layout Pas Sejajar dengan Peta) --}}
                <div class="lg:col-span-5 flex flex-col justify-between gap-4">

                    {{-- Telemetri Cuaca Langsung Pelabuhan Asal --}}
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/90 shadow-xs flex-1 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $originWeather['status'] === 'ONLINE' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pelabuhan Asal • Cuaca Langsung</span>
                            </div>
                            <h4 class="text-base font-extrabold text-slate-900 mt-1">{{ $shipment->origin->name }} ({{ $shipment->origin->city }})</h4>
                            <p class="text-xs text-slate-500 mt-0.5"><i class="fa-solid fa-cloud-sun text-amber-500 mr-1"></i> {{ $originWeather['condition'] }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-black text-slate-900">
                                {{ $originWeather['temperature'] ? $originWeather['temperature'] . '°C' : 'N/A' }}
                            </div>
                            <div class="text-[11px] text-slate-500 mt-0.5">
                                Angin: {{ $originWeather['wind_speed'] ?? '-' }} km/jam<br>Kelembaban: {{ $originWeather['humidity'] ?? '-' }}%
                            </div>
                        </div>
                    </div>

                    {{-- Telemetri Cuaca Langsung Pelabuhan Tujuan --}}
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/90 shadow-xs flex-1 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $destinationWeather['status'] === 'ONLINE' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pelabuhan Tujuan • Cuaca Langsung</span>
                            </div>
                            <h4 class="text-base font-extrabold text-slate-900 mt-1">{{ $shipment->destination->name }} ({{ $shipment->destination->city }})</h4>
                            <p class="text-xs text-slate-500 mt-0.5"><i class="fa-solid fa-cloud-sun text-amber-500 mr-1"></i> {{ $destinationWeather['condition'] }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-black text-slate-900">
                                {{ $destinationWeather['temperature'] ? $destinationWeather['temperature'] . '°C' : 'N/A' }}
                            </div>
                            <div class="text-[11px] text-slate-500 mt-0.5">
                                Angin: {{ $destinationWeather['wind_speed'] ?? '-' }} km/jam<br>Kelembaban: {{ $destinationWeather['humidity'] ?? '-' }}%
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- 4. BARIS 2: RIWAYAT PERJALANAN (KIRI) vs SPESIFIKASI MANIFEST KARGO (KANAN - SEJAJAR RIWAYAT) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- KOLOM KIRI (7 Kolom): TIMELINE KRONOLOGIS & GANGGUAN OPERASIONAL --}}
                <div class="lg:col-span-7 space-y-6">

                    {{-- TIMELINE KRONOLOGIS (Slide 07) --}}
                    <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-200/90 shadow-xs">
                        <div class="flex items-center justify-between mb-5 border-b border-slate-100 pb-3">
                            <h3 class="text-sm sm:text-base font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <i class="fa-solid fa-timeline text-sky-600"></i>
                                <span>Riwayat Perjalanan (Timeline Kronologis)</span>
                            </h3>
                            <span class="text-xs text-slate-500 font-semibold">{{ $shipment->trackingEvents->count() }} Titik Pemeriksaan</span>
                        </div>

                        <div class="relative border-l-2 border-slate-200 ml-4 space-y-6">
                            @forelse($shipment->trackingEvents as $event)
                                <div class="relative pl-6">
                                    <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-white border-4 border-sky-600"></div>

                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                        <span class="text-xs font-black text-slate-900 tracking-tight">{{ $event->event_type }}</span>
                                        <span class="text-[11px] text-slate-500">{{ $event->event_time->format('d M Y, H:i') }} UTC</span>
                                    </div>
                                    <div class="text-xs font-semibold text-slate-700 mt-0.5">
                                        {{ $event->location_name ?? $event->location?->name ?? 'Titik Koordinat Laut' }}
                                        @if($event->latitude && $event->longitude)
                                            <span class="text-[10px] text-slate-400">({{ $event->latitude }}, {{ $event->longitude }})</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-600 mt-1.5 leading-relaxed bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                                        {{ $event->description }}
                                    </p>
                                    <div class="text-[10px] text-slate-400 mt-1">Dicatat oleh: <strong class="text-slate-600">{{ $event->creator->name }}</strong></div>
                                </div>
                            @empty
                                <div class="pl-6 text-xs text-slate-500">Belum ada titik pelacakan yang dicatat.</div>
                            @endforelse
                        </div>

                        {{-- DAFTAR KENDALA / GANGGUAN OPERASIONAL (Slide 08) --}}
                        @if($shipment->exceptions->count() > 0)
                            <div class="mt-8 pt-6 border-t border-slate-200">
                                <h4 class="text-xs font-bold text-rose-800 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                                    <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                                    <span>Gangguan Operasional (Shipment Exceptions)</span>
                                </h4>
                                <div class="space-y-3">
                                    @foreach($shipment->exceptions as $exc)
                                        <div class="p-4 rounded-xl border {{ $exc->status === 'RESOLVED' ? 'bg-slate-50 border-slate-200' : 'bg-rose-50 border-rose-300' }}">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold {{ $exc->status === 'RESOLVED' ? 'bg-slate-200 text-slate-700' : 'bg-rose-600 text-white' }}">
                                                        {{ $exc->exception_type }}
                                                    </span>
                                                    <span class="text-[10px] font-bold text-rose-700">Tingkat: {{ $exc->severity === 'LOW' ? 'Rendah' : ($exc->severity === 'MEDIUM' ? 'Sedang' : 'Tinggi') }}</span>
                                                </div>
                                                <span class="text-[10px] font-bold {{ $exc->status === 'RESOLVED' ? 'text-emerald-700' : 'text-rose-700 animate-pulse' }}">
                                                    STATUS: {{ $exc->status === 'RESOLVED' ? 'TERSELESAIKAN' : 'MENUNGGU TINDAKAN' }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-700 mt-2">{{ $exc->description }}</p>
                                            
                                            @if($exc->status === 'RESOLVED')
                                                <div class="mt-2.5 text-[11px] text-emerald-800 bg-emerald-50 p-2.5 rounded-lg border border-emerald-200">
                                                    <strong>Terselesaikan:</strong> {{ $exc->resolution_notes }}
                                                </div>
                                            @elseif(!Auth::user()->isCustomer())
                                                {{-- Form Selesaikan Kendala (Operator/Admin Saja) --}}
                                                <form method="POST" action="{{ route('exceptions.resolve', $exc) }}" class="mt-3 flex gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="text" name="resolution_notes" required placeholder="Tuliskan catatan penyelesaian gangguan..." class="text-xs rounded-xl border-slate-300 flex-1 py-2 px-3">
                                                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition">
                                                        Selesaikan Kendala
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                </div>

                {{-- KOLOM KANAN (5 Kolom): SPESIFIKASI MANIFEST KARGO (Pas Berdampingan di Samping Riwayat) --}}
                <div class="lg:col-span-5">
                    <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-200/90 shadow-xs space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h4 class="text-xs sm:text-sm font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                <i class="fa-solid fa-clipboard-list text-sky-600"></i>
                                <span>Spesifikasi Manifest Kargo</span>
                            </h4>
                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600">GIS VERIFIED</span>
                        </div>
                        <div class="divide-y divide-slate-100 text-xs sm:text-sm">
                            <div class="py-2.5 flex items-center justify-between">
                                <span class="text-slate-500 font-medium">Komoditas:</span>
                                <span class="font-extrabold text-slate-900">{{ $shipment->cargo_type }}</span>
                            </div>
                            <div class="py-2.5 flex items-center justify-between">
                                <span class="text-slate-500 font-medium">Berat Bersih:</span>
                                <span class="font-extrabold text-slate-900">{{ number_format($shipment->weight_kg, 2) }} KG</span>
                            </div>
                            <div class="py-2.5 flex items-center justify-between">
                                <span class="text-slate-500 font-medium">Volume Muatan:</span>
                                <span class="font-extrabold text-slate-900">{{ $shipment->volume_cbm }} CBM</span>
                            </div>
                            <div class="py-2.5 flex items-center justify-between">
                                <span class="text-slate-500 font-medium">Maskapai Pelayaran:</span>
                                <span class="font-extrabold text-slate-900">{{ $shipment->carrier->name }} ({{ $shipment->carrier->code }})</span>
                            </div>
                            <div class="py-2.5 flex items-center justify-between">
                                <span class="text-slate-500 font-medium">Pemilik / Pengirim:</span>
                                <span class="font-extrabold text-slate-900">{{ $shipment->customer->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Script Peta Khusus Shipment Ini --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const origin = [{{ $shipment->origin->latitude }}, {{ $shipment->origin->longitude }}];
            const dest = [{{ $shipment->destination->latitude }}, {{ $shipment->destination->longitude }}];

            const map = L.map('shipment-map').setView(origin, 4);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            const waypoints = [origin];

            // Titik Pelabuhan Asal
            L.circleMarker(origin, { radius: 7, color: '#0284c7', fillColor: '#38bdf8', fillOpacity: 1 })
                .addTo(map).bindPopup("<b>Pelabuhan Asal:</b> {{ $shipment->origin->name }}");

            // Titik Tracking Events
            @foreach($shipment->trackingEvents as $ev)
                @if($ev->latitude && $ev->longitude)
                    waypoints.push([{{ $ev->latitude }}, {{ $ev->longitude }}]);
                    L.circleMarker([{{ $ev->latitude }}, {{ $ev->longitude }}], { radius: 5, color: '#f59e0b', fillColor: '#fbbf24', fillOpacity: 1 })
                        .addTo(map).bindPopup("<b>{{ $ev->event_type }}</b><br>{{ $ev->event_time->format('d M H:i') }}<br>{{ $ev->description }}");
                @endif
            @endforeach

            // Titik Pelabuhan Tujuan
            waypoints.push(dest);
            L.circleMarker(dest, { radius: 7, color: '#10b981', fillColor: '#34d399', fillOpacity: 1 })
                .addTo(map).bindPopup("<b>Pelabuhan Tujuan:</b> {{ $shipment->destination->name }}");

            // Garis Lintasan Rute
            const polyline = L.polyline(waypoints, { color: '#0284c7', weight: 3, dashArray: '6, 8' }).addTo(map);
            map.fitBounds(polyline.getBounds(), { padding: [40, 40] });
            setTimeout(function() { map.invalidateSize(); }, 300);
        });
    </script>
</x-app-layout>
