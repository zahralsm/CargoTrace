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
        <span class="text-[11px] font-mono text-slate-400">Min. 8 Karakter</span>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5 text-xs">
        @csrf
        @method('put')

        {{-- Baris 1: Kata Sandi Saat Ini --}}
        <div>
            <label for="update_password_current_password" class="block font-bold text-slate-700 mb-1.5 text-xs">Kata Sandi Saat Ini</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-lock text-xs"></i>
                </span>
                <input id="update_password_current_password" 
                       name="current_password" 
                       type="password" 
                       class="w-full pl-10 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-900 shadow-2xs transition" 
                       placeholder="Masukkan kata sandi aktif akun..." 
                       autocomplete="current-password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5" />
        </div>

        {{-- Baris 2: Kata Sandi Baru & Konfirmasi (2 Kolom Lebar & Proporsional) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="update_password_password" class="block font-bold text-slate-700 mb-1.5 text-xs">Kata Sandi Baru</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-key text-xs"></i>
                    </span>
                    <input id="update_password_password" 
                           name="password" 
                           type="password" 
                           class="w-full pl-10 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-900 shadow-2xs transition" 
                           placeholder="Minimal 8 karakter baru..." 
                           autocomplete="new-password" />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5" />
            </div>

            <div>
                <label for="update_password_password_confirmation" class="block font-bold text-slate-700 mb-1.5 text-xs">Konfirmasi Kata Sandi Baru</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-shield-halved text-xs"></i>
                    </span>
                    <input id="update_password_password_confirmation" 
                           name="password_confirmation" 
                           type="password" 
                           class="w-full pl-10 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-900 shadow-2xs transition" 
                           placeholder="Ulangi kata sandi baru..." 
                           autocomplete="new-password" />
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
