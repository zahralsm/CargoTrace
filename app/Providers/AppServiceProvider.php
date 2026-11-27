<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production' || request()->header('x-forwarded-proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Auto-bootstrap database & seed dummy data in production if empty
        if (config('app.env') === 'production') {
            try {
                if (!\Illuminate\Support\Facades\Schema::hasTable('users')) {
                    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                }
                if (\Illuminate\Support\Facades\Schema::hasTable('users') && \App\Models\User::count() === 0) {
                    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Auto database bootstrap notice: ' . $e->getMessage());
            }
        }
    }
}
