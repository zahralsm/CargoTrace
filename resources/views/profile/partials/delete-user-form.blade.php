<section class="space-y-3.5">
    <header class="border-b border-rose-100 pb-3">
        <h2 class="text-xs font-extrabold text-rose-800 uppercase tracking-wider flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
            <span>Zona Kritis: Hapus Akun</span>
        </h2>

        <p class="mt-1 text-[11px] text-slate-500 leading-relaxed">
            Setelah akun Anda dihapus, seluruh data manifes kargo, histori aktivitas, dan sesi akses akan dihapus secara permanen dari server.
        </p>
    </header>

    <div>
        <button
            type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="w-full px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold rounded-xl text-xs transition flex items-center justify-center gap-2 cursor-pointer shadow-2xs"
        >
            <i class="fa-solid fa-trash-can text-xs"></i>
            <span>Hapus Akun Pengguna</span>
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 text-xs text-left">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">
                        Konfirmasi Penghapusan Akun
                    </h2>
                    <p class="text-xs text-slate-500">Tindakan ini permanen dan tidak dapat dibatalkan.</p>
                </div>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed mt-3">
                Silakan masukkan kata sandi akun Anda untuk mengonfirmasi bahwa Anda benar-benar ingin menghapus akun ini secara permanen:
            </p>

            <div class="mt-4">
                <label for="password" class="block font-bold text-slate-700 mb-1.5 text-xs">Kata Sandi Akun</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock text-xs"></i>
                    </span>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="w-full pl-10 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 bg-white"
                        placeholder="Masukkan kata sandi untuk konfirmasi..."
                    />
                </div>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5" />
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer">
                    Batal
                </button>

                <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition cursor-pointer shadow-md">
                    Ya, Hapus Akun Secara Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>
