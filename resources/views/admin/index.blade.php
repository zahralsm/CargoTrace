<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <a href="{{ route('dashboard') }}" class="text-xs font-bold text-sky-600 hover:text-sky-800 flex items-center gap-1.5 mb-1.5 transition">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                </a>
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#1b1f3b] text-sky-400 flex items-center justify-center text-sm font-bold shadow-xs">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
                            Pusat Administrasi Sistem & Log Audit
                        </h2>
                        <p class="text-xs text-slate-500 font-mono">Slide 05: Kelola Maskapai Pelayaran, Pelabuhan, dan Rekam Jejak Forensik</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                    <i class="fa-solid fa-lock mr-1"></i> Hak Akses Administrator Aktif
                </span>
            </div>
        </div>
    </x-slot>

    <div x-data="{ activeTab: 'audit' }" class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Pesan Notifikasi Sukses --}}
            @if(session('success'))
                <div class="p-3.5 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg text-xs font-bold text-emerald-800 shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Tab Navigasi Menu Admin --}}
            <div class="flex items-center gap-2 border-b border-slate-200 bg-white p-2 rounded-xl shadow-xs text-xs font-bold">
                <button @click="activeTab = 'audit'" :class="activeTab === 'audit' ? 'bg-[#1b1f3b] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="px-4 py-2 rounded-lg transition flex items-center gap-2">
                    <i class="fa-solid fa-fingerprint"></i>
                    <span>Log Rekam Jejak (Audit Trail)</span>
                    <span class="px-1.5 py-0.2 rounded text-[10px] bg-slate-700 text-slate-200">{{ $auditLogs->total() }}</span>
                </button>

                <button @click="activeTab = 'carriers'" :class="activeTab === 'carriers' ? 'bg-[#1b1f3b] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="px-4 py-2 rounded-lg transition flex items-center gap-2">
                    <i class="fa-solid fa-ship"></i>
                    <span>Kelola Maskapai Kapal (Carriers)</span>
                    <span class="px-1.5 py-0.2 rounded text-[10px] bg-slate-700 text-slate-200">{{ $carriers->count() }}</span>
                </button>

                <button @click="activeTab = 'locations'" :class="activeTab === 'locations' ? 'bg-[#1b1f3b] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="px-4 py-2 rounded-lg transition flex items-center gap-2">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>Kelola Pelabuhan & Lokasi</span>
                    <span class="px-1.5 py-0.2 rounded text-[10px] bg-slate-700 text-slate-200">{{ $locations->count() }}</span>
                </button>
            </div>

            {{-- TAB 1: AUDIT TRAIL LOGS (Slide 05: Lihat Audit Log) --}}
            <div x-show="activeTab === 'audit'" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden space-y-4">
                <div class="p-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Catatan Aktivitas Sistem (Audit Trail)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Merekam setiap aksi perubahan status kargo, titik checkpoint tracking, dan identitas pengguna.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-3.5">Waktu Kejadian</th>
                                <th class="p-3.5">Aktor / Pengguna</th>
                                <th class="p-3.5">Aksi Sistem</th>
                                <th class="p-3.5">Entitas Target</th>
                                <th class="p-3.5">Detail Perubahan Data</th>
                                <th class="p-3.5">Alamat IP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-mono">
                            @forelse($auditLogs as $log)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-3.5 text-slate-500 font-mono text-[11px]">
                                        {{ $log->created_at->format('d M Y, H:i:s') }}
                                    </td>
                                    <td class="p-3.5 font-sans">
                                        <div class="font-bold text-slate-900">{{ $log->user?->name ?? 'Sistem Otomatis' }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $log->user?->role ?? 'CRON/AUTO' }}</div>
                                    </td>
                                    <td class="p-3.5 font-sans">
                                        @php
                                            $actionBadge = [
                                                'STATUS_TRANSITION'  => 'bg-amber-100 text-amber-800 border-amber-200',
                                                'ADD_TRACKING_EVENT' => 'bg-sky-100 text-sky-800 border-sky-200',
                                                'CREATE_SHIPMENT'    => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            ];
                                            $actionLabel = [
                                                'STATUS_TRANSITION'  => 'PERUBAHAN STATUS',
                                                'ADD_TRACKING_EVENT' => 'TITIK CHECKPOINT',
                                                'CREATE_SHIPMENT'    => 'REGISTRASI KARGO',
                                            ];
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $actionBadge[$log->action] ?? 'bg-slate-100 text-slate-700' }}">
                                            {{ $actionLabel[$log->action] ?? $log->action }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 font-sans text-slate-700 font-semibold">
                                        {{ class_basename($log->entity_type) }} #{{ $log->entity_id }}
                                    </td>
                                    <td class="p-3.5 font-mono text-[11px] max-w-xs truncate text-slate-600">
                                        @if($log->new_values)
                                            <span class="text-emerald-700">{{ json_encode($log->new_values) }}</span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-slate-500 font-mono text-[11px]">
                                        {{ $log->ip_address ?? '127.0.0.1' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-slate-500 font-sans">
                                        Belum ada catatan aktivitas audit log.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-3.5 border-t border-slate-200">
                    {{ $auditLogs->links() }}
                </div>
            </div>

            {{-- TAB 2: MASTER CARRIERS (Slide 05: Kelola Carrier) --}}
            <div x-show="activeTab === 'carriers'" style="display: none;" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Form Tambah Carrier --}}
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-plus text-sky-600"></i>
                        <span>Tambah Maskapai Baru</span>
                    </h3>

                    <form method="POST" action="{{ route('admin.carriers.store') }}" class="space-y-3 text-xs">
                        @csrf
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Maskapai / Operator Kapal</label>
                            <input type="text" name="name" required placeholder="Misal: Evergreen Marine Corp" class="w-full text-xs rounded-lg border-slate-300">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Kode Carrier (Singkatan Unik)</label>
                            <input type="text" name="code" required placeholder="Misal: EMC" class="w-full text-xs rounded-lg border-slate-300 uppercase">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Jenis Layanan</label>
                            <select name="service_type" required class="w-full text-xs rounded-lg border-slate-300">
                                <option value="SEA_FREIGHT">SEA_FREIGHT (Kapal Kargo Laut)</option>
                                <option value="AIR_FREIGHT">AIR_FREIGHT (Pesawat Kargo Udara)</option>
                                <option value="MULTIMODAL">MULTIMODAL (Kombinasi Laut & Darat)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Email Kontak Dispatch</label>
                            <input type="email" name="contact_email" placeholder="dispatch@evergreen.com" class="w-full text-xs rounded-lg border-slate-300">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">No. Telepon / Hotline</label>
                            <input type="text" name="contact_phone" placeholder="+62 21 555 1234" class="w-full text-xs rounded-lg border-slate-300">
                        </div>

                        <button type="submit" class="w-full py-2 bg-[#1b1f3b] hover:bg-sky-600 text-white font-bold rounded-lg transition shadow-xs">
                            + Daftarkan Maskapai
                        </button>
                    </form>
                </div>

                {{-- Tabel Daftar Carrier --}}
                <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-slate-200 bg-slate-50">
                        <h4 class="text-sm font-bold text-slate-900">Daftar Maskapai Pelayaran Terdaftar</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-100 text-slate-700 font-bold uppercase">
                                <tr>
                                    <th class="p-3">Kode</th>
                                    <th class="p-3">Nama Maskapai</th>
                                    <th class="p-3">Jenis Layanan</th>
                                    <th class="p-3">Kontak</th>
                                    <th class="p-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($carriers as $carrier)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="p-3 font-mono font-bold text-sky-600">{{ $carrier->code }}</td>
                                        <td class="p-3 font-bold text-slate-900">{{ $carrier->name }}</td>
                                        <td class="p-3"><span class="px-2 py-0.5 rounded bg-slate-100 font-mono text-[10px]">{{ $carrier->service_type }}</span></td>
                                        <td class="p-3 text-slate-500">{{ $carrier->contact_email ?? '-' }}</td>
                                        <td class="p-3"><span class="text-emerald-700 font-bold">AKTIF</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TAB 3: MASTER LOCATIONS (Slide 05: Kelola Lokasi) --}}
            <div x-show="activeTab === 'locations'" style="display: none;" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Form Tambah Lokasi --}}
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-plus text-sky-600"></i>
                        <span>Tambah Pelabuhan Baru</span>
                    </h3>

                    <form method="POST" action="{{ route('admin.locations.store') }}" class="space-y-3 text-xs">
                        @csrf
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nama Pelabuhan / Terminal</label>
                            <input type="text" name="name" required placeholder="Misal: Port of Belawan" class="w-full text-xs rounded-lg border-slate-300">
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Kode (UN/LOCODE)</label>
                                <input type="text" name="code" required placeholder="IDBLW" class="w-full text-xs rounded-lg border-slate-300 uppercase">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Tipe Lokasi</label>
                                <select name="type" required class="w-full text-xs rounded-lg border-slate-300">
                                    <option value="PORT">PORT (Pelabuhan Laut)</option>
                                    <option value="AIRPORT">AIRPORT (Bandar Udara)</option>
                                    <option value="WAREHOUSE">WAREHOUSE (Gudang Logistik)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Kota</label>
                                <input type="text" name="city" required placeholder="Medan" class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Negara & Kode</label>
                                <div class="flex gap-1">
                                    <input type="text" name="country" required placeholder="Indonesia" class="w-2/3 text-xs rounded-lg border-slate-300">
                                    <input type="text" name="country_code" required placeholder="ID" maxlength="3" class="w-1/3 text-xs rounded-lg border-slate-300 uppercase">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Latitude (Garis Lintang)</label>
                                <input type="text" name="latitude" required placeholder="3.784" class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Longitude (Garis Bujur)</label>
                                <input type="text" name="longitude" required placeholder="98.691" class="w-full text-xs rounded-lg border-slate-300">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-2 bg-[#1b1f3b] hover:bg-sky-600 text-white font-bold rounded-lg transition shadow-xs">
                            + Daftarkan Pelabuhan
                        </button>
                    </form>
                </div>

                {{-- Tabel Daftar Lokasi --}}
                <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-slate-200 bg-slate-50">
                        <h4 class="text-sm font-bold text-slate-900">Daftar Pelabuhan & Hub Global</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-100 text-slate-700 font-bold uppercase">
                                <tr>
                                    <th class="p-3">Kode</th>
                                    <th class="p-3">Nama Pelabuhan</th>
                                    <th class="p-3">Kota / Negara</th>
                                    <th class="p-3">Koordinat GPS</th>
                                    <th class="p-3">Tipe</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($locations as $loc)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="p-3 font-mono font-bold text-sky-600">{{ $loc->code }}</td>
                                        <td class="p-3 font-bold text-slate-900">{{ $loc->name }}</td>
                                        <td class="p-3 text-slate-700">{{ $loc->city }}, {{ $loc->country_code }}</td>
                                        <td class="p-3 font-mono text-[11px] text-slate-500">{{ $loc->latitude }}, {{ $loc->longitude }}</td>
                                        <td class="p-3"><span class="px-2 py-0.5 rounded bg-slate-100 font-mono text-[10px]">{{ $loc->type }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>