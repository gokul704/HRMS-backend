<?php

/**
 * Simple Database Test Script
 * This script tests database connection without causing 500 errors
 */

echo "=== Simple Database Test ===\n";

// Test 1: Check environment variables
echo "1. Environment Variables:\n";
$env_vars = [
    'DATABASE_URL',
    'DB_URL',
    'DB_CONNECTION',
    'DB_HOST',
    'DB_PORT',
    'DB_DATABASE',
    'DB_USERNAME',
    'DB_PASSWORD'
];

foreach ($env_vars as $var) {
    $value = getenv($var);
    if ($value) {
        if (strpos($var, 'PASSWORD') !== false) {
            echo "$var: " . substr($value, 0, 10) . "...\n";
        } else {
            echo "$var: $value\n";
        }
    } else {
        echo "$var: NOT SET\n";
    }
}

echo "\n2. Testing direct MySQL connection:\n";

// Get connection details
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$database = getenv('DB_DATABASE') ?: 'laravel';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

echo "Attempting connection to: $username@$host:$port/$database\n";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "✅ Direct MySQL connection successful!\n";
    echo "Database version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
} catch (PDOException $e) {
    echo "❌ Direct MySQL connection failed: " . $e->getMessage() . "\n";
    echo "Error code: " . $e->getCode() . "\n";
}

echo "\n=== Test Complete ===\n";
