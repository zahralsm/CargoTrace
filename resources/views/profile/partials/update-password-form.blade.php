<section>
    <header class="flex items-center justify-between border-b border-slate-100 pb-4">
        <div>
            <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-sm border border-sky-200/60">
                    <i class="fa-solid fa-key"></i>
                </div>
                <span>Perbarui Kata Sandi</span>
            </h2>
            <p class="mt-1 text-xs text-slate-500">
                Gunakan kombinasi minimal 8 karakter dengan huruf kapital, angka, dan simbol untuk keamanan akun maksimal.
            </p>
        </div>
        <span class="text-[11px] text-slate-400">Min. 8 Karakter</span>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5 text-xs">
        @csrf
        @method('put')

        {{-- 3 Kolom Sejajar Proporsional: Kata Sandi Saat Ini, Kata Sandi Baru, Konfirmasi --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            {{-- Kolom 1: Kata Sandi Saat Ini --}}
            <div>
                <label for="update_password_current_password" class="block font-bold text-slate-700 mb-1.5 text-xs">Kata Sandi Saat Ini</label>
                <div x-data="{ showPass: false }" class="relative group/pass">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within/pass:text-sky-600 transition-colors duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input id="update_password_current_password" 
                           name="current_password" 
                           :type="showPass ? 'text' : 'password'" 
                           class="w-full pl-10 pr-11 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-900 shadow-2xs transition-all duration-200 font-sans" 
                           :class="showPass ? 'spell-anim-reveal tracking-normal font-medium' : 'spell-anim-mask tracking-widest'"
                           placeholder="Kata sandi aktif..." 
                           autocomplete="current-password" />

                    <!-- Tombol Reveal Animasi Column Design Spells -->
                    <button type="button" 
                            @click="showPass = !showPass" 
                            title="Tampilkan / Sembunyikan Kata Sandi"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-sky-600 focus:outline-none transition-colors duration-200 group/btn cursor-pointer">
                        <div class="spell-eye-toggle relative w-7 h-7 rounded-lg flex items-center justify-center hover:bg-slate-100 group-hover/btn:text-sky-600 transition-all duration-200">
                            <svg x-show="showPass" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-50 rotate-45" x-transition:enter-end="opacity-100 scale-100 rotate-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 rotate-0" x-transition:leave-end="opacity-0 scale-50 -rotate-45" class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="!showPass" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-50 -rotate-45" x-transition:enter-end="opacity-100 scale-100 rotate-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 rotate-0" x-transition:leave-end="opacity-0 scale-50 rotate-45" class="w-4 h-4 text-slate-400 group-hover/btn:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </div>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5" />
            </div>

            {{-- Kolom 2: Kata Sandi Baru --}}
            <div>
                <label for="update_password_password" class="block font-bold text-slate-700 mb-1.5 text-xs">Kata Sandi Baru</label>
                <div x-data="{ showPass: false }" class="relative group/pass">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within/pass:text-sky-600 transition-colors duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </span>
                    <input id="update_password_password" 
                           name="password" 
                           :type="showPass ? 'text' : 'password'" 
                           class="w-full pl-10 pr-11 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-900 shadow-2xs transition-all duration-200 font-sans" 
                           :class="showPass ? 'spell-anim-reveal tracking-normal font-medium' : 'spell-anim-mask tracking-widest'"
                           placeholder="Minimal 8 karakter baru..." 
                           autocomplete="new-password" />

                    <!-- Tombol Reveal Animasi Column Design Spells -->
                    <button type="button" 
                            @click="showPass = !showPass" 
                            title="Tampilkan / Sembunyikan Kata Sandi"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-sky-600 focus:outline-none transition-colors duration-200 group/btn cursor-pointer">
                        <div class="spell-eye-toggle relative w-7 h-7 rounded-lg flex items-center justify-center hover:bg-slate-100 group-hover/btn:text-sky-600 transition-all duration-200">
                            <svg x-show="showPass" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-50 rotate-45" x-transition:enter-end="opacity-100 scale-100 rotate-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 rotate-0" x-transition:leave-end="opacity-0 scale-50 -rotate-45" class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="!showPass" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-50 -rotate-45" x-transition:enter-end="opacity-100 scale-100 rotate-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 rotate-0" x-transition:leave-end="opacity-0 scale-50 rotate-45" class="w-4 h-4 text-slate-400 group-hover/btn:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </div>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5" />
            </div>

            {{-- Kolom 3: Konfirmasi Kata Sandi Baru --}}
            <div>
                <label for="update_password_password_confirmation" class="block font-bold text-slate-700 mb-1.5 text-xs">Konfirmasi Kata Sandi Baru</label>
                <div x-data="{ showPass: false }" class="relative group/pass">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within/pass:text-sky-600 transition-colors duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </span>
                    <input id="update_password_password_confirmation" 
                           name="password_confirmation" 
                           :type="showPass ? 'text' : 'password'" 
                           class="w-full pl-10 pr-11 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-900 shadow-2xs transition-all duration-200 font-sans" 
                           :class="showPass ? 'spell-anim-reveal tracking-normal font-medium' : 'spell-anim-mask tracking-widest'"
                           placeholder="Ulangi kata sandi baru..." 
                           autocomplete="new-password" />

                    <!-- Tombol Reveal Animasi Column Design Spells -->
                    <button type="button" 
                            @click="showPass = !showPass" 
                            title="Tampilkan / Sembunyikan Kata Sandi"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-sky-600 focus:outline-none transition-colors duration-200 group/btn cursor-pointer">
                        <div class="spell-eye-toggle relative w-7 h-7 rounded-lg flex items-center justify-center hover:bg-slate-100 group-hover/btn:text-sky-600 transition-all duration-200">
                            <svg x-show="showPass" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-50 rotate-45" x-transition:enter-end="opacity-100 scale-100 rotate-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 rotate-0" x-transition:leave-end="opacity-0 scale-50 -rotate-45" class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="!showPass" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-50 -rotate-45" x-transition:enter-end="opacity-100 scale-100 rotate-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 rotate-0" x-transition:leave-end="opacity-0 scale-50 rotate-45" class="w-4 h-4 text-slate-400 group-hover/btn:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </div>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-3 border-t border-slate-100">
            <button type="submit" class="px-6 py-2.5 bg-[#1b1f3b] hover:bg-sky-600 text-white font-bold rounded-xl text-xs shadow-md transition flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-key text-xs"></i>
                <span>Perbarui Kata Sandi</span>
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs text-emerald-600 font-bold flex items-center gap-1.5"
                ><i class="fa-solid fa-circle-check"></i> Kata sandi berhasil diperbarui.</p>
            @endif
        </div>
    </form>
</section>
