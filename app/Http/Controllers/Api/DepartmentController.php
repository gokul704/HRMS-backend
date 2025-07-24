<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments
     */
    public function index(Request $request)
    {
        $query = Department::with(['employees.user']);

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Search by name or location
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $departments = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $departments,
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
     * Store a newly created department
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $department = Department::create([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Department created successfully',
            'data' => $department,
        ], 201);
    }

    /**
     * Display the specified department
     */
    public function show(Department $department)
    {
        $department->load(['employees.user', 'offerLetters']);

        return response()->json([
            'success' => true,
            'data' => $department,
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
     * Update the specified department
     */
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $department->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Department updated successfully',
            'data' => $department,
        ]);
    }

    /**
     * Remove the specified department
     */
    public function destroy(Department $department)
    {
        // Check if department has employees
        if ($department->employees()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete department that has employees',
            ], 400);
        }

        // Check if department has offer letters
        if ($department->offerLetters()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete department that has offer letters',
            ], 400);
        }

        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully',
        ]);
    }

    /**
     * Get department statistics
     */
    public function statistics()
    {
        $departments = Department::withCount(['employees', 'offerLetters'])->get();

        $stats = [
            'total_departments' => Department::count(),
            'active_departments' => Department::where('is_active', true)->count(),
            'inactive_departments' => Department::where('is_active', false)->count(),
            'departments_with_employees' => $departments->where('employees_count', '>', 0)->count(),
            'departments_with_offers' => $departments->where('offer_letters_count', '>', 0)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'statistics' => $stats,
                'departments' => $departments,
            ],
        ]);
    }

    /**
     * Get departments with employee count
     */
    public function withEmployeeCount()
    {
        $departments = Department::withCount(['employees' => function ($query) {
            $query->where('employment_status', 'active');
        }])->get();

        return response()->json([
            'success' => true,
            'data' => $departments,
        ]);
    }

    /**
     * Toggle department active status
     */
    public function toggleStatus(Department $department)
    {
        $department->update(['is_active' => !$department->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Department status updated successfully',
            'data' => $department,
        ]);
    }
}
