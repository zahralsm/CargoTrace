<section>
    <header>
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
            <i class="fa-solid fa-key text-sky-600"></i>
            <span>Perbarui Kata Sandi</span>
        </h2>

        <p class="mt-1 text-xs text-slate-500">
            Pastikan akun Anda menggunakan kata sandi yang aman untuk mencegah akses tidak sah.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-5 space-y-4 text-xs">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block font-bold text-slate-700 mb-1">Kata Sandi Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div>
            <label for="update_password_password" class="block font-bold text-slate-700 mb-1">Kata Sandi Baru</label>
            <input id="update_password_password" name="password" type="password" class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block font-bold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-4 py-2 bg-[#1b1f3b] hover:bg-sky-600 text-white font-bold rounded-lg text-xs shadow-xs transition">
                Perbarui Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs text-emerald-600 font-bold flex items-center gap-1"
                ><i class="fa-solid fa-check"></i> Kata sandi berhasil diperbarui.</p>
            @endif
        </div>
    </form>
</section>