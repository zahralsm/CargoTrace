<x-app-layout>
    {{-- KANVAS PUSAT KENDALI PROFIL PENGGUNA (TEMA EXECUTIVE BENTO PUTIH BERSIH) --}}
    <div class="bg-slate-100 text-slate-800 min-h-screen py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[1400px] mx-auto space-y-6">

            {{-- 1. PITA TELEMETRI IDENTITAS PENGGUNA --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs px-6 py-4 flex flex-wrap items-center justify-between gap-4">
                {{-- Kiri: Avatar & Informasi Identitas Pengguna --}}
                <div class="flex items-center gap-4">
                    <div class="w-13 h-13 rounded-2xl bg-[#1b1f3b] text-sky-400 flex items-center justify-center text-2xl font-black shadow-xs">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h1 class="text-lg font-black text-slate-900 tracking-tight">{{ $user->name }}</h1>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                AKUN AKTIF
                            </span>
                        </div>
                        <div class="text-xs text-slate-500 font-mono flex flex-wrap items-center gap-3 mt-1">
                            <span><i class="fa-regular fa-envelope mr-1 text-slate-400"></i> {{ $user->email }}</span>
                            <span class="text-slate-300">•</span>
                            <span>ID Pengguna: <strong class="text-slate-700">#USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</strong></span>
                            <span class="text-slate-300">•</span>
                            <span>Terdaftar: <strong class="text-slate-700">{{ $user->created_at->format('d M Y') }}</strong></span>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Lencana Peran & Navigasi Cepat Dashboard --}}
                <div class="flex items-center gap-3">
                    @php
                        $roleColors = [
                            'admin' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'label' => 'MASTER ADMINISTRATOR', 'icon' => 'fa-shield-halved'],
                            'operator' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'label' => 'OPERATOR LOGISTIK', 'icon' => 'fa-anchor'],
                            'customer' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'label' => 'PELANGGAN KARGO', 'icon' => 'fa-box-open']
                        ];
                        $roleInfo = $roleColors[$user->role] ?? $roleColors['customer'];
                    @endphp
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-mono font-extrabold {{ $roleInfo['bg'] }} {{ $roleInfo['text'] }} border {{ $roleInfo['border'] }} shadow-2xs">
                        <i class="fa-solid {{ $roleInfo['icon'] }}"></i>
                        <span>{{ $roleInfo['label'] }}</span>
                    </span>
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-left text-[11px]"></i>
                        <span>Ke Dashboard</span>
                    </a>
                </div>
            </div>

            {{-- 2. GRID BENTO 2 KOLOM (12-KOLOM LUAS, RAPI & PROPORSIONAL) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- SISI KIRI (4 Kolom): OTORITAS SISTEM & ZONA BAHAYA --}}
                <div class="lg:col-span-4 space-y-6">
                    
                    {{-- Kartu Otoritas & Keamanan --}}
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-key text-sky-600"></i>
                                <span>Otoritas & Hak Akses</span>
                            </h3>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-sky-50 text-sky-700 font-bold border border-sky-200">TERVERIFIKASI</span>
                        </div>

                        <div class="space-y-3 text-xs">
                            <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200/80 space-y-1">
                                <div class="text-[10px] font-mono font-bold text-slate-400 uppercase">Tingkat Hak Akses</div>
                                <div class="font-extrabold text-slate-800 text-sm">
                                    {{ $user->role === 'admin' ? 'Super Administrator' : ($user->role === 'operator' ? 'Logistics Operator' : 'Client / Pelanggan') }}
                                </div>
                                <p class="text-[11px] text-slate-500 leading-relaxed mt-1">
                                    {{ $user->role === 'admin' ? 'Akses konfigurasi carrier maskapai, lokasi pelabuhan, kelola seluruh data muatan, dan audit keamanan sistem.' : ($user->role === 'operator' ? 'Akses manajemen pergerakan armada kapal kargo harian, entri muatan kargo baru, dan pembaruan telemetri AIS.' : 'Akses pemantauan pelacakan kontainer dan estimasi waktu tiba (ETA) kargo khusus milik Anda.') }}
                                </p>
                            </div>

                            <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200/80 space-y-2">
                                <div class="text-[10px] font-mono font-bold text-slate-400 uppercase">Spesifikasi Keamanan Akun</div>
                                <div class="space-y-1.5 text-[11px]">
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-600">Enkripsi Kata Sandi:</span>
                                        <span class="font-mono font-bold text-slate-800">Bcrypt (Cost 12)</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-600">Proteksi Akses IDOR:</span>
                                        <span class="font-mono font-bold text-emerald-600">Aktif (100% Aman)</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-600">Autentikasi REST API:</span>
                                        <span class="font-mono font-bold text-sky-600">Sanctum Token</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kartu Zona Bahaya (Hapus Akun Pengguna) --}}
                    <div class="bg-white rounded-3xl border border-rose-200/90 shadow-sm p-6">
                        @include('profile.partials.delete-user-form')
                    </div>

                </div>

                {{-- SISI KANAN (8 Kolom): FORMULIR PROFIL & KATA SANDI (LUAS, RAPI & KOLOM PROPORSIONAL) --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- Form Informasi Profil --}}
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-7 hover:border-sky-300 transition">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    {{-- Form Perbarui Kata Sandi --}}
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-7 hover:border-sky-300 transition">
                        @include('profile.partials.update-password-form')
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
