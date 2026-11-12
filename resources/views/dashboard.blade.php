<x-app-layout>
    {{-- Memuat Asset Leaflet.js untuk Peta Interaktif (Slide 09) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        /* Animasi denied/pulsing untuk kapal yang sedang berlayar (IN_TRANSIT) */
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.4); opacity: 0.2; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
        .pulsing-marker {
            width: 14px;
            height: 14px;
            background-color: #0284c7;
            border: 2px solid #ffffff;
            border-radius: 50%;
            position: relative;
            box-shadow: 0 0 10px rgba(2, 132, 199, 0.8);
        }
        .pulsing-marker::after {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            width: 18px;
            height: 18px;
            border: 2px solid #38bdf8;
            border-radius: 50%;
            animation: pulse-ring 2s infinite ease-in-out;
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wider bg-slate-800 text-sky-400 border border-slate-700">
                        Fleet Telemetry Deck
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wider {{ Auth::user()->role === 'admin' ? 'bg-purple-900/50 text-purple-300 border border-purple-700' : (Auth::user()->role === 'operator' ? 'bg-amber-900/50 text-amber-300 border border-amber-700' : 'bg-emerald-900/50 text-emerald-300 border border-emerald-700') }}">
                        Role: {{ Auth::user()->role }}
                    </span>
                </div>
                <h2 class="text-xl font-bold text-gray-900 leading-tight mt-1">
                    Global Shipment Visibility Console
                </h2>
            </div>
            
            {{-- Jam UTC Maritim Real-time --}}
            <div class="flex items-center gap-3 bg-slate-900 text-slate-100 px-4 py-2 rounded-lg border border-slate-800 shadow-sm text-xs font-mono">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span>UTC TIME: <strong id="utc-clock" class="text-emerald-400">--:--:--</strong></span>
                <span class="text-slate-500">|</span>
                <span class="text-slate-400">TELEMETRY: ONLINE</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- 1. TOP METRIC HUD (Slide 10: Real Database Counts) --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Manifest</div>
                    <div class="text-2xl font-black text-slate-900 mt-1">{{ $totalShipments }}</div>
                    <div class="text-xs text-slate-500 mt-1">Active tracked units</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="text-xs font-medium text-sky-600 uppercase tracking-wider">In Transit (Sea/Air)</div>
                    <div class="text-2xl font-black text-sky-700 mt-1">{{ $inTransitCount }}</div>
                    <div class="text-xs text-sky-600 mt-1">Underway voyages</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="text-xs font-medium text-emerald-600 uppercase tracking-wider">Delivered On-Time</div>
                    <div class="text-2xl font-black text-emerald-700 mt-1">{{ $deliveredCount }}</div>
                    <div class="text-xs text-emerald-600 mt-1">Completed shipments</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="text-xs font-medium text-amber-600 uppercase tracking-wider">Delayed / SLA Risk</div>
                    <div class="text-2xl font-black text-amber-700 mt-1">{{ $delayedCount }}</div>
                    <div class="text-xs text-amber-600 mt-1">Eta deviation detected</div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm col-span-2 md:col-span-1">
                    <div class="text-xs font-medium text-rose-600 uppercase tracking-wider">Open Exceptions</div>
                    <div class="text-2xl font-black text-rose-700 mt-1">{{ $openExceptionsCount }}</div>
                    <div class="text-xs text-rose-600 mt-1">Requires investigation</div>
                </div>
            </div>

            {{-- 2. EARLY WARNING ALERT BANNER --}}
            @if($openExceptionsCount > 0)
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm flex items-start gap-3">
                    <div class="p-1 rounded-full bg-rose-100 text-rose-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-rose-900">Active Operational Exceptions Detected</h4>
                        <p class="text-xs text-rose-700 mt-0.5">Terdapat {{ $openExceptionsCount }} shipment mengalami hambatan (cuaca/bea cukai/pelabuhan). Segera tinjau status di tabel manifest di bawah.</p>
                    </div>
                </div>
            @endif

            {{-- 3. INTERACTIVE ROUTE RADAR MAP (Slide 09: Leaflet.js + OpenStreetMap) --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-2 bg-slate-50">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-sky-500"></div>
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Live Ocean & Freight Route Radar</h3>
                    </div>
                    <span class="text-xs text-slate-500">Menampilkan lintasan koordinat asal (origin), waypoint transit, dan pelabuhan tujuan.</span>
                </div>
                
                {{-- Kontainer Peta Leaflet --}}
                <div id="radar-map" class="h-96 w-full bg-slate-900 z-0"></div>
            </div>

            {{-- 4. FILTER & SEARCH CONTROL BAR (Slide 10) --}}
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Cari Kargo / No. Shipment</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Misal: CTG-2026 atau Coffee" class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Status Lifecycle</label>
                        <select name="status" class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500">
                            <option value="">Semua Status</option>
                            <option value="CREATED" {{ request('status') === 'CREATED' ? 'selected' : '' }}>CREATED</option>
                            <option value="BOOKED" {{ request('status') === 'BOOKED' ? 'selected' : '' }}>BOOKED</option>
                            <option value="PICKED_UP" {{ request('status') === 'PICKED_UP' ? 'selected' : '' }}>PICKED_UP</option>
                            <option value="IN_TRANSIT" {{ request('status') === 'IN_TRANSIT' ? 'selected' : '' }}>IN_TRANSIT</option>
                            <option value="DELIVERED" {{ request('status') === 'DELIVERED' ? 'selected' : '' }}>DELIVERED</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Armada Carrier</label>
                        <select name="carrier_id" class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500">
                            <option value="">Semua Carrier</option>
                            @foreach($carriers as $c)
                                <option value="{{ $c->id }}" {{ request('carrier_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Kondisi Keterlambatan</label>
                        <select name="delay_status" class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500">
                            <option value="">Semua Kondisi</option>
                            <option value="ON_TIME" {{ request('delay_status') === 'ON_TIME' ? 'selected' : '' }}>ON TIME (Tepat Waktu)</option>
                            <option value="DELAYED" {{ request('delay_status') === 'DELAYED' ? 'selected' : '' }}>DELAYED (Terlambat)</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold border border-slate-300 transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- 5. OPERATIONS STREAM TABLE (Slide 10) --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Manifest Pengiriman Aktif</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar perjalanan kargo dan pelacakan telemetri real-time.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200 tracking-wider">
                            <tr>
                                <th class="p-3">Shipment Ref</th>
                                <th class="p-3">Carrier / Armada</th>
                                <th class="p-3">Rute (Origin &rarr; Dest)</th>
                                <th class="p-3">Muatan Kargo</th>
                                <th class="p-3">Status Lifecycle</th>
                                <th class="p-3">Kondisi ETA & Delay</th>
                                <th class="p-3">Titik Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-mono">
                            @forelse($shipments as $s)
                                <tr class="hover:bg-slate-50/80 transition">
                                    {{-- Shipment Ref --}}
                                    <td class="p-3">
                                        <div class="font-bold text-slate-900 font-sans">{{ $s->shipment_number }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $s->created_at->format('d M Y') }}</div>
                                    </td>

                                    {{-- Carrier --}}
                                    <td class="p-3 font-sans">
                                        <div class="font-medium text-slate-800">{{ $s->carrier->name }}</div>
                                        <span class="inline-block px-1.5 py-0.5 text-[10px] rounded bg-slate-100 text-slate-600 border border-slate-200">{{ $s->carrier->code }}</span>
                                    </td>

                                    {{-- Route --}}
                                    <td class="p-3 font-sans">
                                        <div class="flex items-center gap-1.5 text-slate-800 font-medium">
                                            <span>{{ $s->origin->city }}</span>
                                            <span class="text-slate-400">&rarr;</span>
                                            <span>{{ $s->destination->city }}</span>
                                        </div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">
                                            {{ $s->origin->code }} to {{ $s->destination->code }}
                                        </div>
                                    </td>

                                    {{-- Cargo --}}
                                    <td class="p-3 font-sans">
                                        <div class="text-slate-900 font-medium truncate max-w-xs">{{ $s->cargo_type }}</div>
                                        <div class="text-[11px] text-slate-500">{{ number_format($s->weight_kg, 0) }} kg | {{ $s->volume_cbm }} CBM</div>
                                    </td>

                                    {{-- Status Lifecycle --}}
                                    <td class="p-3 font-sans">
                                        @php
                                            $statusColors = [
                                                'CREATED'    => 'bg-slate-100 text-slate-700 border-slate-300',
                                                'BOOKED'     => 'bg-sky-50 text-sky-700 border-sky-300',
                                                'PICKED_UP'  => 'bg-indigo-50 text-indigo-700 border-indigo-300',
                                                'IN_TRANSIT' => 'bg-amber-50 text-amber-800 border-amber-300 animate-pulse',
                                                'DELIVERED'  => 'bg-emerald-50 text-emerald-800 border-emerald-300',
                                            ];
                                            $color = $statusColors[$s->current_status] ?? 'bg-slate-100 text-slate-700 border-slate-300';
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold border {{ $color }}">
                                            {{ $s->current_status }}
                                        </span>
                                    </td>

                                    {{-- Delay --}}
                                    <td class="p-3 font-sans">
                                        @if($s->delay_status === 'DELAYED')
                                            <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-300">
                                                <span>DELAY +{{ $s->delay_hours }}h</span>
                                                <span class="text-[9px] px-1 rounded bg-rose-200 text-rose-800">({{ $s->delay_severity }})</span>
                                            </div>
                                            @if($s->delay_reason)
                                                <div class="text-[10px] text-rose-600 mt-1 max-w-xs truncate" title="{{ $s->delay_reason }}">
                                                    {{ $s->delay_reason }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-300">
                                                ON TIME
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Latest Waypoint --}}
                                    <td class="p-3 font-sans">
                                        <div class="text-slate-800 text-[11px] font-medium">
                                            {{ $s->latestEvent?->location_name ?? $s->origin->name }}
                                        </div>
                                        <div class="text-[10px] text-slate-400">
                                            {{ $s->latestEvent ? $s->latestEvent->event_time->format('d M H:i') : $s->expected_departure->format('d M H:i') }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-500 font-sans">
                                        Tidak ada data shipment yang sesuai dengan filter pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Server-Side --}}
                <div class="p-4 border-t border-slate-200">
                    {{ $shipments->links() }}
                </div>
            </div>

        </div>
    </div>

    {{-- Script untuk Jam Maritim Real-time dan Peta Leaflet --}}
    <script>
        // 1. Live UTC Clock
        function updateUtcClock() {
            const now = new Date();
            const hours = String(now.getUTCHours()).padStart(2, '0');
            const minutes = String(now.getUTCMinutes()).padStart(2, '0');
            const seconds = String(now.getUTCSeconds()).padStart(2, '0');
            const clockEl = document.getElementById('utc-clock');
            if (clockEl) {
                clockEl.innerText = `${hours}:${minutes}:${seconds} UTC`;
            }
        }
        setInterval(updateUtcClock, 1000);
        updateUtcClock();

        // 2. Leaflet Map Radar Integration (Slide 09)
        document.addEventListener('DOMContentLoaded', function () {
            const mapData = @json($mapShipments);

            // Inisialisasi Peta berpusat di Samudra Hindia / Asia Tenggara
            const map = L.map('radar-map', {
                zoomControl: true,
                attributionControl: true
            }).setView([10.0, 80.0], 3);

            // CartoDB Voyager Tile (tampilan bersih, elegan, dan profesional)
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                maxZoom: 18
            }).addTo(map);

            const bounds = [];

            // Gambar titik dan garis untuk setiap shipment
            mapData.forEach(item => {
                const originLatLng = [item.origin.lat, item.origin.lng];
                const destLatLng = [item.destination.lat, item.destination.lng];
                const currentLatLng = [item.current_location.lat, item.current_location.lng];

                bounds.push(originLatLng, destLatLng, currentLatLng);

                // Garis putus-putus rute pengiriman
                const isDelayed = item.delay_status === 'DELAYED';
                const routeColor = isDelayed ? '#f43f5e' : (item.status === 'DELIVERED' ? '#10b981' : '#0284c7');

                L.polyline([originLatLng, currentLatLng, destLatLng], {
                    color: routeColor,
                    weight: 2,
                    dashArray: '5, 8',
                    opacity: 0.7
                }).addTo(map);

                // Marker Titik Asal (Origin)
                L.circleMarker(originLatLng, {
                    radius: 5,
                    color: '#64748b',
                    fillColor: '#ffffff',
                    fillOpacity: 1,
                    weight: 2
                }).addTo(map).bindPopup(`<b>Origin:</b> ${item.origin.name}<br><b>Shipment:</b> ${item.number}`);

                // Marker Titik Tujuan (Destination)
                L.circleMarker(destLatLng, {
                    radius: 5,
                    color: '#10b981',
                    fillColor: '#10b981',
                    fillOpacity: 1,
                    weight: 1
                }).addTo(map).bindPopup(`<b>Destination:</b> ${item.destination.name}<br><b>Shipment:</b> ${item.number}`);

                // Custom Icon untuk Posisi Kargo Terkini
                const customBeacon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div class="pulsing-marker" style="background-color: ${isDelayed ? '#e11d48' : '#0284c7'};"></div>`,
                    iconSize: [14, 14],
                    iconAnchor: [7, 7]
                });

                L.marker(currentLatLng, { icon: customBeacon })
                    .addTo(map)
                    .bindPopup(`
                        <div style="font-family: sans-serif; font-size: 12px; line-height: 1.4;">
                            <strong style="color: #0f172a;">${item.number}</strong><br>
                            <span style="color: #64748b;">Carrier: ${item.carrier}</span><br>
                            <span>Cargo: ${item.cargo}</span><br>
                            <span style="display: inline-block; margin-top: 4px; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 10px; background: ${isDelayed ? '#ffe4e6' : '#e0f2fe'}; color: ${isDelayed ? '#be123c' : '#0369a1'};">
                                ${item.status} ${isDelayed ? '• DELAYED' : ''}
                            </span>
                        </div>
                    `);
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [40, 40] });
            }
        });
    </script>
</x-app-layout>