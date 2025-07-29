<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DepartmentController;
use App\Http\Controllers\Web\EmployeeController;
use App\Http\Controllers\Web\OfferLetterController;
use App\Http\Controllers\Web\PayrollController;
use App\Http\Controllers\Web\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Health check route
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'HRMS Application is running',
        'timestamp' => now(),
        'environment' => app()->environment(),
        'database' => config('database.default')
    ]);
});

// Database test route
Route::get('/test-db', function () {
    try {
        $pdo = DB::connection()->getPdo();
        return response()->json([
            'status' => 'success',
            'message' => 'Database connection successful',
            'database' => $pdo->getAttribute(PDO::ATTR_SERVER_VERSION),
            'connection' => config('database.default'),
            'host' => config('database.connections.mysql.host'),
            'database_name' => config('database.connections.mysql.database'),
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database connection failed',
            'error' => $e->getMessage(),
            'connection' => config('database.default'),
            'host' => config('database.connections.mysql.host'),
            'database_name' => config('database.connections.mysql.database'),
        ], 500);
    }
});

// Comprehensive database diagnostic route
Route::get('/diagnose-db', function () {
    ob_start();
    include base_path('diagnose-db.php');
    $output = ob_get_clean();

    return response()->json([
        'status' => 'diagnostic_complete',
        'output' => $output
    ]);
});

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Department routes
    Route::resource('departments', DepartmentController::class)->names([
        'index' => 'web.departments.index',
        'create' => 'web.departments.create',
        'store' => 'web.departments.store',
        'show' => 'web.departments.show',
        'edit' => 'web.departments.edit',
        'update' => 'web.departments.update',
        'destroy' => 'web.departments.destroy',
    ]);
    Route::get('/departments/{department}/statistics', [DepartmentController::class, 'statistics'])->name('web.departments.statistics');
    Route::patch('/departments/{department}/toggle-status', [DepartmentController::class, 'toggleStatus'])->name('web.departments.toggle-status');

    // Employee routes
    Route::resource('employees', EmployeeController::class)->names([
        'index' => 'web.employees.index',
        'create' => 'web.employees.create',
        'store' => 'web.employees.store',
        'show' => 'web.employees.show',
        'edit' => 'web.employees.edit',
        'update' => 'web.employees.update',
        'destroy' => 'web.employees.destroy',
    ]);
    Route::patch('/employees/{employee}/complete-onboarding', [EmployeeController::class, 'completeOnboarding'])->name('web.employees.complete-onboarding');
    Route::get('/employees/statistics', [EmployeeController::class, 'statistics'])->name('web.employees.statistics');
    Route::get('/departments/{department}/employees', [EmployeeController::class, 'byDepartment'])->name('web.departments.employees');

    // Offer Letter routes
    Route::resource('offer-letters', OfferLetterController::class)->names([
        'index' => 'web.offer-letters.index',
        'create' => 'web.offer-letters.create',
        'store' => 'web.offer-letters.store',
        'show' => 'web.offer-letters.show',
        'edit' => 'web.offer-letters.edit',
        'update' => 'web.offer-letters.update',
        'destroy' => 'web.offer-letters.destroy',
    ]);
    Route::patch('/offer-letters/{offerLetter}/send', [OfferLetterController::class, 'send'])->name('web.offer-letters.send');
    Route::patch('/offer-letters/{offerLetter}/approve', [OfferLetterController::class, 'approve'])->name('web.offer-letters.approve');
    Route::patch('/offer-letters/{offerLetter}/update-status', [OfferLetterController::class, 'updateStatus'])->name('web.offer-letters.update-status');
    Route::get('/offer-letters/statistics', [OfferLetterController::class, 'statistics'])->name('web.offer-letters.statistics');
    Route::get('/departments/{department}/offer-letters', [OfferLetterController::class, 'byDepartment'])->name('web.departments.offer-letters');

    // Payroll routes
    Route::resource('payrolls', PayrollController::class)->names([
        'index' => 'web.payrolls.index',
        'create' => 'web.payrolls.create',
        'store' => 'web.payrolls.store',
        'show' => 'web.payrolls.show',
        'edit' => 'web.payrolls.edit',
        'update' => 'web.payrolls.update',
        'destroy' => 'web.payrolls.destroy',
    ]);
    Route::patch('/payrolls/{payroll}/mark-as-paid', [PayrollController::class, 'markAsPaid'])->name('web.payrolls.mark-as-paid');
    Route::patch('/payrolls/{payroll}/mark-as-failed', [PayrollController::class, 'markAsFailed'])->name('web.payrolls.mark-as-failed');
    Route::get('/payrolls/statistics', [PayrollController::class, 'statistics'])->name('web.payrolls.statistics');
    Route::get('/employees/{employee}/payrolls', [PayrollController::class, 'byEmployee'])->name('web.employees.payrolls');
    Route::post('/payrolls/generate-bulk', [PayrollController::class, 'generateBulk'])->name('web.payrolls.generate-bulk');

    // Role-based routes
    Route::middleware('role:hr,manager')->group(function () {
        Route::get('/dashboard/statistics', [DashboardController::class, 'statistics'])->name('web.dashboard.statistics');
    });

    Route::middleware('role:manager')->group(function () {
        Route::get('/manager/approvals', [DashboardController::class, 'approvals'])->name('web.manager.approvals');
    });

    Route::middleware('role:employee')->group(function () {
        Route::get('/employee/profile', [EmployeeController::class, 'profile'])->name('web.employee.profile');
        Route::get('/employee/payrolls', [PayrollController::class, 'employeePayrolls'])->name('web.employee.payrolls');
    });
});
