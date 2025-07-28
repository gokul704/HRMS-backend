<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class HandleDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip database check for health check route
        if ($request->is('health')) {
            return $next($request);
        }

        try {
            // Test database connection
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            Log::warning('Database connection failed in middleware: ' . $e->getMessage());

            // For API routes, return JSON error
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Database connection unavailable',
                    'message' => 'The application is running but database is not accessible',
                    'status' => 'partial'
                ], 503);
            }

            // For web routes, show a maintenance page
            return response()->view('errors.database', [
                'message' => 'Database connection is currently unavailable. Please try again later.'
            ], 503);
        }

        return $next($request);
    }
}
