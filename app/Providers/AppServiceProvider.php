<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // Completely bypass database connection during startup
        // This will allow the application to start without database
        try {
            // Only test connection if we're not in a web request
            if (!request()->is('health')) {
                DB::connection()->getPdo();
            }
        } catch (\Exception $e) {
            Log::warning('Database connection failed: ' . $e->getMessage());
            // Continue without database connection
        }
    }
}
