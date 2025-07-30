<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Employee;
use App\Models\OfferLetter;
use App\Models\Payroll;
use App\Models\Leave; // Added missing import for Leave

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index()
    {
        $user = auth()->user();

        // Redirect based on user role
        if ($user->isEmployee()) {
            return redirect()->route('web.employee.dashboard');
        } elseif ($user->isManager()) {
            return $this->managerDashboard();
        } elseif ($user->isHr()) {
            return $this->hrDashboard();
        } else {
            // Default dashboard for other roles
            return $this->defaultDashboard();
        }
    }

    /**
     * HR Dashboard
     */
    private function hrDashboard()
    {
        // Overall statistics
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('employment_status', 'active')->count();
        $totalDepartments = Department::count();
        $activeDepartments = Department::where('is_active', true)->count();

        // Leave statistics
        $totalLeaves = Leave::count();
        $pendingLeaves = Leave::where('status', 'pending')->count();
        $approvedLeaves = Leave::whereIn('status', ['manager_approved', 'hr_approved'])->count();
        $rejectedLeaves = Leave::whereIn('status', ['manager_rejected', 'hr_rejected'])->count();

        // Payroll statistics
        $totalPayrolls = Payroll::count();
        $paidPayrolls = Payroll::where('payment_status', 'paid')->count();
        $pendingPayrolls = Payroll::where('payment_status', 'pending')->count();

        // Offer letter statistics
        $totalOffers = OfferLetter::count();
        $pendingOffers = OfferLetter::whereIn('status', ['draft', 'sent'])->count();
        $acceptedOffers = OfferLetter::where('status', 'accepted')->count();

        // Recent activities
        $recentLeaves = Leave::with(['employee.department', 'appliedBy'])
            ->latest()
            ->take(5)
            ->get();

        $recentEmployees = Employee::with(['department', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.hr', compact(
            'totalEmployees',
            'activeEmployees',
            'totalDepartments',
            'activeDepartments',
            'totalLeaves',
            'pendingLeaves',
            'approvedLeaves',
            'rejectedLeaves',
            'totalPayrolls',
            'paidPayrolls',
            'pendingPayrolls',
            'totalOffers',
            'pendingOffers',
            'acceptedOffers',
            'recentLeaves',
            'recentEmployees'
        ));
    }

    /**
     * Manager Dashboard
     */
    private function managerDashboard()
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee || !$employee->department) {
            return redirect()->route('dashboard')->with('error', 'Manager department not found.');
        }

        // Department statistics
        $departmentEmployees = Employee::where('department_id', $employee->department_id)->count();
        $departmentActiveEmployees = Employee::where('department_id', $employee->department_id)
            ->where('employment_status', 'active')
            ->count();

        // Department leave statistics
        $departmentLeaves = Leave::whereHas('employee', function($query) use ($employee) {
            $query->where('department_id', $employee->department_id);
        });
        $totalDepartmentLeaves = $departmentLeaves->count();
        $pendingDepartmentLeaves = $departmentLeaves->where('status', 'pending')->count();
        $approvedDepartmentLeaves = $departmentLeaves->whereIn('status', ['manager_approved', 'hr_approved'])->count();

        // Team members
        $teamMembers = Employee::where('department_id', $employee->department_id)
            ->where('id', '!=', $employee->id)
            ->with(['user'])
            ->get();

        // Department offer letter statistics
        $departmentOffers = OfferLetter::where('department_id', $employee->department_id);
        $totalDepartmentOffers = $departmentOffers->count();
        $pendingDepartmentOffers = $departmentOffers->whereIn('status', ['draft', 'sent'])->count();
        $pendingOffers = $pendingDepartmentOffers; // Alias for view compatibility

        // Recent department leaves
        $recentDepartmentLeaves = Leave::with(['employee', 'appliedBy'])
            ->whereHas('employee', function($query) use ($employee) {
                $query->where('department_id', $employee->department_id);
            })
            ->latest()
            ->take(5)
            ->get();

        // Recent department offer letters
        $recentDepartmentOffers = OfferLetter::with(['department', 'createdBy'])
            ->where('department_id', $employee->department_id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.manager', compact(
            'employee',
            'departmentEmployees',
            'departmentActiveEmployees',
            'totalDepartmentLeaves',
            'pendingDepartmentLeaves',
            'approvedDepartmentLeaves',
            'pendingOffers',
            'totalDepartmentOffers',
            'pendingDepartmentOffers',
            'teamMembers',
            'recentDepartmentLeaves',
            'recentDepartmentOffers'
        ));
    }

    /**
     * Default Dashboard
     */
    private function defaultDashboard()
    {
        // Basic statistics for other roles
        $totalEmployees = Employee::count();
        $totalDepartments = Department::count();
        $totalLeaves = Leave::count();
        $totalPayrolls = Payroll::count();

        return view('dashboard.default', compact(
            'totalEmployees',
            'totalDepartments',
            'totalLeaves',
            'totalPayrolls'
        ));
    }

    /**
     * Show dashboard statistics (HR & Manager only)
     */
    public function statistics()
    {
        $this->authorize('viewStatistics');

        $data = [
            'departments' => Department::withCount('employees')->get(),
            'employeesByStatus' => [
                'active' => Employee::where('employment_status', 'active')->count(),
                'inactive' => Employee::where('employment_status', 'inactive')->count(),
                'terminated' => Employee::where('employment_status', 'terminated')->count(),
            ],
            'offerLettersByStatus' => [
                'draft' => OfferLetter::where('status', 'draft')->count(),
                'sent' => OfferLetter::where('status', 'sent')->count(),
                'accepted' => OfferLetter::where('status', 'accepted')->count(),
                'rejected' => OfferLetter::where('status', 'rejected')->count(),
            ],
            'payrollsByStatus' => [
                'pending' => Payroll::where('payment_status', 'pending')->count(),
                'paid' => Payroll::where('payment_status', 'paid')->count(),
                'failed' => Payroll::where('payment_status', 'failed')->count(),
            ],
        ];

        return view('dashboard.statistics', $data);
    }

    /**
     * Show manager approvals (Manager only)
     */
    public function approvals()
    {
        $this->authorize('viewApprovals');

        $data = [
            'pendingOffers' => OfferLetter::where('status', 'draft')->with(['department', 'createdBy'])->get(),
            'pendingPayrolls' => Payroll::where('payment_status', 'pending')->with(['employee'])->get(),
        ];

        return view('dashboard.approvals', $data);
    }

    /**
     * Show employee dashboard
     */
    public function employeeDashboard()
    {
        $user = auth()->user();

        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not found.');
        }

        // Employee statistics
        $leaveStats = [
            'total_leaves' => $employee->leaves()->count(),
            'approved_leaves' => $employee->leaves()->whereIn('status', ['manager_approved', 'hr_approved'])->count(),
            'pending_leaves' => $employee->leaves()->where('status', 'pending')->count(),
            'rejected_leaves' => $employee->leaves()->whereIn('status', ['manager_rejected', 'hr_rejected'])->count(),
        ];

        $payrollStats = [
            'total_payrolls' => $employee->payrolls()->count(),
            'paid_payrolls' => $employee->payrolls()->where('payment_status', 'paid')->count(),
            'pending_payrolls' => $employee->payrolls()->where('payment_status', 'pending')->count(),
        ];

        // Recent activities
        $recentLeaves = $employee->leaves()
            ->with(['appliedBy', 'approvedBy'])
            ->latest()
            ->take(5)
            ->get();

        $recentPayrolls = $employee->payrolls()
            ->with(['processedBy'])
            ->latest()
            ->take(5)
            ->get();

        // Leave balance (simplified)
        $leaveBalance = [
            'annual' => $this->calculateLeaveBalance($employee, 'annual'),
            'sick' => $this->calculateLeaveBalance($employee, 'sick'),
            'casual' => $this->calculateLeaveBalance($employee, 'casual'),
        ];

        return view('dashboard.employee', compact(
            'employee',
            'leaveStats',
            'payrollStats',
            'recentLeaves',
            'recentPayrolls',
            'leaveBalance'
        ));
    }

    /**
     * Calculate leave balance for employee
     */
    private function calculateLeaveBalance($employee, $leaveType)
    {
        $usedLeaves = $employee->leaves()
            ->where('leave_type', $leaveType)
            ->whereIn('status', ['manager_approved', 'hr_approved'])
            ->whereYear('start_date', date('Y'))
            ->sum('total_days');

        $allocatedLeaves = match($leaveType) {
            'annual' => 21,
            'sick' => 10,
            'casual' => 7,
            default => 5
        };

        return max(0, $allocatedLeaves - $usedLeaves);
    }
}
