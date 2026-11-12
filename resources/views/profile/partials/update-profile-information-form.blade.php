<section>
    <header>
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
            <i class="fa-solid fa-id-card text-sky-600"></i>
            <span>Informasi Profil Pengguna</span>
        </h2>

        <p class="mt-1 text-xs text-slate-500">
            Perbarui nama lengkap dan alamat email akun Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-5 space-y-4 text-xs">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block font-bold text-slate-700 mb-1">Nama Lengkap</label>
            <input id="name" name="name" type="text" class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block font-bold text-slate-700 mb-1">Alamat Email</label>
            <input id="email" name="email" type="email" class="w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-xs mt-2 text-slate-800">
                        Email Anda belum diverifikasi.

                        <button form="send-verification" class="underline text-xs text-sky-600 hover:text-sky-800">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-emerald-600">
                            Tautan verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-4 py-2 bg-[#1b1f3b] hover:bg-sky-600 text-white font-bold rounded-lg text-xs shadow-xs transition">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs text-emerald-600 font-bold flex items-center gap-1"
                ><i class="fa-solid fa-check"></i> Berhasil disimpan.</p>
            @endif
        </div>
    </form>
</section>