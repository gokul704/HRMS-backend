<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\OfferLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\ReportService;
use App\Exports\LeaveReportExport;
use App\Exports\PayrollReportExport;
use App\Exports\EmployeeReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->isHr()) {
            return $this->hrReports();
        } elseif ($user->isManager()) {
            return $this->managerReports();
        } else {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
    }

    public function hrReports()
    {
        try {
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

            // Payroll statistics - Fixed: using payment_status instead of status
            $totalPayrolls = Payroll::count();
            $paidPayrolls = Payroll::where('payment_status', 'paid')->count();
            $pendingPayrolls = Payroll::where('payment_status', 'pending')->count();

            // Offer letter statistics - Fixed: using correct status values
            $totalOffers = OfferLetter::count();
            $pendingOffers = OfferLetter::whereIn('status', ['draft', 'sent'])->count();
            $acceptedOffers = OfferLetter::where('status', 'accepted')->count();

            // Department-wise employee count
            $departmentStats = Department::withCount('employees')->get();

            // Recent leaves
            $recentLeaves = Leave::with(['employee.department', 'appliedBy'])
                ->latest()
                ->take(10)
                ->get();

            // Recent employees
            $recentEmployees = Employee::with(['department', 'user'])
                ->latest()
                ->take(10)
                ->get();

            // Monthly leave trends
            $monthlyLeaves = Leave::selectRaw('MONTH(start_date) as month, COUNT(*) as count')
                ->whereYear('start_date', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Leave type distribution for all departments
            $leaveTypeDistribution = Leave::selectRaw('leave_type, COUNT(*) as count')
                ->whereYear('start_date', date('Y'))
                ->groupBy('leave_type')
                ->get();

            return view('reports.hr', compact(
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
                'departmentStats',
                'recentLeaves',
                'recentEmployees',
                'monthlyLeaves',
                'leaveTypeDistribution'
            ));
        } catch (\Exception $e) {
            \Log::error('HR Reports Error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error loading reports: ' . $e->getMessage());
        }
    }

    public function managerReports()
    {
        try {
            $user = auth()->user();

            // Get manager's department
            $managerDepartment = null;
            if ($user->employee) {
                $managerDepartment = $user->employee->department;
            }

            if (!$managerDepartment) {
                return redirect()->route('dashboard')->with('error', 'Manager department not found.');
            }

            // Department statistics
            $departmentEmployees = Employee::where('department_id', $managerDepartment->id)->count();
            $departmentActiveEmployees = Employee::where('department_id', $managerDepartment->id)
                ->where('employment_status', 'active')
                ->count();

            // Department leave statistics - Fixed: using correct scope
            $departmentLeaves = Leave::whereHas('employee', function($query) use ($managerDepartment) {
                $query->where('department_id', $managerDepartment->id);
            });
            $totalDepartmentLeaves = $departmentLeaves->count();
            $pendingDepartmentLeaves = $departmentLeaves->where('status', 'pending')->count();
            $approvedDepartmentLeaves = $departmentLeaves->whereIn('status', ['manager_approved', 'hr_approved'])->count();
            $rejectedDepartmentLeaves = $departmentLeaves->whereIn('status', ['manager_rejected', 'hr_rejected'])->count();

            // Department employees with their leave counts
            $departmentEmployeesWithLeaves = Employee::where('department_id', $managerDepartment->id)
                ->withCount(['leaves as total_leaves' => function($query) {
                    $query->whereYear('start_date', date('Y'));
                }])
                ->withCount(['leaves as approved_leaves' => function($query) {
                    $query->whereYear('start_date', date('Y'))->whereIn('status', ['manager_approved', 'hr_approved']);
                }])
                ->withCount(['leaves as pending_leaves' => function($query) {
                    $query->whereYear('start_date', date('Y'))->where('status', 'pending');
                }])
                ->get();

            // Recent department leaves
            $recentDepartmentLeaves = Leave::with(['employee', 'appliedBy'])
                ->whereHas('employee', function($query) use ($managerDepartment) {
                    $query->where('department_id', $managerDepartment->id);
                })
                ->latest()
                ->take(10)
                ->get();

            // Monthly department leave trends
            $monthlyDepartmentLeaves = Leave::selectRaw('MONTH(start_date) as month, COUNT(*) as count')
                ->whereHas('employee', function($query) use ($managerDepartment) {
                    $query->where('department_id', $managerDepartment->id);
                })
                ->whereYear('start_date', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Leave type distribution for department
            $leaveTypeDistribution = Leave::selectRaw('leave_type, COUNT(*) as count')
                ->whereHas('employee', function($query) use ($managerDepartment) {
                    $query->where('department_id', $managerDepartment->id);
                })
                ->whereYear('start_date', date('Y'))
                ->groupBy('leave_type')
                ->get();

            return view('reports.manager', compact(
                'managerDepartment',
                'departmentEmployees',
                'departmentActiveEmployees',
                'totalDepartmentLeaves',
                'pendingDepartmentLeaves',
                'approvedDepartmentLeaves',
                'rejectedDepartmentLeaves',
                'departmentEmployeesWithLeaves',
                'recentDepartmentLeaves',
                'monthlyDepartmentLeaves',
                'leaveTypeDistribution'
            ));
        } catch (\Exception $e) {
            \Log::error('Manager Reports Error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error loading reports: ' . $e->getMessage());
        }
    }

    public function leaveReport(Request $request)
    {
        try {
            $user = auth()->user();

            $query = Leave::with(['employee.department', 'appliedBy', 'approvedBy']);

            // Apply filters
            if ($request->filled('department_id')) {
                $query->whereHas('employee', function($q) use ($request) {
                    $q->where('department_id', $request->department_id);
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('leave_type')) {
                $query->where('leave_type', $request->leave_type);
            }

            if ($request->filled('date_from')) {
                $query->where('start_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('end_date', '<=', $request->date_to);
            }

            // For managers, only show their department
            if ($user->isManager() && $user->employee && $user->employee->department) {
                $query->whereHas('employee', function($q) use ($user) {
                    $q->where('department_id', $user->employee->department_id);
                });
            }

            $leaves = $query->latest()->paginate(20);
            $departments = Department::all();

            return view('reports.leave', compact('leaves', 'departments'));
        } catch (\Exception $e) {
            \Log::error('Leave Report Error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error loading leave report: ' . $e->getMessage());
        }
    }

    public function departmentReport(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->isManager() && $user->employee && $user->employee->department) {
                // Manager can only see their department
                $department = $user->employee->department;
                return $this->singleDepartmentReport($department);
            }

            // HR can see all departments
            $departments = Department::withCount('employees')
                ->withCount(['employees as active_employees' => function($query) {
                    $query->where('employment_status', 'active');
                }])
                ->withCount(['leaves as total_leaves' => function($query) {
                    $query->whereYear('start_date', date('Y'));
                }])
                ->withCount(['leaves as pending_leaves' => function($query) {
                    $query->whereYear('start_date', date('Y'))->where('status', 'pending');
                }])
                ->get();

            return view('reports.department', compact('departments'));
        } catch (\Exception $e) {
            \Log::error('Department Report Error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error loading department report: ' . $e->getMessage());
        }
    }

    private function singleDepartmentReport($department)
    {
        try {
            $employees = Employee::where('department_id', $department->id)
                ->withCount(['leaves as total_leaves' => function($query) {
                    $query->whereYear('start_date', date('Y'));
                }])
                ->withCount(['leaves as approved_leaves' => function($query) {
                    $query->whereYear('start_date', date('Y'))->whereIn('status', ['manager_approved', 'hr_approved']);
                }])
                ->withCount(['leaves as pending_leaves' => function($query) {
                    $query->whereYear('start_date', date('Y'))->where('status', 'pending');
                }])
                ->get();

            $recentLeaves = Leave::with(['employee'])
                ->whereHas('employee', function($query) use ($department) {
                    $query->where('department_id', $department->id);
                })
                ->latest()
                ->take(10)
                ->get();

            return view('reports.department-single', compact('department', 'employees', 'recentLeaves'));
        } catch (\Exception $e) {
            \Log::error('Single Department Report Error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error loading department report: ' . $e->getMessage());
        }
    }

    public function exportLeaveReport(Request $request)
    {
        try {
            $user = auth()->user();

            $query = Leave::with(['employee.department', 'appliedBy', 'approvedBy']);

            // Apply filters
            if ($request->filled('department_id')) {
                $query->whereHas('employee', function($q) use ($request) {
                    $q->where('department_id', $request->department_id);
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->where('start_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('end_date', '<=', $request->date_to);
            }

            // For managers, only show their department
            if ($user->isManager() && $user->employee && $user->employee->department) {
                $query->whereHas('employee', function($q) use ($user) {
                    $q->where('department_id', $user->employee->department_id);
                });
            }

            $leaves = $query->get();

            $filename = 'leave_report_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($leaves) {
                $file = fopen('php://output', 'w');

                // Add headers
                fputcsv($file, [
                    'Employee ID',
                    'Employee Name',
                    'Department',
                    'Leave Type',
                    'Start Date',
                    'End Date',
                    'Total Days',
                    'Reason',
                    'Status',
                    'Applied By',
                    'Approved By',
                    'Approved At',
                    'Applied Date'
                ]);

                // Add data
                foreach ($leaves as $leave) {
                    fputcsv($file, [
                        $leave->employee->employee_id ?? 'N/A',
                        $leave->employee->first_name . ' ' . $leave->employee->last_name,
                        $leave->employee->department->name ?? 'N/A',
                        $leave->leave_type_label,
                        $leave->start_date->format('Y-m-d'),
                        $leave->end_date->format('Y-m-d'),
                        $leave->total_days,
                        $leave->reason,
                        $leave->status_label,
                        $leave->appliedBy->name ?? 'N/A',
                        $leave->approvedBy->name ?? 'N/A',
                        $leave->approved_at ? $leave->approved_at->format('Y-m-d H:i:s') : 'N/A',
                        $leave->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            \Log::error('Export Leave Report Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }

    /**
     * Export leave report as Excel
     */
    public function exportLeaveReportExcel(Request $request)
    {
        try {
            $user = auth()->user();

            $query = Leave::with(['employee.department', 'appliedBy', 'approvedBy']);

            // Apply filters
            if ($request->filled('department_id')) {
                $query->whereHas('employee', function($q) use ($request) {
                    $q->where('department_id', $request->department_id);
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->where('start_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('end_date', '<=', $request->date_to);
            }

            // For managers, only show their department
            if ($user->isManager() && $user->employee && $user->employee->department) {
                $query->whereHas('employee', function($q) use ($user) {
                    $q->where('department_id', $user->employee->department_id);
                });
            }

            $leaves = $query->get();

            $filename = 'leave_report_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new LeaveReportExport($leaves), $filename);
        } catch (\Exception $e) {
            \Log::error('Export Leave Report Excel Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }

    /**
     * Export leave report as PDF
     */
    public function exportLeaveReportPdf(Request $request)
    {
        try {
            $user = auth()->user();

            $query = Leave::with(['employee.department', 'appliedBy', 'approvedBy']);

            // Apply filters
            if ($request->filled('department_id')) {
                $query->whereHas('employee', function($q) use ($request) {
                    $q->where('department_id', $request->department_id);
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->where('start_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('end_date', '<=', $request->date_to);
            }

            // For managers, only show their department
            if ($user->isManager() && $user->employee && $user->employee->department) {
                $query->whereHas('employee', function($q) use ($user) {
                    $q->where('department_id', $user->employee->department_id);
                });
            }

            $leaves = $query->get();

            $pdf = $this->reportService->generateLeaveReport($leaves, $request->all());

            $filename = 'leave_report_' . date('Y-m-d_H-i-s') . '.pdf';

            return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            \Log::error('Export Leave Report PDF Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }

    /**
     * Export payroll report as Excel
     */
    public function exportPayrollReportExcel(Request $request)
    {
        try {
            $query = Payroll::with(['employee.department', 'processedBy']);

            // Apply filters
            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            if ($request->filled('month')) {
                $query->where('month', $request->month);
            }

            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }

            $payrolls = $query->get();

            $filename = 'payroll_report_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new PayrollReportExport($payrolls), $filename);
        } catch (\Exception $e) {
            \Log::error('Export Payroll Report Excel Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }

    /**
     * Export payroll report as PDF
     */
    public function exportPayrollReportPdf(Request $request)
    {
        try {
            $query = Payroll::with(['employee.department', 'processedBy']);

            // Apply filters
            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            if ($request->filled('month')) {
                $query->where('month', $request->month);
            }

            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }

            $payrolls = $query->get();

            $pdf = $this->reportService->generatePayrollReport($payrolls, $request->all());

            $filename = 'payroll_report_' . date('Y-m-d_H-i-s') . '.pdf';

            return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            \Log::error('Export Payroll Report PDF Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }

    /**
     * Export employee report as Excel
     */
    public function exportEmployeeReportExcel(Request $request)
    {
        try {
            $query = Employee::with(['user', 'department']);

            // Apply filters
            if ($request->filled('department_id')) {
                $query->where('department_id', $request->department_id);
            }

            if ($request->filled('employment_status')) {
                $query->where('employment_status', $request->employment_status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('employee_id', 'like', "%{$search}%");
                });
            }

            $employees = $query->get();

            $filename = 'employee_report_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new EmployeeReportExport($employees), $filename);
        } catch (\Exception $e) {
            \Log::error('Export Employee Report Excel Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }

    /**
     * Export employee report as PDF
     */
    public function exportEmployeeReportPdf(Request $request)
    {
        try {
            $query = Employee::with(['user', 'department']);

            // Apply filters
            if ($request->filled('department_id')) {
                $query->where('department_id', $request->department_id);
            }

            if ($request->filled('employment_status')) {
                $query->where('employment_status', $request->employment_status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('employee_id', 'like', "%{$search}%");
                });
            }

            $employees = $query->get();

            $pdf = $this->reportService->generateEmployeeReport($employees, $request->all());

            $filename = 'employee_report_' . date('Y-m-d_H-i-s') . '.pdf';

            return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            \Log::error('Export Employee Report PDF Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }

    /**
     * Approve leave (for managers and HR)
     */
    public function approveLeave(Request $request, $leaveId)
    {
        try {
            $user = auth()->user();
            $leave = Leave::with('employee')->findOrFail($leaveId);

            // Check if user has permission to approve this leave
            if ($user->isManager()) {
                // Manager can only approve leaves from their department
                if ($leave->employee->department_id !== $user->employee->department_id) {
                    return redirect()->back()->with('error', 'You can only approve leaves from your department.');
                }
            } elseif (!$user->isHr()) {
                return redirect()->back()->with('error', 'Access denied.');
            }

            // Check if leave is pending
            if ($leave->status !== 'pending') {
                return redirect()->back()->with('error', 'This leave has already been processed.');
            }

            // Update leave status
            $leave->status = $user->isManager() ? 'manager_approved' : 'hr_approved';
            $leave->approved_by = $user->id;
            $leave->approved_at = now();
            $leave->save();

            return redirect()->back()->with('success', 'Leave approved successfully.');
        } catch (\Exception $e) {
            \Log::error('Approve Leave Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error approving leave: ' . $e->getMessage());
        }
    }

    /**
     * Reject leave (for managers and HR)
     */
    public function rejectLeave(Request $request, $leaveId)
    {
        try {
            $user = auth()->user();
            $leave = Leave::with('employee')->findOrFail($leaveId);

            // Check if user has permission to reject this leave
            if ($user->isManager()) {
                // Manager can only reject leaves from their department
                if ($leave->employee->department_id !== $user->employee->department_id) {
                    return redirect()->back()->with('error', 'You can only reject leaves from your department.');
                }
            } elseif (!$user->isHr()) {
                return redirect()->back()->with('error', 'Access denied.');
            }

            // Check if leave is pending
            if ($leave->status !== 'pending') {
                return redirect()->back()->with('error', 'This leave has already been processed.');
            }

            // Update leave status
            $leave->status = $user->isManager() ? 'manager_rejected' : 'hr_rejected';
            $leave->approved_by = $user->id;
            $leave->approved_at = now();
            $leave->save();

            return redirect()->back()->with('success', 'Leave rejected successfully.');
        } catch (\Exception $e) {
            \Log::error('Reject Leave Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error rejecting leave: ' . $e->getMessage());
        }
    }
}
