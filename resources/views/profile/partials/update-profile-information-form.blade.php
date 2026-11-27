<section>
    <header class="flex items-center justify-between border-b border-slate-100 pb-4">
        <div>
            <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-sm border border-sky-200/60">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <span>Informasi Profil Pengguna</span>
            </h2>
            <p class="mt-1 text-xs text-slate-500">
                Perbarui nama lengkap dan alamat email akun resmi Anda pada platform CargoTrace.
            </p>
        </div>
        <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-mono font-bold bg-sky-50 text-sky-700 border border-sky-200">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            DATA UTAMA
        </span>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5 text-xs">
        @csrf
        @method('patch')

        {{-- 2 Kolom Lebar & Rapi untuk Nama dan Email --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            {{-- Kolom 1: Nama Lengkap --}}
            <div>
                <label for="name" class="block font-bold text-slate-700 mb-1.5 text-xs">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-user text-xs"></i>
                    </span>
                    <input id="name" 
                           name="name" 
                           type="text" 
                           class="w-full pl-10 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-900 shadow-2xs transition" 
                           value="{{ old('name', $user->name) }}" 
                           required 
                           autofocus 
                           autocomplete="name" />
                </div>
                <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
            </div>

            {{-- Kolom 2: Alamat Email --}}
            <div>
                <label for="email" class="block font-bold text-slate-700 mb-1.5 text-xs">Alamat Email Resmi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-envelope text-xs"></i>
                    </span>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           class="w-full pl-10 pr-3.5 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-slate-900 shadow-2xs transition" 
                           value="{{ old('email', $user->email) }}" 
                           required 
                           autocomplete="username" />
                </div>
                <x-input-error class="mt-1.5" :messages="$errors->get('email')" />
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs">
                <p>
                    Email Anda belum diverifikasi.
                    <button form="send-verification" class="underline text-sky-600 hover:text-sky-800 font-bold ml-1">
                        Kirim ulang email verifikasi
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-1.5 font-bold text-emerald-600">
                        Tautan verifikasi baru telah dikirim ke alamat email Anda.
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-4 pt-3 border-t border-slate-100">
            <button type="submit" class="px-6 py-2.5 bg-[#1b1f3b] hover:bg-sky-600 text-white font-bold rounded-xl text-xs shadow-md transition flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-check text-xs"></i>
                <span>Simpan Perubahan</span>
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs text-emerald-600 font-bold flex items-center gap-1.5"
                ><i class="fa-solid fa-circle-check"></i> Profil berhasil diperbarui.</p>
            @endif
        </div>
    </form>
</section>
