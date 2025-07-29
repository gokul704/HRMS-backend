<?php

/**
 * Parse Railway DATABASE_URL and extract individual components
 * This script helps debug database connection issues on Railway
 */

$databaseUrl = $_ENV['DATABASE_URL'] ?? getenv('DATABASE_URL');

if (!$databaseUrl) {
    echo "DATABASE_URL not found in environment variables\n";
    exit(1);
}

echo "Original DATABASE_URL: " . $databaseUrl . "\n\n";

// Parse the URL
$parsed = parse_url($databaseUrl);

if ($parsed === false) {
    echo "Failed to parse DATABASE_URL\n";
    exit(1);
}

echo "Parsed components:\n";
echo "Scheme: " . ($parsed['scheme'] ?? 'N/A') . "\n";
echo "Host: " . ($parsed['host'] ?? 'N/A') . "\n";
echo "Port: " . ($parsed['port'] ?? 'N/A') . "\n";
echo "User: " . ($parsed['user'] ?? 'N/A') . "\n";
echo "Password: " . (isset($parsed['pass']) ? '***' : 'N/A') . "\n";
echo "Database: " . (ltrim($parsed['path'] ?? '', '/')) . "\n\n";

echo "Environment variables to set:\n";
echo "DB_CONNECTION=mysql\n";
echo "DB_HOST=" . ($parsed['host'] ?? '') . "\n";
echo "DB_PORT=" . ($parsed['port'] ?? '3306') . "\n";
echo "DB_DATABASE=" . (ltrim($parsed['path'] ?? '', '/')) . "\n";
echo "DB_USERNAME=" . ($parsed['user'] ?? '') . "\n";
echo "DB_PASSWORD=" . ($parsed['pass'] ?? '') . "\n";
