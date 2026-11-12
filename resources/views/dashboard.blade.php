<x-app-layout>
    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- 1. TOP 5 COLORFUL STAT CARDS (Sesuai Mockup Gambar 2) --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                
                {{-- Card 1: Total Shipments (Cyan / Teal) --}}
                <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex items-center gap-3.5 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-cyan-600 flex items-center justify-center text-white text-xl shadow-md shrink-0">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Shipments</div>
                        <div class="text-2xl font-black text-slate-900 leading-tight mt-0.5">{{ number_format($totalShipments) }}</div>
                    </div>
                </div>

                {{-- Card 2: In Transit (Orange / Amber) --}}
                <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex items-center gap-3.5 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center text-white text-xl shadow-md shrink-0">
                        <i class="fa-solid fa-ship"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">In Transit</div>
                        <div class="text-2xl font-black text-slate-900 leading-tight mt-0.5">{{ number_format($inTransitCount) }}</div>
                    </div>
                </div>

                {{-- Card 3: Delivered (Emerald / Green) --}}
                <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex items-center gap-3.5 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center text-white text-xl shadow-md shrink-0">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Delivered</div>
                        <div class="text-2xl font-black text-slate-900 leading-tight mt-0.5">{{ number_format($deliveredCount) }}</div>
                    </div>
                </div>

                {{-- Card 4: Delayed (Rose / Pink) --}}
                <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex items-center gap-3.5 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-rose-500 flex items-center justify-center text-white text-xl shadow-md shrink-0">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Delayed</div>
                        <div class="text-2xl font-black text-slate-900 leading-tight mt-0.5">{{ number_format($delayedCount) }}</div>
                    </div>
                </div>

                {{-- Card 5: Open Exceptions (Red / Coral) --}}
                <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex items-center gap-3.5 hover:shadow-md transition col-span-2 sm:col-span-1">
                    <div class="w-12 h-12 rounded-xl bg-red-600 flex items-center justify-center text-white text-xl shadow-md shrink-0">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Open Exceptions</div>
                        <div class="text-2xl font-black text-slate-900 leading-tight mt-0.5">{{ number_format($openExceptionsCount) }}</div>
                    </div>
                </div>

            </div>

            {{-- 2. MIDDLE SECTION (2 COLUMNS: TIMELINE & MAP DI KIRI + CHART & EXCEPTIONS DI KANAN) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT COLUMN: SHIPMENT TIMELINE & MAP VIEW (8 COLUMNS) --}}
                <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-map-location-dot text-sky-600"></i>
                            <h3 class="text-sm font-bold text-slate-900">Shipment Timeline & Map View</h3>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-100 text-sky-800 border border-sky-200 font-mono">Leaflet.js</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 flex-1">
                        {{-- Sub-Left: Timeline List (5 Columns) --}}
                        <div class="md:col-span-5 p-4 border-r border-slate-200 flex flex-col justify-between bg-white">
                            <div>
                                <div class="relative mb-3">
                                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                                    <input type="text" placeholder="Search Timeline..." class="w-full pl-8 pr-3 py-1.5 text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500 bg-slate-50">
                                </div>

                                {{-- Vertical Stepper with Colorful Pills --}}
                                <div class="space-y-4 relative before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200 pl-7 text-xs">
                                    
                                    {{-- Item 1: Delivered --}}
                                    <div class="relative">
                                        <div class="absolute -left-7 top-1 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white ring-2 ring-emerald-200"></div>
                                        <div class="font-bold text-slate-900">Shipment #CTG-2026-0082</div>
                                        <div class="text-[11px] text-slate-500">Surabaya &rarr; Singapore</div>
                                        <div class="mt-1 flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500 text-white shadow-xs">DELIVERED</span>
                                            <span class="text-[10px] text-slate-400">12 Nov</span>
                                        </div>
                                    </div>

                                    {{-- Item 2: In Transit --}}
                                    <div class="relative">
                                        <div class="absolute -left-7 top-1 w-3.5 h-3.5 rounded-full bg-amber-500 border-2 border-white ring-2 ring-amber-200"></div>
                                        <div class="font-bold text-slate-900">Shipment #CTG-2026-0081</div>
                                        <div class="text-[11px] text-slate-500">Jakarta &rarr; Rotterdam</div>
                                        <div class="mt-1 flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500 text-white shadow-xs">IN TRANSIT</span>
                                            <span class="text-[10px] text-slate-400">10 Nov</span>
                                        </div>
                                    </div>

                                    {{-- Item 3: Picked Up --}}
                                    <div class="relative">
                                        <div class="absolute -left-7 top-1 w-3.5 h-3.5 rounded-full bg-purple-500 border-2 border-white ring-2 ring-purple-200"></div>
                                        <div class="font-bold text-slate-900">Shipment #CTG-2026-0083</div>
                                        <div class="text-[11px] text-slate-500">Jakarta &rarr; Hamburg</div>
                                        <div class="mt-1 flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-600 text-white shadow-xs">PICKED UP</span>
                                            <span class="text-[10px] text-slate-400">02 Nov</span>
                                        </div>
                                    </div>

                                    {{-- Item 4: Booked --}}
                                    <div class="relative">
                                        <div class="absolute -left-7 top-1 w-3.5 h-3.5 rounded-full bg-sky-500 border-2 border-white ring-2 ring-sky-200"></div>
                                        <div class="font-bold text-slate-900">Shipment #CTG-2026-0084</div>
                                        <div class="text-[11px] text-slate-500">Singapore &rarr; Sydney</div>
                                        <div class="mt-1 flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-500 text-white shadow-xs">BOOKED</span>
                                            <span class="text-[10px] text-slate-400">28 Oct</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- Sub-Right: Leaflet Map Embedded (7 Columns) --}}
                        <div class="md:col-span-7 h-72 md:h-auto min-h-[300px] relative bg-slate-900">
                            <div id="dashboard-radar-map" class="w-full h-full min-h-[320px]"></div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: CHART.JS & EXCEPTIONS TABLE (5 COLUMNS) --}}
                <div class="lg:col-span-5 space-y-6">
                    
                    {{-- Performance Bar Chart (Chart.js) --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-bold text-slate-900">Shipment Overview & Performance</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200 font-mono">Chart.js</span>
                        </div>
                        <p class="text-xs text-slate-500 mb-3">Shipment Volume by Status (Past 7 Days)</p>
                        <div class="h-44 relative">
                            <canvas id="shipmentStatusChart"></canvas>
                        </div>
                    </div>

                    {{-- Delayed Shipments & Exceptions Widget --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-bold text-slate-900">Delayed Shipments & Exceptions</h3>
                            <span class="text-[10px] text-slate-500">Live SLA status</span>
                        </div>
                        <div class="divide-y divide-slate-100 text-xs">
                            <div class="py-2.5 flex items-center justify-between">
                                <div>
                                    <div class="font-bold text-slate-900">Shipment #CTG-2026-0081</div>
                                    <div class="text-[11px] text-slate-500">Weather Squall Indian Ocean</div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                    Delayed +14h
                                </span>
                            </div>

                            <div class="py-2.5 flex items-center justify-between">
                                <div>
                                    <div class="font-bold text-slate-900">Shipment #CTG-2026-0083</div>
                                    <div class="text-[11px] text-slate-500">Export Customs Verification</div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                    In Transit
                                </span>
                            </div>

                            <div class="py-2.5 flex items-center justify-between">
                                <div>
                                    <div class="font-bold text-slate-900">Shipment #CTG-2026-0082</div>
                                    <div class="text-[11px] text-slate-500">Singapore PSA Discharge</div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    Delivered
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- 3. BOTTOM SECTION: SHIPMENT TIMELINE & STATUS TABLE (Slide 10) --}}
            <div id="manifest-table" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200 bg-slate-50 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Shipment Timeline & Status</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Filter manifest kargo dan klik nomor kargo untuk melihat peta dan cuaca live.</p>
                    </div>

                    {{-- Search & Filter Mini Bar --}}
                    <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ref / kargo..." class="text-xs rounded-lg border-slate-300 py-1.5 px-3 focus:ring-sky-500 focus:border-sky-500">
                        <select name="status" class="text-xs rounded-lg border-slate-300 py-1.5 focus:ring-sky-500 focus:border-sky-500">
                            <option value="">Semua Status</option>
                            <option value="CREATED" {{ request('status') === 'CREATED' ? 'selected' : '' }}>CREATED</option>
                            <option value="BOOKED" {{ request('status') === 'BOOKED' ? 'selected' : '' }}>BOOKED</option>
                            <option value="PICKED_UP" {{ request('status') === 'PICKED_UP' ? 'selected' : '' }}>PICKED_UP</option>
                            <option value="IN_TRANSIT" {{ request('status') === 'IN_TRANSIT' ? 'selected' : '' }}>IN_TRANSIT</option>
                            <option value="DELIVERED" {{ request('status') === 'DELIVERED' ? 'selected' : '' }}>DELIVERED</option>
                        </select>
                        <button type="submit" class="px-3 py-1.5 bg-[#1b1f3b] text-white rounded-lg text-xs font-bold hover:bg-[#292f54] transition">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'status', 'carrier_id', 'delay_status']))
                            <a href="{{ route('dashboard') }}" class="px-2.5 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-xs font-bold">Reset</a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold border-b border-slate-200 tracking-wider">
                            <tr>
                                <th class="p-3.5">Shipment Ref</th>
                                <th class="p-3.5">Carrier</th>
                                <th class="p-3.5">Rute (Origin &rarr; Dest)</th>
                                <th class="p-3.5">Muatan Kargo</th>
                                <th class="p-3.5">Status Lifecycle</th>
                                <th class="p-3.5">Kondisi SLA / Delay</th>
                                <th class="p-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($shipments as $s)
                                <tr class="hover:bg-slate-50 transition">
                                    {{-- Shipment Ref --}}
                                    <td class="p-3.5">
                                        <a href="{{ route('shipments.show', $s) }}" class="font-bold text-sky-600 hover:text-sky-800 flex items-center gap-1.5">
                                            <span>{{ $s->shipment_number }}</span>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                        </a>
                                        <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $s->created_at->format('d M Y') }}</div>
                                    </td>

                                    {{-- Carrier --}}
                                    <td class="p-3.5">
                                        <div class="font-bold text-slate-800">{{ $s->carrier->name }}</div>
                                        <span class="inline-block px-1.5 py-0.5 text-[10px] rounded bg-slate-200/80 text-slate-700 font-mono font-semibold">{{ $s->carrier->code }}</span>
                                    </td>

                                    {{-- Route --}}
                                    <td class="p-3.5">
                                        <div class="flex items-center gap-1.5 font-semibold text-slate-800">
                                            <span>{{ $s->origin->city }}</span>
                                            <span class="text-sky-500 font-bold">&rarr;</span>
                                            <span>{{ $s->destination->city }}</span>
                                        </div>
                                        <div class="text-[10px] text-slate-500 font-mono">{{ $s->origin->code }} to {{ $s->destination->code }}</div>
                                    </td>

                                    {{-- Cargo --}}
                                    <td class="p-3.5">
                                        <div class="font-semibold text-slate-900 truncate max-w-xs">{{ $s->cargo_type }}</div>
                                        <div class="text-[11px] text-slate-500">{{ number_format($s->weight_kg, 0) }} kg | {{ $s->volume_cbm }} CBM</div>
                                    </td>

                                    {{-- Status Lifecycle --}}
                                    <td class="p-3.5">
                                        @php
                                            $pillStyles = [
                                                'CREATED'    => 'bg-slate-200 text-slate-800',
                                                'BOOKED'     => 'bg-sky-500 text-white',
                                                'PICKED_UP'  => 'bg-purple-600 text-white',
                                                'IN_TRANSIT' => 'bg-amber-500 text-white font-bold animate-pulse',
                                                'DELIVERED'  => 'bg-emerald-500 text-white font-bold',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold shadow-xs {{ $pillStyles[$s->current_status] ?? 'bg-slate-200 text-slate-800' }}">
                                            {{ $s->current_status }}
                                        </span>
                                    </td>

                                    {{-- Delay --}}
                                    <td class="p-3.5">
                                        @if($s->delay_status === 'DELAYED')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-bold bg-rose-500 text-white shadow-xs">
                                                <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> DELAY +{{ $s->delay_hours }}h ({{ $s->delay_severity }})
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-300">
                                                <i class="fa-solid fa-check text-[9px]"></i> ON TIME
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Action --}}
                                    <td class="p-3.5 text-right">
                                        <a href="{{ route('shipments.show', $s) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-700 rounded-lg text-xs font-bold border border-sky-200 transition">
                                            <span>Detail</span>
                                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-500">
                                        Tidak ada data shipment yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-4 border-t border-slate-200">
                    {{ $shipments->links() }}
                </div>
            </div>

        </div>
    </div>

    {{-- Script untuk Leaflet Map & Chart.js (Slide 09 & 14) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Chart.js: Shipment Volume by Status Bar Chart (Sesuai Mockup Gambar 2)
            const ctx = document.getElementById('shipmentStatusChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Booked', 'In Transit', 'Delivered', 'Delayed'],
                        datasets: [{
                            data: [{{ $totalShipments - $inTransitCount - $deliveredCount }}, {{ $inTransitCount }}, {{ $deliveredCount }}, {{ $delayedCount }}],
                            backgroundColor: [
                                '#3b82f6', // Booked: Blue
                                '#f59e0b', // In Transit: Orange/Amber
                                '#10b981', // Delivered: Green
                                '#f43f5e'  // Delayed: Rose/Pink
                            ],
                            borderRadius: 6,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f1f5f9' },
                                ticks: { font: { size: 10 } }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10 } }
                            }
                        }
                    }
                });
            }

            // 2. Leaflet Map View di Sub-kolom Kanan (Sesuai Mockup Gambar 2)
            const mapData = @json($mapShipments);
            const mapEl = document.getElementById('dashboard-radar-map');
            
            if (mapEl) {
                const map = L.map('dashboard-radar-map', {
                    zoomControl: true,
                    attributionControl: false
                }).setView([15.0, 75.0], 3);

                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                    maxZoom: 18
                }).addTo(map);

                const bounds = [];

                mapData.forEach(item => {
                    const originLatLng = [item.origin.lat, item.origin.lng];
                    const destLatLng = [item.destination.lat, item.destination.lng];
                    const currentLatLng = [item.current_location.lat, item.current_location.lng];

                    bounds.push(originLatLng, destLatLng, currentLatLng);

                    // Polyline Garis Pelayaran
                    const isDelayed = item.delay_status === 'DELAYED';
                    const routeColor = isDelayed ? '#f43f5e' : (item.status === 'DELIVERED' ? '#10b981' : '#0284c7');

                    L.polyline([originLatLng, currentLatLng, destLatLng], {
                        color: routeColor,
                        weight: 2.5,
                        opacity: 0.8
                    }).addTo(map);

                    // Origin Marker
                    L.circleMarker(originLatLng, {
                        radius: 5,
                        color: '#0369a1',
                        fillColor: '#38bdf8',
                        fillOpacity: 1,
                        weight: 2
                    }).addTo(map).bindPopup(`<b>Origin:</b> ${item.origin.name}`);

                    // Destination Marker
                    L.circleMarker(destLatLng, {
                        radius: 5,
                        color: '#059669',
                        fillColor: '#34d399',
                        fillOpacity: 1,
                        weight: 2
                    }).addTo(map).bindPopup(`<b>Destination:</b> ${item.destination.name}`);

                    // Current Location Marker
                    const beacon = L.circleMarker(currentLatLng, {
                        radius: 6,
                        color: isDelayed ? '#be123c' : '#0284c7',
                        fillColor: isDelayed ? '#f43f5e' : '#38bdf8',
                        fillOpacity: 1,
                        weight: 2
                    }).addTo(map);

                    beacon.bindPopup(`
                        <div style="font-family: sans-serif; font-size: 11px; line-height: 1.4;">
                            <strong style="color: #0f172a;">Current Location</strong><br>
                            <span style="color: #64748b;">Shipment: ${item.number}</span><br>
                            <span style="color: #64748b;">Carrier: ${item.carrier}</span><br>
                            <span style="color: ${isDelayed ? '#be123c' : '#059669'}; font-weight: bold;">
                                Status: ${item.status} (${isDelayed ? 'Delayed' : 'On Schedule'})
                            </span>
                        </div>
                    `);
                    
                    // Buka popup untuk shipment aktif pertama (persis seperti di gambar contoh)
                    if (item.status === 'IN_TRANSIT') {
                        beacon.openPopup();
                    }
                });

                if (bounds.length > 0) {
                    map.fitBounds(bounds, { padding: [25, 25] });
                }
            }
        });
    </script>
</x-app-layout>
