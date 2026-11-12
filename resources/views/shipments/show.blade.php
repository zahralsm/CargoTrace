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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

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
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
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
                            <div class="text-[10px] font-mono {{ $isCurrent ? 'text-sky-100' : ($isPassed ? 'text-emerald-600' : 'text-slate-400') }}">Tahap 0{{ $idx + 1 }}</div>
                            <div class="text-xs font-extrabold mt-1 tracking-tight">
                                {{ $stepNames[$step] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 2. KARTU TELEMETRI CUACA LANGSUNG DI PELABUHAN ASAL & TUJUAN (Slide 09) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Cuaca Pelabuhan Asal --}}
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full {{ $originWeather['status'] === 'ONLINE' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pelabuhan Asal • Cuaca Langsung</span>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900 mt-1">{{ $shipment->origin->name }} ({{ $shipment->origin->city }})</h4>
                        <p class="text-xs text-slate-500 mt-0.5"><i class="fa-solid fa-cloud-sun text-amber-500 mr-1"></i> {{ $originWeather['condition'] }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-black text-slate-900 font-mono">
                            {{ $originWeather['temperature'] ? $originWeather['temperature'] . '°C' : 'N/A' }}
                        </div>
                        <div class="text-[11px] text-slate-500 mt-0.5">
                            Angin: {{ $originWeather['wind_speed'] ?? '-' }} km/jam | Kelembaban: {{ $originWeather['humidity'] ?? '-' }}%
                        </div>
                    </div>
                </div>

                {{-- Cuaca Pelabuhan Tujuan --}}
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full {{ $destinationWeather['status'] === 'ONLINE' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pelabuhan Tujuan • Cuaca Langsung</span>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900 mt-1">{{ $shipment->destination->name }} ({{ $shipment->destination->city }})</h4>
                        <p class="text-xs text-slate-500 mt-0.5"><i class="fa-solid fa-cloud-sun text-amber-500 mr-1"></i> {{ $destinationWeather['condition'] }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-black text-slate-900 font-mono">
                            {{ $destinationWeather['temperature'] ? $destinationWeather['temperature'] . '°C' : 'N/A' }}
                        </div>
                        <div class="text-[11px] text-slate-500 mt-0.5">
                            Angin: {{ $destinationWeather['wind_speed'] ?? '-' }} km/jam | Kelembaban: {{ $destinationWeather['humidity'] ?? '-' }}%
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. PETA RADAR LINTASAN PELAYARAN (Slide 09) --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-compass text-sky-600"></i>
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Radar Lintasan Rute Kargo</h3>
                    </div>
                    <span class="text-xs text-slate-500">Lintasan pelayaran laut dan titik koordinat singgah</span>
                </div>
                <div id="shipment-map" class="h-80 w-full bg-slate-900"></div>
            </div>

            {{-- 4. TIMELINE PELACAKAN & SPESIFIKASI / KONSOL OPERATOR --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- TIMELINE KRONOLOGIS (Slide 07) --}}
                <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-timeline text-sky-600"></i>
                            <span>Riwayat Perjalanan (Timeline Kronologis)</span>
                        </h3>
                        <span class="text-xs text-slate-500 font-mono">{{ $shipment->trackingEvents->count() }} Titik Pemeriksaan Tercatat</span>
                    </div>

                    <div class="relative border-l-2 border-slate-200 ml-4 space-y-6">
                        @forelse($shipment->trackingEvents as $event)
                            <div class="relative pl-6">
                                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-white border-4 border-sky-600"></div>

                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <span class="text-xs font-black text-slate-900 tracking-tight">{{ $event->event_type }}</span>
                                    <span class="text-[11px] font-mono text-slate-500">{{ $event->event_time->format('d M Y, H:i') }} UTC</span>
                                </div>
                                <div class="text-xs font-semibold text-slate-700 mt-0.5">
                                    {{ $event->location_name ?? $event->location?->name ?? 'Titik Koordinat Laut' }}
                                    @if($event->latitude && $event->longitude)
                                        <span class="text-[10px] font-mono text-slate-400">({{ $event->latitude }}, {{ $event->longitude }})</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-600 mt-1.5 leading-relaxed bg-slate-50 p-3 rounded-lg border border-slate-100">
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
                                                <input type="text" name="resolution_notes" required placeholder="Tuliskan catatan penyelesaian gangguan..." class="text-xs rounded-lg border-slate-300 flex-1">
                                                <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition">
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

                {{-- SPESIFIKASI MANIFEST & KONSOL OPERATOR --}}
                <div class="space-y-6">

                    {{-- Spesifikasi Muatan --}}
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-3">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Spesifikasi Manifest Kargo</h4>
                        <div class="text-xs space-y-2">
                            <div><strong class="text-slate-500">Komoditas:</strong> <span class="font-bold text-slate-900">{{ $shipment->cargo_type }}</span></div>
                            <div><strong class="text-slate-500">Berat Bersih:</strong> <span class="font-bold text-slate-900">{{ number_format($shipment->weight_kg, 2) }} KG</span></div>
                            <div><strong class="text-slate-500">Volume Muatan:</strong> <span class="font-bold text-slate-900">{{ $shipment->volume_cbm }} CBM</span></div>
                            <div><strong class="text-slate-500">Maskapai Pelayaran:</strong> <span class="font-bold text-slate-900">{{ $shipment->carrier->name }} ({{ $shipment->carrier->code }})</span></div>
                            <div><strong class="text-slate-500">Pemilik / Pengirim:</strong> <span class="font-bold text-slate-900">{{ $shipment->customer->name }}</span></div>
                        </div>
                    </div>

                    {{-- KONSOL PENGENDALI OPERATOR (Hanya untuk Operator & Admin) --}}
                    @if(!Auth::user()->isCustomer())
                        <div class="bg-[#1b1f3b] text-white p-5 rounded-xl border border-[#292f54] shadow-sm space-y-4">
                            <div class="flex items-center justify-between border-b border-[#292f54] pb-2">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-sky-400">Konsol Pengendali Dispatch</h4>
                                <span class="text-[10px] text-slate-400 font-mono">MODE OPERATOR</span>
                            </div>

                            <form method="POST" action="{{ route('shipments.tracking.store', $shipment) }}" class="space-y-3 text-xs">
                                @csrf
                                <div>
                                    <label class="block text-[11px] text-slate-300 font-semibold mb-1">Status / Jenis Peristiwa Baru</label>
                                    <select name="event_type" required class="w-full text-xs rounded-lg bg-[#252b4d] border-[#343b66] text-white">
                                        <option value="BOOKED">BOOKED (Pemesanan Dikonfirmasi)</option>
                                        <option value="PICKED_UP">PICKED_UP (Kontainer Dimuat di Dermaga)</option>
                                        <option value="DEPARTED">DEPARTED (Kapal Bertolak dari Pelabuhan)</option>
                                        <option value="IN_TRANSIT">IN_TRANSIT (Sedang Berlayar di Samudra)</option>
                                        <option value="CUSTOMS_HOLD">CUSTOMS_HOLD (Tertahan Bea Cukai)</option>
                                        <option value="DELIVERED">DELIVERED (Kargo Tiba di Tujuan)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] text-slate-300 font-semibold mb-1">Pilih Titik Lokasi / Pelabuhan</label>
                                    <select name="location_id" class="w-full text-xs rounded-lg bg-[#252b4d] border-[#343b66] text-white">
                                        <option value="">-- Koordinat Bebas / Laut Lepas --</option>
                                        @foreach($locations as $loc)
                                            <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->city }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[10px] text-slate-400 mb-1">Garis Lintang (Latitude)</label>
                                        <input type="text" name="latitude" placeholder="Misal: -6.103" class="w-full text-xs rounded-lg bg-[#252b4d] border-[#343b66] text-white">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-slate-400 mb-1">Garis Bujur (Longitude)</label>
                                        <input type="text" name="longitude" placeholder="Misal: 106.882" class="w-full text-xs rounded-lg bg-[#252b4d] border-[#343b66] text-white">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[11px] text-slate-300 font-semibold mb-1">Waktu Kejadian (Timestamp)</label>
                                    <input type="datetime-local" name="event_time" required value="{{ date('Y-m-d\TH:i') }}" class="w-full text-xs rounded-lg bg-[#252b4d] border-[#343b66] text-white">
                                </div>

                                <div>
                                    <label class="block text-[11px] text-slate-300 font-semibold mb-1">Catatan Operasional (Deskripsi)</label>
                                    <textarea name="description" rows="2" required placeholder="Catatan muatan, kondisi segel kontainer, posisi kapal..." class="w-full text-xs rounded-lg bg-[#252b4d] border-[#343b66] text-white"></textarea>
                                </div>

                                <button type="submit" class="w-full py-2.5 bg-sky-600 hover:bg-sky-500 text-white font-bold rounded-lg text-xs shadow-md transition">
                                    + Catat Titik Pelacakan (Checkpoint)
                                </button>
                            </form>
                        </div>
                    @endif

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
        });
    </script>
</x-app-layout>