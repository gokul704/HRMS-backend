<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\User;

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
            'user_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'employee_id' => 'required|string|unique:employees',
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

        Employee::create($request->all());

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully!');
    }

    /**
     * Display the specified employee
     */
    public function show(Employee $employee)
    {
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

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully!');
    }

    /**
     * Remove the specified employee
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
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
     * Show employee profile (Employee only)
     */
    public function profile()
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not found.');
        }

        $employee->load(['department', 'payrolls']);

        return view('employees.profile', compact('employee'));
    }
}
