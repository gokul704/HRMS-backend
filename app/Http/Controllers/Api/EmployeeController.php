<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees
     */
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'department']);

        // Filter by department
        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Filter by employment status
        if ($request->has('employment_status')) {
            $query->where('employment_status', $request->employment_status);
        }

        // Filter by onboarding status
        if ($request->has('is_onboarded')) {
            $query->where('is_onboarded', $request->is_onboarded);
        }

        // Search by name or employee ID
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $employees = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $employees,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'employment_status' => 'required|in:active,inactive,terminated,resigned',
        ]);

        // Create user account
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make('password123'), // Default password
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

        $employee->load(['user', 'department']);

        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully',
            'data' => $employee,
        ], 201);
    }

    /**
     * Display the specified employee
     */
    public function show(Employee $employee)
    {
        $employee->load(['user', 'department', 'payrolls']);

        return response()->json([
            'success' => true,
            'data' => $employee,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified employee
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'position' => 'sometimes|string|max:255',
            'department_id' => 'sometimes|exists:departments,id',
            'salary' => 'sometimes|numeric|min:0',
            'employment_status' => 'sometimes|in:active,inactive,terminated,resigned',
            'termination_date' => 'nullable|date',
            'termination_reason' => 'nullable|string',
        ]);

        $employee->update($request->all());

        // Update user name if first_name or last_name changed
        if ($request->has('first_name') || $request->has('last_name')) {
            $employee->user->update([
                'name' => $employee->first_name . ' ' . $employee->last_name,
            ]);
        }

        $employee->load(['user', 'department']);

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully',
            'data' => $employee,
        ]);
    }

    /**
     * Remove the specified employee
     */
    public function destroy(Employee $employee)
    {
        // Soft delete by updating employment status
        $employee->update([
            'employment_status' => 'terminated',
            'termination_date' => now(),
            'termination_reason' => 'Deleted by admin',
        ]);

        // Deactivate user account
        $employee->user->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Employee terminated successfully',
        ]);
    }

    /**
     * Complete employee onboarding
     */
    public function completeOnboarding(Employee $employee)
    {
        $employee->update(['is_onboarded' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Employee onboarding completed successfully',
            'data' => $employee,
        ]);
    }

    /**
     * Get employee statistics
     */
    public function statistics()
    {
        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('employment_status', 'active')->count(),
            'onboarded_employees' => Employee::where('is_onboarded', true)->count(),
            'pending_onboarding' => Employee::where('is_onboarded', false)->count(),
            'departments' => Department::active()->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Get employees by department
     */
    public function byDepartment(Department $department)
    {
        $employees = $department->employees()->with(['user'])->get();

        return response()->json([
            'success' => true,
            'data' => [
                'department' => $department,
                'employees' => $employees,
            ],
        ]);
    }
}
