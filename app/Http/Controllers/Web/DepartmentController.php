<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments
     */
    public function index()
    {
        $departments = Department::withCount('employees')->paginate(10);
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created department
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        Department::create($request->all());

        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully!');
    }

    /**
     * Display the specified department
     */
    public function show(Department $department)
    {
        $department->load(['employees.user']);
        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified department
     */
    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified department
     */
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $department->update($request->all());

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully!');
    }

    /**
     * Remove the specified department
     */
    public function destroy(Department $department)
    {
        if ($department->employees()->count() > 0) {
            return back()->with('error', 'Cannot delete department with employees. Please reassign employees first.');
        }

        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully!');
    }

    /**
     * Show department statistics
     */
    public function statistics(Department $department)
    {
        $data = [
            'department' => $department,
            'employeeCount' => $department->employees()->count(),
            'activeEmployees' => $department->employees()->where('employment_status', 'active')->count(),
            'offerLetters' => $department->offerLetters()->count(),
            'pendingOffers' => $department->offerLetters()->where('status', 'draft')->count(),
        ];

        return view('departments.statistics', $data);
    }

    /**
     * Toggle department status
     */
    public function toggleStatus(Department $department)
    {
        $department->update(['is_active' => !$department->is_active]);

        $status = $department->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Department {$status} successfully!");
    }
}
