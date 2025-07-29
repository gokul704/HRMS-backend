<?php

/**
 * Comprehensive Database Diagnostic Script for Railway
 * This script will help us understand exactly what's happening with the database connection
 */

echo "=== Railway Database Diagnostic ===\n\n";

// 1. Check all environment variables
echo "1. Environment Variables:\n";
echo "DATABASE_URL: " . (getenv('DATABASE_URL') ?: 'NOT SET') . "\n";
echo "DB_URL: " . (getenv('DB_URL') ?: 'NOT SET') . "\n";
echo "DB_CONNECTION: " . (getenv('DB_CONNECTION') ?: 'NOT SET') . "\n";
echo "DB_HOST: " . (getenv('DB_HOST') ?: 'NOT SET') . "\n";
echo "DB_PORT: " . (getenv('DB_PORT') ?: 'NOT SET') . "\n";
echo "DB_DATABASE: " . (getenv('DB_DATABASE') ?: 'NOT SET') . "\n";
echo "DB_USERNAME: " . (getenv('DB_USERNAME') ?: 'NOT SET') . "\n";
echo "DB_PASSWORD: " . (getenv('DB_PASSWORD') ? substr(getenv('DB_PASSWORD'), 0, 10) . '...' : 'NOT SET') . "\n\n";

// 2. Check Laravel database configuration
echo "2. Laravel Database Configuration:\n";
try {
    $config = config('database');
    echo "Default connection: " . ($config['default'] ?? 'NOT SET') . "\n";

    if (isset($config['connections']['mysql'])) {
        $mysql = $config['connections']['mysql'];
        echo "MySQL Host: " . ($mysql['host'] ?? 'NOT SET') . "\n";
        echo "MySQL Port: " . ($mysql['port'] ?? 'NOT SET') . "\n";
        echo "MySQL Database: " . ($mysql['database'] ?? 'NOT SET') . "\n";
        echo "MySQL Username: " . ($mysql['username'] ?? 'NOT SET') . "\n";
        echo "MySQL Password: " . (isset($mysql['password']) ? substr($mysql['password'], 0, 10) . '...' : 'NOT SET') . "\n";
    } else {
        echo "MySQL connection not configured\n";
    }
} catch (Exception $e) {
    echo "Error reading config: " . $e->getMessage() . "\n";
}
echo "\n";

// 3. Test database connection
echo "3. Database Connection Test:\n";
try {
    $pdo = DB::connection()->getPdo();
    echo "✅ Database connection successful!\n";
    echo "Database version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
    echo "Connection info: " . $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS) . "\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "Error code: " . $e->getCode() . "\n";
}
echo "\n";

// 4. Test if we can connect to MySQL directly
echo "4. Direct MySQL Connection Test:\n";
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$database = getenv('DB_DATABASE') ?: 'laravel';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

echo "Attempting to connect to: $username@$host:$port/$database\n";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "✅ Direct MySQL connection successful!\n";
} catch (PDOException $e) {
    echo "❌ Direct MySQL connection failed: " . $e->getMessage() . "\n";
    echo "Error code: " . $e->getCode() . "\n";
}
echo "\n";

// 5. Check if database exists and is accessible
echo "5. Database Accessibility Test:\n";
try {
    $pdo = DB::connection()->getPdo();
    $stmt = $pdo->query("SELECT DATABASE() as current_db");
    $result = $stmt->fetch();
    echo "Current database: " . ($result['current_db'] ?? 'UNKNOWN') . "\n";

    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Available tables: " . implode(', ', $tables) . "\n";
} catch (Exception $e) {
    echo "❌ Cannot access database: " . $e->getMessage() . "\n";
}
echo "\n";

echo "=== Diagnostic Complete ===\n";
