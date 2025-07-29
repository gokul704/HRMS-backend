<?php

/**
 * Simple Laravel Startup Script for Railway
 * This script handles all the necessary Laravel setup steps
 */

echo "=== Laravel Startup Script ===\n";

// 1. Generate application key if not set
if (empty($_ENV['APP_KEY']) || $_ENV['APP_KEY'] === 'base64:') {
    echo "Generating application key...\n";
    passthru('php artisan key:generate --force');
} else {
    echo "Application key already set.\n";
}

// 2. Clear and cache configuration
echo "Caching configuration...\n";
passthru('php artisan config:clear');
passthru('php artisan config:cache');

// 3. Clear and cache routes
echo "Caching routes...\n";
passthru('php artisan route:clear');
passthru('php artisan route:cache');

// 4. Clear and cache views
echo "Caching views...\n";
passthru('php artisan view:clear');
passthru('php artisan view:cache');

// 5. Run migrations
echo "Running migrations...\n";
passthru('php artisan migrate --force');

echo "=== Startup Complete ===\n";
