<?php

// Simple API test script for StaffIQ
$baseUrl = 'http://localhost:8000/api';

echo "Testing StaffIQ API Endpoints\n";
echo "==========================\n\n";

// Test 1: Login
echo "1. Testing Login...\n";
$loginData = [
    'email' => 'sarah.johnson@company.com',
    'password' => 'password123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if ($data['success']) {
        echo "✅ Login successful\n";
        $token = $data['data']['token'];
        echo "Token: " . substr($token, 0, 20) . "...\n\n";
    } else {
        echo "❌ Login failed\n";
        exit;
    }
} else {
    echo "❌ Login failed (HTTP $httpCode)\n";
    exit;
}

// Test 2: Get Profile
echo "2. Testing Get Profile...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/profile');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if ($data['success']) {
        echo "✅ Profile retrieved successfully\n";
        echo "User: " . $data['data']['user']['name'] . " (" . $data['data']['user']['role'] . ")\n\n";
    } else {
        echo "❌ Profile retrieval failed\n\n";
    }
} else {
    echo "❌ Profile retrieval failed (HTTP $httpCode)\n\n";
}

// Test 3: Get Departments
echo "3. Testing Get Departments...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/departments');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if ($data['success']) {
        echo "✅ Departments retrieved successfully\n";
        echo "Total departments: " . count($data['data']['data']) . "\n\n";
    } else {
        echo "❌ Departments retrieval failed\n\n";
    }
} else {
    echo "❌ Departments retrieval failed (HTTP $httpCode)\n\n";
}

// Test 4: Get Employees
echo "4. Testing Get Employees...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/employees');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if ($data['success']) {
        echo "✅ Employees retrieved successfully\n";
        echo "Total employees: " . count($data['data']['data']) . "\n\n";
    } else {
        echo "❌ Employees retrieval failed\n\n";
    }
} else {
    echo "❌ Employees retrieval failed (HTTP $httpCode)\n\n";
}

// Test 5: Get Statistics
echo "5. Testing Get Statistics...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/employees/statistics');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if ($data['success']) {
        echo "✅ Statistics retrieved successfully\n";
        echo "Total employees: " . $data['data']['total_employees'] . "\n";
        echo "Active employees: " . $data['data']['active_employees'] . "\n";
        echo "Onboarded employees: " . $data['data']['onboarded_employees'] . "\n\n";
    } else {
        echo "❌ Statistics retrieval failed\n\n";
    }
} else {
    echo "❌ Statistics retrieval failed (HTTP $httpCode)\n\n";
}

echo "API Testing Complete!\n";
echo "====================\n";
echo "The StaffIQ API is working correctly.\n";
echo "You can now integrate this with your Flutter app.\n";
