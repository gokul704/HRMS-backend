<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Employee;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Leave::with(['employee.department', 'appliedBy', 'approvedBy']);

        // Filter based on user role
        if ($user->isEmployee()) {
            // Employees can only see their own leaves
            $query->where('employee_id', $user->employee->id);
        } elseif ($user->isManager()) {
            // Managers can see leaves from their department
            if ($user->employee && $user->employee->department) {
                $query->byDepartment($user->employee->department_id);
            }
        }
        // HR can see all leaves (no additional filter)

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('end_date', '<=', $request->date_to);
        }

        $leaves = $query->latest()->paginate(15);
        $departments = Department::all();

        return view('leaves.index', compact('leaves', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Only employees can create leave requests
        if (!$user->isEmployee()) {
            return redirect()->route('leaves.index')->with('error', 'Only employees can create leave requests.');
        }

        $leaveTypes = [
            'annual' => 'Annual Leave',
            'sick' => 'Sick Leave',
            'casual' => 'Casual Leave',
            'maternity' => 'Maternity Leave',
            'paternity' => 'Paternity Leave',
            'other' => 'Other Leave'
        ];

        return view('leaves.create', compact('leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Only employees can create leave requests
        if (!$user->isEmployee()) {
            return redirect()->route('leaves.index')->with('error', 'Only employees can create leave requests.');
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

        // Get the employee's manager
        $manager = null;
        if ($user->employee && $user->employee->department) {
            $manager = User::whereHas('employee', function($query) use ($user) {
                $query->where('department_id', $user->employee->department_id)
                      ->where('position', 'like', '%manager%');
            })->first();
        }

        try {
            DB::beginTransaction();

            $leave = Leave::create([
                'employee_id' => $user->employee->id,
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_days' => $totalDays,
                'reason' => $request->reason,
                'status' => 'pending',
                'manager_id' => $manager ? $manager->id : null,
                'applied_by' => $user->id,
            ]);

            DB::commit();

            return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to submit leave request. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        $user = Auth::user();

        // Check if user has permission to view this leave
        if ($user->isEmployee() && $leave->employee_id !== $user->employee->id) {
            return redirect()->route('leaves.index')->with('error', 'You can only view your own leave requests.');
        }

        if ($user->isManager() && $leave->employee->department_id !== $user->employee->department_id) {
            return redirect()->route('leaves.index')->with('error', 'You can only view leaves from your department.');
        }

        return view('leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leave $leave)
    {
        $user = Auth::user();

        // Only the employee who created the leave can edit it (if it's still pending)
        if (!$user->isEmployee() || $leave->employee_id !== $user->employee->id) {
            return redirect()->route('leaves.index')->with('error', 'You can only edit your own leave requests.');
        }

        if ($leave->status !== 'pending') {
            return redirect()->route('leaves.index')->with('error', 'You can only edit pending leave requests.');
        }

        $leaveTypes = [
            'annual' => 'Annual Leave',
            'sick' => 'Sick Leave',
            'casual' => 'Casual Leave',
            'maternity' => 'Maternity Leave',
            'paternity' => 'Paternity Leave',
            'other' => 'Other Leave'
        ];

        return view('leaves.edit', compact('leave', 'leaveTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leave $leave)
    {
        $user = Auth::user();

        // Only the employee who created the leave can update it (if it's still pending)
        if (!$user->isEmployee() || $leave->employee_id !== $user->employee->id) {
            return redirect()->route('leaves.index')->with('error', 'You can only update your own leave requests.');
        }

        if ($leave->status !== 'pending') {
            return redirect()->route('leaves.index')->with('error', 'You can only update pending leave requests.');
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

        try {
            $leave->update([
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_days' => $totalDays,
                'reason' => $request->reason,
            ]);

            return redirect()->route('leaves.index')->with('success', 'Leave request updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update leave request. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        $user = Auth::user();

        // Only the employee who created the leave can delete it (if it's still pending)
        if (!$user->isEmployee() || $leave->employee_id !== $user->employee->id) {
            return redirect()->route('leaves.index')->with('error', 'You can only delete your own leave requests.');
        }

        if ($leave->status !== 'pending') {
            return redirect()->route('leaves.index')->with('error', 'You can only delete pending leave requests.');
        }

        try {
            $leave->delete();
            return redirect()->route('leaves.index')->with('success', 'Leave request cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to cancel leave request. Please try again.');
        }
    }

    /**
     * Manager approval actions
     */
    public function managerApproval()
    {
        $user = Auth::user();

        if (!$user->isManager()) {
            return redirect()->route('leaves.index')->with('error', 'Only managers can access approval page.');
        }

        $pendingLeaves = Leave::with(['employee', 'appliedBy'])
            ->where('status', 'pending')
            ->whereHas('employee', function($query) use ($user) {
                $query->where('department_id', $user->employee->department_id);
            })
            ->latest()
            ->paginate(15);

        return view('leaves.manager-approval', compact('pendingLeaves'));
    }

    public function approveByManager(Request $request, Leave $leave)
    {
        $user = Auth::user();

        if (!$user->isManager()) {
            return redirect()->route('leaves.index')->with('error', 'Only managers can approve leaves.');
        }

        // Check if the leave belongs to manager's department
        if ($leave->employee->department_id !== $user->employee->department_id) {
            return redirect()->route('leaves.index')->with('error', 'You can only approve leaves from your department.');
        }

        if ($leave->status !== 'pending') {
            return redirect()->route('leaves.index')->with('error', 'This leave request has already been processed.');
        }

        $request->validate([
            'manager_remarks' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $leave->update([
                'status' => 'manager_approved',
                'manager_approved_at' => now(),
                'manager_remarks' => $request->manager_remarks,
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('leaves.manager-approval')->with('success', 'Leave request approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to approve leave request. Please try again.');
        }
    }

    public function rejectByManager(Request $request, Leave $leave)
    {
        $user = Auth::user();

        if (!$user->isManager()) {
            return redirect()->route('leaves.index')->with('error', 'Only managers can reject leaves.');
        }

        // Check if the leave belongs to manager's department
        if ($leave->employee->department_id !== $user->employee->department_id) {
            return redirect()->route('leaves.index')->with('error', 'You can only reject leaves from your department.');
        }

        if ($leave->status !== 'pending') {
            return redirect()->route('leaves.index')->with('error', 'This leave request has already been processed.');
        }

        $request->validate([
            'manager_remarks' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $leave->update([
                'status' => 'manager_rejected',
                'manager_approved_at' => now(),
                'manager_remarks' => $request->manager_remarks,
                'approved_by' => $user->id,
                'approved_at' => now(),
                'rejection_reason' => $request->manager_remarks,
            ]);

            DB::commit();

            return redirect()->route('leaves.manager-approval')->with('success', 'Leave request rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to reject leave request. Please try again.');
        }
    }

    /**
     * HR approval actions
     */
    public function hrApproval()
    {
        $user = Auth::user();

        if (!$user->isHr()) {
            return redirect()->route('leaves.index')->with('error', 'Only HR can access approval page.');
        }

        // HR can see manager-approved leaves and leaves where manager is the applicant
        $pendingLeaves = Leave::with(['employee.department', 'appliedBy', 'manager'])
            ->where(function($query) {
                $query->where('status', 'manager_approved')
                      ->orWhere(function($q) {
                          $q->where('status', 'pending')
                            ->whereHas('appliedBy', function($subQuery) {
                                $subQuery->whereHas('employee', function($empQuery) {
                                    $empQuery->where('position', 'like', '%manager%');
                                });
                            });
                      });
            })
            ->latest()
            ->paginate(15);

        return view('leaves.hr-approval', compact('pendingLeaves'));
    }

    public function approveByHr(Request $request, Leave $leave)
    {
        $user = Auth::user();

        if (!$user->isHr()) {
            return redirect()->route('leaves.index')->with('error', 'Only HR can approve leaves.');
        }

        if (!in_array($leave->status, ['manager_approved', 'pending'])) {
            return redirect()->route('leaves.index')->with('error', 'This leave request cannot be approved.');
        }

        // If it's a manager's leave request (pending), it can be approved directly
        // If it's an employee's leave request (manager_approved), it can be approved after manager approval
        if ($leave->status === 'pending' && $leave->appliedBy->isManager()) {
            // Manager's leave request - can be approved directly
            $newStatus = 'hr_approved';
        } elseif ($leave->status === 'manager_approved') {
            // Employee's leave request - approved by manager, now approved by HR
            $newStatus = 'hr_approved';
        } else {
            return redirect()->route('leaves.index')->with('error', 'This leave request cannot be approved.');
        }

        $request->validate([
            'hr_remarks' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $leave->update([
                'status' => $newStatus,
                'hr_approved_at' => now(),
                'hr_remarks' => $request->hr_remarks,
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('leaves.hr-approval')->with('success', 'Leave request approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to approve leave request. Please try again.');
        }
    }

    public function rejectByHr(Request $request, Leave $leave)
    {
        $user = Auth::user();

        if (!$user->isHr()) {
            return redirect()->route('leaves.index')->with('error', 'Only HR can reject leaves.');
        }

        if (!in_array($leave->status, ['manager_approved', 'pending'])) {
            return redirect()->route('leaves.index')->with('error', 'This leave request cannot be rejected.');
        }

        $request->validate([
            'hr_remarks' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $leave->update([
                'status' => 'hr_rejected',
                'hr_approved_at' => now(),
                'hr_remarks' => $request->hr_remarks,
                'approved_by' => $user->id,
                'approved_at' => now(),
                'rejection_reason' => $request->hr_remarks,
            ]);

            DB::commit();

            return redirect()->route('leaves.hr-approval')->with('success', 'Leave request rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to reject leave request. Please try again.');
        }
    }

    /**
     * Statistics for dashboard
     */
    public function statistics()
    {
        $user = Auth::user();

        if (!$user->isHr() && !$user->isManager()) {
            return redirect()->route('leaves.index')->with('error', 'Access denied.');
        }

        $query = Leave::query();

        if ($user->isManager()) {
            $query->byDepartment($user->employee->department_id);
        }

        $statistics = [
            'total_leaves' => $query->count(),
            'pending_leaves' => (clone $query)->where('status', 'pending')->count(),
            'approved_leaves' => (clone $query)->whereIn('status', ['manager_approved', 'hr_approved'])->count(),
            'rejected_leaves' => (clone $query)->whereIn('status', ['manager_rejected', 'hr_rejected'])->count(),
        ];

        return view('leaves.statistics', compact('statistics'));
    }
}
