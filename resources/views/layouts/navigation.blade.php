<nav x-data="{ open: false }" class="bg-[#1b1f3b] border-b border-[#292f54] text-white">
    <!-- Menu Navigasi Utama -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo CargoTrace -->
                <div class="shrink-0 flex items-center gap-2.5">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-lg bg-sky-500 flex items-center justify-center text-white shadow-md group-hover:bg-sky-400 transition">
                            <i class="fa-solid fa-anchor text-lg"></i>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight text-white">CargoTrace</span>
                    </a>
                </div>

                <!-- Tautan Menu Navigasi -->
                <div class="hidden sm:flex space-x-2">
                    <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-md text-xs font-bold transition {{ request()->routeIs('dashboard') ? 'bg-[#292f54] text-sky-400' : 'text-slate-300 hover:bg-[#252b4d] hover:text-white' }}">
                        <i class="fa-solid fa-chart-pie mr-1.5"></i> Dashboard
                    </a>
                    <a href="{{ route('dashboard') }}#manifest-table" class="px-3.5 py-2 rounded-md text-xs font-bold text-slate-300 hover:bg-[#252b4d] hover:text-white transition">
                        <i class="fa-solid fa-box mr-1.5"></i> Daftar Kargo
                    </a>
                    <a href="{{ route('dashboard') }}#manifest-table" class="px-3.5 py-2 rounded-md text-xs font-bold text-slate-300 hover:bg-[#252b4d] hover:text-white transition">
                        <i class="fa-solid fa-ship mr-1.5"></i> Maskapai Kapal
                    </a>
                    <a href="{{ route('dashboard') }}#manifest-table" class="px-3.5 py-2 rounded-md text-xs font-bold text-slate-300 hover:bg-[#252b4d] hover:text-white transition">
                        <i class="fa-solid fa-location-dot mr-1.5"></i> Pelabuhan
                    </a>

                    {{-- Tombol Konsol Admin Khusus Administrator (Slide 05) --}}
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.index') }}" class="px-3.5 py-2 rounded-md text-xs font-bold transition {{ request()->routeIs('admin.*') ? 'bg-[#292f54] text-sky-400 ring-1 ring-sky-400' : 'text-purple-300 hover:bg-[#252b4d] hover:text-white' }}">
                            <i class="fa-solid fa-screwdriver-wrench mr-1.5"></i> Konsol Admin
                        </a>
                    @endif
                </div>
            </div>

            <!-- Bagian Kanan: Lencana Peran & Menu Profil Pengguna -->
            <div class="hidden sm:flex sm:items-center sm:gap-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase {{ Auth::user()->role === 'admin' ? 'bg-purple-900/80 text-purple-300 border border-purple-600' : (Auth::user()->role === 'operator' ? 'bg-amber-900/80 text-amber-300 border border-amber-600' : 'bg-emerald-900/80 text-emerald-300 border border-emerald-600') }}">
                    <i class="fa-solid fa-shield-halved mr-1"></i> 
                    {{ Auth::user()->role === 'admin' ? 'ADMINISTRATOR' : (Auth::user()->role === 'operator' ? 'OPERATOR LOGISTIK' : 'PELANGGAN') }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-bold bg-[#292f54] text-slate-200 hover:text-white hover:bg-[#343b66] transition">
                            <div class="w-6 h-6 rounded-full bg-sky-600 flex items-center justify-center text-[10px] text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>{{ Auth::user()->name }}</div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="fa-regular fa-user mr-1.5"></i> Profil Saya
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="fa-solid fa-arrow-right-from-bracket mr-1.5"></i> Keluar Akun
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Tombol Hamburger (Khusus Layar HP / Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-md text-slate-400 hover:text-white hover:bg-[#252b4d]">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Responsif (Tampilan Layar HP) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#161a33] border-b border-[#292f54]">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('dashboard') }}" class="block py-2 text-sm font-bold text-sky-400">Dashboard</a>
            <a href="{{ route('dashboard') }}#manifest-table" class="block py-2 text-sm font-medium text-slate-300">Daftar Kargo</a>
            <a href="{{ route('dashboard') }}#manifest-table" class="block py-2 text-sm font-medium text-slate-300">Maskapai Kapal</a>
            <a href="{{ route('dashboard') }}#manifest-table" class="block py-2 text-sm font-medium text-slate-300">Pelabuhan</a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.index') }}" class="block py-2 text-sm font-bold text-purple-300">Konsol Admin</a>
            @endif
        </div>
    </div>
</nav>