<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\User;
use App\Models\Leave; // Added this import
use Carbon\Carbon; // Added this import

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees
     */
    public function index()
    {
        $employees = Employee::with(['user', 'department'])
            ->when(request('search'), function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('employee_id', 'like', "%{$search}%")
                      ->orWhere('position', 'like', "%{$search}%");
                });
            })
            ->when(request('department'), function($query, $department) {
                $query->where('department_id', $department);
            })
            ->when(request('status'), function($query, $status) {
                $query->where('employment_status', $status);
            })
            ->paginate(15);

        $departments = Department::all();

        return view('employees.index', compact('employees', 'departments'));
    }

    /**
     * Show the form for creating a new employee
     */
    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        return view('employees.create', compact('departments'));
    }

    /**
     * Store a newly created employee
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'position' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'employment_status' => 'required|in:active,inactive,terminated',
        ]);

        // Create user account
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => bcrypt('password123'), // Default password
            'role' => 'employee',
            'is_active' => true,
        ]);

        // Generate employee ID
        $employeeId = 'EMP' . str_pad(Employee::count() + 1, 3, '0', STR_PAD_LEFT);

        // Create employee record
        $employee = Employee::create([
            'user_id' => $user->id,
            'department_id' => $request->department_id,
            'employee_id' => $employeeId,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'position' => $request->position,
            'hire_date' => $request->hire_date,
            'salary' => $request->salary,
            'employment_status' => $request->employment_status,
            'is_onboarded' => false,
        ]);

        return redirect()->route('web.employees.index')
            ->with('success', 'Employee created successfully!');
    }

    /**
     * Display the specified employee
     */
    public function show(Employee $employee)
    {
        $user = auth()->user();

        // Check access permissions
        if ($user->isEmployee()) {
            // Employees can only view their own profile
            if ($user->employee->id !== $employee->id) {
                return redirect()->route('dashboard')->with('error', 'Access denied.');
            }
        } elseif ($user->isManager()) {
            // Managers can only view employees from their department
            if ($user->employee->department_id !== $employee->department_id) {
                return redirect()->route('dashboard')->with('error', 'Access denied.');
            }
        } elseif (!$user->isHr()) {
            // Only HR can view all employees
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $employee->load(['user', 'department', 'payrolls']);
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee
     */
    public function edit(Employee $employee)
    {
        $departments = Department::where('is_active', true)->get();
        return view('employees.edit', compact('employee', 'departments'));
    }

    /**
     * Update the specified employee
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'position' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'employment_status' => 'required|in:active,inactive,terminated',
            'is_onboarded' => 'boolean',
        ]);

        $employee->update($request->all());

        return redirect()->route('web.employees.index')
            ->with('success', 'Employee updated successfully!');
    }

    /**
     * Remove the specified employee
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('web.employees.index')
            ->with('success', 'Employee deleted successfully!');
    }

    /**
     * Complete employee onboarding
     */
    public function completeOnboarding(Employee $employee)
    {
        $employee->update(['is_onboarded' => true]);

        return back()->with('success', 'Employee onboarding completed successfully!');
    }

    /**
     * Show employee statistics
     */
    public function statistics()
    {
        $data = [
            'totalEmployees' => Employee::count(),
            'activeEmployees' => Employee::where('employment_status', 'active')->count(),
            'inactiveEmployees' => Employee::where('employment_status', 'inactive')->count(),
            'terminatedEmployees' => Employee::where('employment_status', 'terminated')->count(),
            'onboardedEmployees' => Employee::where('is_onboarded', true)->count(),
            'pendingOnboarding' => Employee::where('is_onboarded', false)->count(),
            'employeesByDepartment' => Department::withCount('employees')->get(),
            'recentHires' => Employee::with(['user', 'department'])->latest('hire_date')->take(10)->get(),
        ];

        return view('employees.statistics', $data);
    }

    /**
     * Show employees by department
     */
    public function byDepartment(Department $department)
    {
        $employees = $department->employees()->with(['user'])->paginate(15);
        return view('employees.by-department', compact('employees', 'department'));
    }

    /**
     * Show employee profile (for employees)
     */
    public function profile()
    {
        $user = auth()->user();

        if (!$user->isEmployee()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not found.');
        }

        // Get employee's leave statistics
        $leaveStats = [
            'total_leaves' => $employee->leaves()->count(),
            'approved_leaves' => $employee->leaves()->whereIn('status', ['manager_approved', 'hr_approved'])->count(),
            'pending_leaves' => $employee->leaves()->where('status', 'pending')->count(),
            'rejected_leaves' => $employee->leaves()->whereIn('status', ['manager_rejected', 'hr_rejected'])->count(),
        ];

        // Get recent leaves
        $recentLeaves = $employee->leaves()
            ->with(['appliedBy', 'approvedBy'])
            ->latest()
            ->take(5)
            ->get();

        // Get recent payrolls
        $recentPayrolls = $employee->payrolls()
            ->latest()
            ->take(5)
            ->get();

        return view('employees.profile', compact('employee', 'leaveStats', 'recentLeaves', 'recentPayrolls'));
    }

    /**
     * Show user's own profile (for HR and Managers)
     */
    public function myProfile()
    {
        $user = auth()->user();

        if ($user->isEmployee()) {
            return redirect()->route('web.employee.profile');
        }

        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not found.');
        }

        // Get employee's leave statistics
        $leaveStats = [
            'total_leaves' => $employee->leaves()->count(),
            'approved_leaves' => $employee->leaves()->whereIn('status', ['manager_approved', 'hr_approved'])->count(),
            'pending_leaves' => $employee->leaves()->where('status', 'pending')->count(),
            'rejected_leaves' => $employee->leaves()->whereIn('status', ['manager_rejected', 'hr_rejected'])->count(),
        ];

        // Get recent leaves
        $recentLeaves = $employee->leaves()
            ->with(['appliedBy', 'approvedBy'])
            ->latest()
            ->take(5)
            ->get();

        // Get recent payrolls
        $recentPayrolls = $employee->payrolls()
            ->latest()
            ->take(5)
            ->get();

        return view('employees.profile', compact('employee', 'leaveStats', 'recentLeaves', 'recentPayrolls'));
    }

    /**
     * Show user's own leaves (for all users)
     */
    public function myLeaves()
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not found.');
        }

        $leaves = $employee->leaves()
            ->with(['appliedBy', 'approvedBy'])
            ->latest()
            ->paginate(15);

        return view('employees.my-leaves', compact('leaves', 'employee'));
    }

    /**
     * Show form to request leave
     */
    public function requestLeave()
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not found.');
        }

        return view('employees.request-leave', compact('employee'));
    }

    /**
     * Store leave request
     */
    public function storeLeaveRequest(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not found.');
        }

        $request->validate([
            'leave_type' => 'required|in:annual,sick,casual,maternity,paternity,other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        // Calculate total days
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        // Check if employee has enough leave balance (basic validation)
        $leaveBalance = $this->calculateLeaveBalance($employee, $request->leave_type);

        if ($leaveBalance < $totalDays) {
            return back()->withErrors(['leave_type' => 'Insufficient leave balance.']);
        }

        // Create leave request
        $leave = Leave::create([
            'employee_id' => $employee->id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'status' => 'pending',
            'applied_by' => $user->id,
        ]);

        return redirect()->route('employees.my-leaves')->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Show manager's team
     */
    public function managerTeam()
    {
        $user = auth()->user();

        if (!$user->isManager()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $employee = $user->employee;

        if (!$employee || !$employee->department) {
            return redirect()->route('dashboard')->with('error', 'Manager department not found.');
        }

        $teamMembers = Employee::where('department_id', $employee->department_id)
            ->where('id', '!=', $employee->id) // Exclude manager
            ->with(['user', 'leaves' => function($query) {
                $query->whereYear('start_date', date('Y'));
            }])
            ->withCount(['leaves as total_leaves' => function($query) {
                $query->whereYear('start_date', date('Y'));
            }])
            ->withCount(['leaves as pending_leaves' => function($query) {
                $query->whereYear('start_date', date('Y'))->where('status', 'pending');
            }])
            ->get();

        $pendingLeaves = Leave::with(['employee.user'])
            ->whereHas('employee', function($query) use ($employee) {
                $query->where('department_id', $employee->department_id);
            })
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('employees.manager-team', compact('teamMembers', 'pendingLeaves', 'employee'));
    }

    /**
     * Calculate leave balance for employee
     */
    private function calculateLeaveBalance($employee, $leaveType)
    {
        // This is a simplified calculation
        // In a real system, you would have leave policies and balances
        $usedLeaves = $employee->leaves()
            ->where('leave_type', $leaveType)
            ->whereIn('status', ['manager_approved', 'hr_approved'])
            ->whereYear('start_date', date('Y'))
            ->sum('total_days');

        // Default leave allocation (this should come from leave policies)
        $allocatedLeaves = match($leaveType) {
            'annual' => 21,
            'sick' => 10,
            'casual' => 7,
            'maternity' => 90,
            'paternity' => 14,
            default => 5
        };

        return $allocatedLeaves - $usedLeaves;
    }
}
