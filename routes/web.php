<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DepartmentController;
use App\Http\Controllers\Web\EmployeeController;
use App\Http\Controllers\Web\OfferLetterController;
use App\Http\Controllers\Web\PayrollController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\ReportsController;
use App\Http\Controllers\Web\LeaveController;

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
        'message' => 'StaffIQ Application is running',
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

    // Employee-specific routes
    // Personal leave management for all users
    Route::middleware('auth')->group(function () {
        Route::get('/my/profile', [EmployeeController::class, 'myProfile'])->name('my.profile');
        Route::get('/my/leaves', [EmployeeController::class, 'myLeaves'])->name('my.leaves');
        Route::get('/my/leaves/request', [EmployeeController::class, 'requestLeave'])->name('my.request-leave');
        Route::post('/my/leaves/request', [EmployeeController::class, 'storeLeaveRequest'])->name('my.store-leave-request');
    });

    Route::middleware('role:employee')->group(function () {
        Route::get('/employee/profile', [EmployeeController::class, 'profile'])->name('web.employee.profile');
        Route::get('/employee/leaves', [EmployeeController::class, 'myLeaves'])->name('employees.my-leaves');
        Route::get('/employee/leaves/request', [EmployeeController::class, 'requestLeave'])->name('employees.request-leave');
        Route::post('/employee/leaves/request', [EmployeeController::class, 'storeLeaveRequest'])->name('employees.store-leave-request');
    });

    // Offer Letter routes - HR and Manager access
    Route::middleware('role:hr,manager')->group(function () {
        Route::get('/offer-letters', [OfferLetterController::class, 'index'])->name('web.offer-letters.index');
        Route::get('/offer-letters/{offerLetter}', [OfferLetterController::class, 'show'])->name('web.offer-letters.show');
        Route::patch('/offer-letters/{offerLetter}/approve', [OfferLetterController::class, 'approve'])->name('web.offer-letters.approve');
        Route::get('/offer-letters/statistics', [OfferLetterController::class, 'statistics'])->name('web.offer-letters.statistics');
        Route::get('/departments/{department}/offer-letters', [OfferLetterController::class, 'byDepartment'])->name('web.departments.offer-letters');
    });

    // Offer Letter routes - HR only (create, edit, delete, send)
    Route::middleware('role:hr')->group(function () {
        Route::get('/offer-letters/create', [OfferLetterController::class, 'create'])->name('web.offer-letters.create');
        Route::post('/offer-letters', [OfferLetterController::class, 'store'])->name('web.offer-letters.store');
        Route::get('/offer-letters/{offerLetter}/edit', [OfferLetterController::class, 'edit'])->name('web.offer-letters.edit');
        Route::put('/offer-letters/{offerLetter}', [OfferLetterController::class, 'update'])->name('web.offer-letters.update');
        Route::delete('/offer-letters/{offerLetter}', [OfferLetterController::class, 'destroy'])->name('web.offer-letters.destroy');
        Route::patch('/offer-letters/{offerLetter}/send', [OfferLetterController::class, 'send'])->name('web.offer-letters.send');
        Route::patch('/offer-letters/{offerLetter}/update-status', [OfferLetterController::class, 'updateStatus'])->name('web.offer-letters.update-status');
    });

    // Payroll routes
    Route::middleware('role:hr')->group(function () {
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
        Route::get('/payrolls/generate-bulk', [PayrollController::class, 'showBulkGenerateForm'])->name('web.payrolls.generate-bulk-form');
        Route::post('/payrolls/generate-bulk', [PayrollController::class, 'generateBulk'])->name('web.payrolls.generate-bulk');
    });

    // Payslip PDF routes
    Route::middleware('role:hr,manager')->group(function () {
        Route::post('/payrolls/{payroll}/generate-payslip', [PayrollController::class, 'generatePayslip'])->name('web.payrolls.generate-payslip');
        Route::get('/payrolls/{payroll}/download-payslip', [PayrollController::class, 'downloadPayslip'])->name('web.payrolls.download-payslip');
        Route::post('/payrolls/generate-bulk-payslips', [PayrollController::class, 'generateBulkPayslips'])->name('web.payrolls.generate-bulk-payslips');
    });

    // Employee payroll access
    Route::middleware('role:employee')->group(function () {
        Route::get('/employee/payrolls', [PayrollController::class, 'employeePayrolls'])->name('web.employee.payrolls');
        Route::get('/employee/payrolls/{payroll}/download-payslip', [PayrollController::class, 'downloadPayslip'])->name('web.employee.download-payslip');
    });

    // Leave routes
    Route::resource('leaves', LeaveController::class)->names([
        'index' => 'leaves.index',
        'create' => 'leaves.create',
        'store' => 'leaves.store',
        'show' => 'leaves.show',
        'edit' => 'leaves.edit',
        'update' => 'leaves.update',
        'destroy' => 'leaves.destroy',
    ]);
    Route::get('/leaves/statistics', [LeaveController::class, 'statistics'])->name('leaves.statistics');

    // Manager approval routes
    Route::middleware('role:manager')->group(function () {
        Route::get('/leaves/manager/approval', [LeaveController::class, 'managerApproval'])->name('leaves.manager-approval');
        Route::patch('/leaves/{leave}/manager/approve', [LeaveController::class, 'approveByManager'])->name('leaves.manager-approve');
        Route::patch('/leaves/{leave}/manager/reject', [LeaveController::class, 'rejectByManager'])->name('leaves.manager-reject');
    });

    // HR approval routes
    Route::middleware('role:hr')->group(function () {
        Route::get('/leaves/hr/approval', [LeaveController::class, 'hrApproval'])->name('leaves.hr-approval');
        Route::patch('/leaves/{leave}/hr/approve', [LeaveController::class, 'approveByHr'])->name('leaves.hr-approve');
        Route::patch('/leaves/{leave}/hr/reject', [LeaveController::class, 'rejectByHr'])->name('leaves.hr-reject');
    });

    // Reports routes
    Route::middleware('role:hr,manager')->group(function () {
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/leave', [ReportsController::class, 'leaveReport'])->name('reports.leave');
        Route::get('/reports/department', [ReportsController::class, 'departmentReport'])->name('reports.department');
        Route::get('/reports/leave/export', [ReportsController::class, 'exportLeaveReport'])->name('reports.leave.export');

        // PDF and Excel Export Routes
        Route::get('/reports/leave/export/excel', [ReportsController::class, 'exportLeaveReportExcel'])->name('reports.leave.export.excel');
        Route::get('/reports/leave/export/pdf', [ReportsController::class, 'exportLeaveReportPdf'])->name('reports.leave.export.pdf');
        Route::get('/reports/payroll/export/excel', [ReportsController::class, 'exportPayrollReportExcel'])->name('reports.payroll.export.excel');
        Route::get('/reports/payroll/export/pdf', [ReportsController::class, 'exportPayrollReportPdf'])->name('reports.payroll.export.pdf');
        Route::get('/reports/employee/export/excel', [ReportsController::class, 'exportEmployeeReportExcel'])->name('reports.employee.export.excel');
        Route::get('/reports/employee/export/pdf', [ReportsController::class, 'exportEmployeeReportPdf'])->name('reports.employee.export.pdf');

        // Leave Approval Routes
        Route::post('/reports/leave/{leave}/approve', [ReportsController::class, 'approveLeave'])->name('reports.leave.approve');
        Route::post('/reports/leave/{leave}/reject', [ReportsController::class, 'rejectLeave'])->name('reports.leave.reject');
    });

    // Role-based routes
    Route::middleware('role:hr,manager')->group(function () {
        Route::get('/dashboard/statistics', [DashboardController::class, 'statistics'])->name('web.dashboard.statistics');
    });

    Route::middleware('role:manager')->group(function () {
        Route::get('/manager/approvals', [DashboardController::class, 'approvals'])->name('web.manager.approvals');
        Route::get('/manager/team', [EmployeeController::class, 'managerTeam'])->name('web.manager.team');
    });

    Route::middleware('role:employee')->group(function () {
        Route::get('/employee/dashboard', [DashboardController::class, 'employeeDashboard'])->name('web.employee.dashboard');
    });
});
