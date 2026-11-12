<section class="space-y-4">
    <header>
        <h2 class="text-sm font-bold text-rose-800 uppercase tracking-wider flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
            <span>Hapus Akun Pengguna</span>
        </h2>

        <p class="mt-1 text-xs text-slate-500">
            Setelah akun Anda dihapus, semua data dan informasi yang terkait akan dihapus secara permanen dari basis data sistem.
        </p>
    </header>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-xs shadow-xs transition"
    >Hapus Akun Saya</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 text-xs">
            @csrf
            @method('delete')

            <h2 class="text-base font-extrabold text-slate-900">
                Apakah Anda yakin ingin menghapus akun ini?
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi akun Anda untuk mengonfirmasi penghapusan permanen.
            </p>

            <div class="mt-4">
                <label for="password" class="block font-bold text-slate-700 mb-1">Kata Sandi Akun</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full text-xs rounded-lg border-slate-300 focus:ring-rose-500 focus:border-rose-500"
                    placeholder="Masukkan kata sandi..."
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
            </div>

            <div class="mt-6 flex justify-end gap-2.5">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg text-xs transition">
                    Batal
                </button>

                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-xs transition">
                    Konfirmasi Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>