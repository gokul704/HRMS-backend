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
        // Handle database connection failures gracefully
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            Log::warning('Database connection failed: ' . $e->getMessage());
            // Continue without database connection
        }
    }
}
