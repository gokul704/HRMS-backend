<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\OfferLetterController;
use App\Http\Controllers\Api\PayrollController;
use App\Http\Controllers\Api\DepartmentController;

// Test endpoint to fix database
Route::get('/test-db', function () {
    try {
        // Check database connection
        DB::connection()->getPdo();

        // Check if users exist
        $userCount = \App\Models\User::count();
        $deptCount = \App\Models\Department::count();

        if ($userCount == 0) {
            // Run seeders
            \Artisan::call('db:seed', ['--force' => true]);
            return response()->json([
                'success' => true,
                'message' => 'Database seeded successfully',
                'data' => [
                    'users_created' => \App\Models\User::count(),
                    'departments_created' => \App\Models\Department::count()
                ]
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Database already has data',
                'data' => [
                    'users' => $userCount,
                    'departments' => $deptCount
                ]
            ]);
        }
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ], 500);
    }
});

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::get('/check-auth', [AuthController::class, 'checkAuth']);

    // Department routes
    Route::apiResource('departments', DepartmentController::class);
    Route::get('/departments/{department}/statistics', [DepartmentController::class, 'statistics']);
    Route::get('/departments-with-employee-count', [DepartmentController::class, 'withEmployeeCount']);
    Route::patch('/departments/{department}/toggle-status', [DepartmentController::class, 'toggleStatus']);

    // Employee routes
    Route::apiResource('employees', EmployeeController::class);
    Route::patch('/employees/{employee}/complete-onboarding', [EmployeeController::class, 'completeOnboarding']);
    Route::get('/employees/statistics', [EmployeeController::class, 'statistics']);
    Route::get('/departments/{department}/employees', [EmployeeController::class, 'byDepartment']);

    // Offer Letter routes - HR only
    Route::middleware('role:hr')->group(function () {
        Route::apiResource('offer-letters', OfferLetterController::class);
        Route::patch('/offer-letters/{offerLetter}/send', [OfferLetterController::class, 'send']);
        Route::patch('/offer-letters/{offerLetter}/update-status', [OfferLetterController::class, 'updateStatus']);
        Route::get('/offer-letters/statistics', [OfferLetterController::class, 'statistics']);
        Route::get('/departments/{department}/offer-letters', [OfferLetterController::class, 'byDepartment']);
    });

    // Offer Letter view routes - Manager can view but not create
    Route::middleware('role:manager')->group(function () {
        Route::get('/offer-letters', [OfferLetterController::class, 'index']);
        Route::get('/offer-letters/{offerLetter}', [OfferLetterController::class, 'show']);
        Route::patch('/offer-letters/{offerLetter}/approve', [OfferLetterController::class, 'approve']);
    });

    // Payroll routes
    Route::apiResource('payrolls', PayrollController::class);
    Route::patch('/payrolls/{payroll}/mark-as-paid', [PayrollController::class, 'markAsPaid']);
    Route::patch('/payrolls/{payroll}/mark-as-failed', [PayrollController::class, 'markAsFailed']);
    Route::get('/payrolls/statistics', [PayrollController::class, 'statistics']);
    Route::get('/employees/{employee}/payrolls', [PayrollController::class, 'byEmployee']);
    Route::post('/payrolls/generate-bulk', [PayrollController::class, 'generateBulk']);

    // Role-based routes
    Route::middleware('role:hr,manager')->group(function () {
        // HR and Manager specific routes
        Route::get('/dashboard/statistics', function () {
            return response()->json([
                'success' => true,
                'data' => [
                    'employees' => \App\Models\Employee::count(),
                    'departments' => \App\Models\Department::count(),
                    'offer_letters' => \App\Models\OfferLetter::count(),
                    'payrolls' => \App\Models\Payroll::count(),
                ],
            ]);
        });
    });

    Route::middleware('role:manager')->group(function () {
        // Manager specific routes
        Route::get('/manager/approvals', function () {
            return response()->json([
                'success' => true,
                'data' => [
                    'pending_offers' => \App\Models\OfferLetter::where('status', 'draft')->count(),
                    'pending_payrolls' => \App\Models\Payroll::where('payment_status', 'pending')->count(),
                ],
            ]);
        });
    });

    Route::middleware('role:employee')->group(function () {
        // Employee specific routes
        Route::get('/employee/profile', function (Request $request) {
            $employee = $request->user()->employee;
            return response()->json([
                'success' => true,
                'data' => $employee->load(['department', 'payrolls']),
            ]);
        });

        Route::get('/employee/payrolls', function (Request $request) {
            $payrolls = $request->user()->employee->payrolls()->orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $payrolls,
            ]);
        });
    });
});
