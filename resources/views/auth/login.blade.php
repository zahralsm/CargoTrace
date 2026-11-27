<x-guest-layout>
    <!-- Status Sesi / Notifikasi -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 mb-3 border border-sky-100 shadow-xs">
            <i class="fa-solid fa-right-to-bracket text-lg"></i>
        </div>
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Masuk ke Portal</h2>
        <p class="text-xs text-slate-500 mt-1">Masukkan kredensial akun Anda untuk mengakses dashboard</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Alamat Email -->
        <div>
            <label for="email" class="block font-bold text-xs text-slate-700 mb-1.5">Alamat Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-regular fa-envelope text-xs"></i>
                </span>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       placeholder="nama@cargotrace.com"
                       class="w-full pl-10 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-900 transition shadow-2xs" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Kata Sandi dengan Animasi Reveal Column (Design Spells) -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block font-bold text-xs text-slate-700">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-[11px] text-sky-600 hover:text-sky-800 font-semibold transition" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <div x-data="{ showPass: false }" class="relative group/pass">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within/pass:text-sky-600 transition-colors duration-200">
                    <i class="fa-solid fa-lock text-xs"></i>
                </span>
                <input id="password" 
                       :type="showPass ? 'text' : 'password'" 
                       name="password" 
                       required 
                       placeholder="••••••••"
                       class="w-full pl-10 pr-11 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-900 transition-all duration-200 shadow-2xs font-sans" 
                       :class="showPass ? 'spell-anim-reveal tracking-normal font-medium' : 'spell-anim-mask tracking-widest'" />

                <!-- Tombol Reveal Animasi Column Design Spells -->
                <button type="button" 
                        @click="showPass = !showPass" 
                        title="Tampilkan / Sembunyikan Kata Sandi"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-sky-600 focus:outline-none transition-colors duration-200 group/btn cursor-pointer">
                    <div class="spell-eye-toggle relative w-7 h-7 rounded-lg flex items-center justify-center hover:bg-slate-100 group-hover/btn:text-sky-600 transition-all duration-200">
                        <!-- Icon Mata Terbuka -->
                        <svg x-show="showPass"
                             x-cloak
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-50 rotate-45"
                             x-transition:enter-end="opacity-100 scale-100 rotate-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 rotate-0"
                             x-transition:leave-end="opacity-0 scale-50 -rotate-45"
                             class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>

                        <!-- Icon Mata Tertutup / Slash -->
                        <svg x-show="!showPass"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-50 -rotate-45"
                             x-transition:enter-end="opacity-100 scale-100 rotate-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 rotate-0"
                             x-transition:leave-end="opacity-0 scale-50 rotate-45"
                             class="w-4 h-4 text-slate-400 group-hover/btn:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </div>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Ingat Saya -->
        <div class="flex items-center pt-1">
            <input id="remember_me" type="checkbox" checked class="w-4 h-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500 transition" name="remember">
            <label for="remember_me" class="ms-2 text-xs text-slate-600 font-medium">Ingat saya di perangkat ini</label>
        </div>

        <!-- Tombol Masuk -->
        <div class="pt-2">
            <button type="submit" class="w-full py-3 px-4 bg-[#1b1f3b] hover:bg-sky-600 text-white font-bold rounded-xl text-xs shadow-lg hover:shadow-sky-500/25 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer group">
                <span>Masuk ke Sistem</span>
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </form>
</x-guest-layout>