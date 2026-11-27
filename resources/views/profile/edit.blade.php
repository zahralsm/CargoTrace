<x-app-layout>
    {{-- PENGATURAN PROFIL PENGGUNA (TEMA EXECUTIVE PUTIH BERSIH) --}}
    <div class="bg-slate-100 text-slate-800 min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto space-y-6">

            {{-- 1. Kartu Informasi Profil Pengguna (Kolom Lebar, Rapi & Proporsional) --}}
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 hover:border-sky-300 transition-all duration-200">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- 2. Kartu Perbarui Kata Sandi (Kolom Lebar, Rapi & Proporsional) --}}
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 hover:border-sky-300 transition-all duration-200">
                @include('profile.partials.update-password-form')
            </div>

            {{-- 3. Kartu Zona Kritis / Hapus Akun --}}
            <div class="bg-white rounded-3xl border border-rose-200/90 shadow-sm p-6 sm:p-8">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>
