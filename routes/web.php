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
    Route::resource('departments', DepartmentController::class);
    Route::get('/departments/{department}/statistics', [DepartmentController::class, 'statistics'])->name('departments.statistics');
    Route::patch('/departments/{department}/toggle-status', [DepartmentController::class, 'toggleStatus'])->name('departments.toggle-status');

    // Employee routes
    Route::resource('employees', EmployeeController::class);
    Route::patch('/employees/{employee}/complete-onboarding', [EmployeeController::class, 'completeOnboarding'])->name('employees.complete-onboarding');
    Route::get('/employees/statistics', [EmployeeController::class, 'statistics'])->name('employees.statistics');
    Route::get('/departments/{department}/employees', [EmployeeController::class, 'byDepartment'])->name('departments.employees');

    // Offer Letter routes
    Route::resource('offer-letters', OfferLetterController::class);
    Route::patch('/offer-letters/{offerLetter}/send', [OfferLetterController::class, 'send'])->name('offer-letters.send');
    Route::patch('/offer-letters/{offerLetter}/approve', [OfferLetterController::class, 'approve'])->name('offer-letters.approve');
    Route::patch('/offer-letters/{offerLetter}/update-status', [OfferLetterController::class, 'updateStatus'])->name('offer-letters.update-status');
    Route::get('/offer-letters/statistics', [OfferLetterController::class, 'statistics'])->name('offer-letters.statistics');
    Route::get('/departments/{department}/offer-letters', [OfferLetterController::class, 'byDepartment'])->name('departments.offer-letters');

    // Payroll routes
    Route::resource('payrolls', PayrollController::class);
    Route::patch('/payrolls/{payroll}/mark-as-paid', [PayrollController::class, 'markAsPaid'])->name('payrolls.mark-as-paid');
    Route::patch('/payrolls/{payroll}/mark-as-failed', [PayrollController::class, 'markAsFailed'])->name('payrolls.mark-as-failed');
    Route::get('/payrolls/statistics', [PayrollController::class, 'statistics'])->name('payrolls.statistics');
    Route::get('/employees/{employee}/payrolls', [PayrollController::class, 'byEmployee'])->name('employees.payrolls');
    Route::post('/payrolls/generate-bulk', [PayrollController::class, 'generateBulk'])->name('payrolls.generate-bulk');

    // Role-based routes
    Route::middleware('role:hr,manager')->group(function () {
        Route::get('/dashboard/statistics', [DashboardController::class, 'statistics'])->name('dashboard.statistics');
    });

    Route::middleware('role:manager')->group(function () {
        Route::get('/manager/approvals', [DashboardController::class, 'approvals'])->name('manager.approvals');
    });

    Route::middleware('role:employee')->group(function () {
        Route::get('/employee/profile', [EmployeeController::class, 'profile'])->name('employee.profile');
        Route::get('/employee/payrolls', [PayrollController::class, 'employeePayrolls'])->name('employee.payrolls');
    });
});
