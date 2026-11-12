<x-guest-layout>
    <!-- Status Sesi -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-5 text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#1b1f3b] text-sky-400 mb-2 shadow-md">
            <i class="fa-solid fa-anchor text-xl"></i>
        </div>
        <h2 class="text-xl font-extrabold text-slate-900">Masuk ke CargoTrace</h2>
        <p class="text-xs text-slate-500 mt-0.5">Sistem Pemantauan Logistik & Pelacakan Kargo Maritim</p>
    </div>

    {{-- Kartu Bantuan Akun Uji Coba UAS --}}
    <div class="mb-5 p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 space-y-1">
        <div class="font-bold text-slate-800 flex items-center gap-1.5 text-[11px] uppercase tracking-wider">
            <i class="fa-solid fa-circle-info text-sky-600"></i>
            <span>Akun Uji Coba (Password: password)</span>
        </div>
        <div class="grid grid-cols-3 gap-1 pt-1 text-[11px] font-mono">
            <div class="p-1 rounded bg-white border border-slate-200 text-center cursor-pointer hover:bg-sky-50" onclick="document.getElementById('email').value='admin@cargotrace.com'; document.getElementById('password').value='password';">
                <span class="block font-bold text-purple-700">Admin</span>
                <span class="text-[9px] text-slate-400">admin@</span>
            </div>
            <div class="p-1 rounded bg-white border border-slate-200 text-center cursor-pointer hover:bg-sky-50" onclick="document.getElementById('email').value='operator@cargotrace.com'; document.getElementById('password').value='password';">
                <span class="block font-bold text-amber-700">Operator</span>
                <span class="text-[9px] text-slate-400">operator@</span>
            </div>
            <div class="p-1 rounded bg-white border border-slate-200 text-center cursor-pointer hover:bg-sky-50" onclick="document.getElementById('email').value='customer@cargotrace.com'; document.getElementById('password').value='password';">
                <span class="block font-bold text-emerald-700">Customer</span>
                <span class="text-[9px] text-slate-400">customer@</span>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Alamat Email -->
        <div>
            <label for="email" class="block font-bold text-xs text-slate-700 mb-1">Alamat Email</label>
            <x-text-input id="email" class="block w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Masukkan alamat email akun..." />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Kata Sandi -->
        <div>
            <label for="password" class="block font-bold text-xs text-slate-700 mb-1">Kata Sandi</label>
            <x-text-input id="password" class="block w-full text-xs rounded-lg border-slate-300 focus:ring-sky-500 focus:border-sky-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="Masukkan kata sandi..." />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Ingat Saya -->
        <div class="flex items-center justify-between text-xs">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-sky-600 shadow-xs focus:ring-sky-500" name="remember">
                <span class="ms-2 text-slate-600">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-slate-500 hover:text-slate-800" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <div>
            <button type="submit" class="w-full py-2.5 bg-[#1b1f3b] hover:bg-sky-600 text-white font-bold rounded-lg text-xs shadow-md transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                <span>Masuk ke Sistem</span>
            </button>
        </div>
    </form>
</x-guest-layout>