<?php

// Database fix script for Railway
echo "Starting database fix...\n";

// Test database connection
try {
    $pdo = new PDO(
        'mysql:host=mysql.railway.internal;dbname=hrms_backend;port=3306',
        'root',
        'mheOQUJVaYXnjMDAnhZOqCmfRUPWvPWB'
    );
    echo "✅ Database connection successful!\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Check if tables exist
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "📋 Current tables: " . implode(', ', $tables) . "\n";

// Check if users exist
$userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
echo "👥 Current users: $userCount\n";

if ($userCount == 0) {
    echo "🔄 No users found. Running seeder...\n";

    // Run the seeder manually
    require_once 'vendor/autoload.php';

    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    // Run DepartmentSeeder
    echo "📊 Seeding departments...\n";
    $departmentSeeder = new \Database\Seeders\DepartmentSeeder();
    $departmentSeeder->run();

    // Run UserSeeder
    echo "👤 Seeding users...\n";
    $userSeeder = new \Database\Seeders\UserSeeder();
    $userSeeder->run();

    echo "✅ Seeding completed!\n";
} else {
    echo "✅ Users already exist!\n";
}

// Test a user
$stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE email = ?");
$stmt->execute(['gokul.kumar.dev@company.com']);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "✅ Test user found: {$user['name']} ({$user['email']}) - Role: {$user['role']}\n";
} else {
    echo "❌ Test user not found\n";
}

echo "🎉 Database fix completed!\n";
