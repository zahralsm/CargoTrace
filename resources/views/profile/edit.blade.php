<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-user-gear text-sky-600"></i>
            <h2 class="font-extrabold text-lg text-slate-900 tracking-tight">
                Pengaturan Profil Pengguna
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 bg-white border border-slate-200/90 shadow-sm rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 bg-white border border-slate-200/90 shadow-sm rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 bg-white border border-slate-200/90 shadow-sm rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>