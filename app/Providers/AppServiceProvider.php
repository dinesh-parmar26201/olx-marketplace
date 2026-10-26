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
        // Auto-initialize demo database when running on serverless (e.g. Vercel) with SQLite
        if (!app()->runningInConsole() && config('database.default') === 'sqlite') {
            try {
                if (!\Illuminate\Support\Facades\Schema::hasTable('categories')) {
                    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                }
            } catch (\Throwable $e) {
                // Ignore if database connection cannot be initialized
            }
        }
    }
}
