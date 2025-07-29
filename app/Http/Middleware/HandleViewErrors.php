<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use App\Helpers\ViewHelper;

class HandleViewErrors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Add view error handling to the response
        $response = $next($request);

        // If there's a view error, handle it gracefully
        if ($response->getStatusCode() === 500) {
            $content = $response->getContent();

            if (str_contains($content, 'View') && str_contains($content, 'not found')) {
                Log::warning('View not found error detected, returning error page');

                return response()->view('errors.view-not-found', [
                    'view' => 'Unknown',
                    'data' => []
                ], 500);
            }
        }

        return $response;
    }
}
