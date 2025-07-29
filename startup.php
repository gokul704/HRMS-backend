<?php

/**
 * Simple Laravel Startup Script for Railway
 * This script handles all the necessary Laravel setup steps
 */

echo "=== Laravel Startup Script ===\n";

// Debug environment variables
echo "=== Environment Variables ===\n";
echo "DB_CONNECTION: " . ($_ENV['DB_CONNECTION'] ?? 'NOT SET') . "\n";
echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'NOT SET') . "\n";
echo "DB_PORT: " . ($_ENV['DB_PORT'] ?? 'NOT SET') . "\n";
echo "DB_DATABASE: " . ($_ENV['DB_DATABASE'] ?? 'NOT SET') . "\n";
echo "DB_USERNAME: " . ($_ENV['DB_USERNAME'] ?? 'NOT SET') . "\n";
echo "DB_PASSWORD: " . (isset($_ENV['DB_PASSWORD']) ? substr($_ENV['DB_PASSWORD'], 0, 10) . '...' : 'NOT SET') . "\n";
echo "MYSQL_URL: " . ($_ENV['MYSQL_URL'] ?? 'NOT SET') . "\n";
echo "MYSQLHOST: " . ($_ENV['MYSQLHOST'] ?? 'NOT SET') . "\n";
echo "MYSQLPORT: " . ($_ENV['MYSQLPORT'] ?? 'NOT SET') . "\n";
echo "MYSQLDATABASE: " . ($_ENV['MYSQLDATABASE'] ?? 'NOT SET') . "\n";
echo "MYSQLUSER: " . ($_ENV['MYSQLUSER'] ?? 'NOT SET') . "\n";
echo "MYSQLPASSWORD: " . (isset($_ENV['MYSQLPASSWORD']) ? substr($_ENV['MYSQLPASSWORD'], 0, 10) . '...' : 'NOT SET') . "\n";
echo "RAILWAY_PRIVATE_DOMAIN: " . ($_ENV['RAILWAY_PRIVATE_DOMAIN'] ?? 'NOT SET') . "\n";
echo "RAILWAY_TCP_PROXY_DOMAIN: " . ($_ENV['RAILWAY_TCP_PROXY_DOMAIN'] ?? 'NOT SET') . "\n";
echo "RAILWAY_TCP_PROXY_PORT: " . ($_ENV['RAILWAY_TCP_PROXY_PORT'] ?? 'NOT SET') . "\n";
echo "==========================\n\n";

// Check if we're in Railway environment
$isRailway = !empty($_ENV['RAILWAY_PRIVATE_DOMAIN']);
echo "Railway Environment Detected: " . ($isRailway ? 'YES' : 'NO') . "\n\n";

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

// 5. Test database connection before migrations
echo "Testing database connection...\n";
$returnCode = null;
passthru('php artisan tinker --execute="try { DB::connection()->getPdo(); echo \"Database connection successful!\"; } catch (Exception \$e) { echo \"Database connection failed: \" . \$e->getMessage(); }" 2>&1', $returnCode);
echo "Database test result: $returnCode\n";

// 6. Run migrations only if database is accessible
if ($returnCode === 0) {
    echo "Running migrations...\n";
    passthru('php artisan migrate --force');
} else {
    echo "Skipping migrations due to database connection failure.\n";
    echo "This is expected if Railway template variables are not resolved yet.\n";
}

echo "=== Startup Complete ===\n";
