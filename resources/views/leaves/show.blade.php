@extends('layouts.app')
@section('title', 'Leave Details - StaffIQ')
@section('page-title', 'Leave Details')

@section('page-actions')
    <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Leaves
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Leave Request Details</h5>
                    <span class="badge bg-{{ $leave->status_badge }} fs-6">
                        {{ $leave->status_label }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Employee Information</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Employee Name</label>
                            <p class="mb-0">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Employee ID</label>
                            <p class="mb-0">{{ $leave->employee->employee_id }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Department</label>
                            <p class="mb-0">{{ $leave->employee->department->name ?? 'No Department' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Position</label>
                            <p class="mb-0">{{ $leave->employee->position }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Leave Information</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Leave Type</label>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ $leave->leave_type_label }}</span>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Duration</label>
                            <p class="mb-0">
                                {{ $leave->start_date->format('M d, Y') }} to {{ $leave->end_date->format('M d, Y') }}
                                <br><small class="text-muted">{{ $leave->total_days }} days</small>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Reason</label>
                            <p class="mb-0">{{ $leave->reason }}</p>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Application Details</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Applied By</label>
                            <p class="mb-0">{{ $leave->appliedBy->name }} ({{ ucfirst($leave->appliedBy->role) }})</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Applied Date</label>
                            <p class="mb-0">{{ $leave->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Approval Information</h6>
                        @if($leave->approvedBy)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Approved By</label>
                                <p class="mb-0">{{ $leave->approvedBy->name }} ({{ ucfirst($leave->approvedBy->role) }})</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Approval Date</label>
                                <p class="mb-0">{{ $leave->approved_at->format('M d, Y H:i') }}</p>
                            </div>
                        @else
                            <p class="text-muted mb-0">Not yet approved</p>
                        @endif
                    </div>
                </div>

                @if($leave->manager_remarks || $leave->hr_remarks)
                <hr>
                <div class="row">
                    @if($leave->manager_remarks)
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Manager Remarks</h6>
                        <div class="alert alert-info">
                            <p class="mb-0">{{ $leave->manager_remarks }}</p>
                            <small class="text-muted">{{ $leave->manager_approved_at ? $leave->manager_approved_at->format('M d, Y H:i') : '' }}</small>
                        </div>
                    </div>
                    @endif

                    @if($leave->hr_remarks)
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">HR Remarks</h6>
                        <div class="alert alert-success">
                            <p class="mb-0">{{ $leave->hr_remarks }}</p>
                            <small class="text-muted">{{ $leave->hr_approved_at ? $leave->hr_approved_at->format('M d, Y H:i') : '' }}</small>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                @if($leave->rejection_reason)
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">Rejection Reason</h6>
                        <div class="alert alert-danger">
                            <p class="mb-0">{{ $leave->rejection_reason }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Approval Actions -->
        @if(auth()->user()->isManager() && $leave->status === 'pending' && $leave->employee->department_id === auth()->user()->employee->department_id)
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i>Manager Approval</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('leaves.manager-approve', $leave) }}" class="mb-3">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="manager_remarks" class="form-label">Remarks (Optional)</label>
                        <textarea name="manager_remarks" id="manager_remarks" class="form-control" rows="3" placeholder="Add any remarks..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-check me-2"></i>Approve
                    </button>
                </form>

                <form method="POST" action="{{ route('leaves.manager-reject', $leave) }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="rejection_remarks" class="form-label">Rejection Reason *</label>
                        <textarea name="manager_remarks" id="rejection_remarks" class="form-control" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-times me-2"></i>Reject
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if(auth()->user()->isHr() && in_array($leave->status, ['manager_approved', 'pending']))
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-user-tie me-2"></i>HR Approval</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('leaves.hr-approve', $leave) }}" class="mb-3">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="hr_remarks" class="form-label">Remarks (Optional)</label>
                        <textarea name="hr_remarks" id="hr_remarks" class="form-control" rows="3" placeholder="Add any remarks..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-check me-2"></i>Approve
                    </button>
                </form>

                <form method="POST" action="{{ route('leaves.hr-reject', $leave) }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="hr_rejection_remarks" class="form-label">Rejection Reason *</label>
                        <textarea name="hr_remarks" id="hr_rejection_remarks" class="form-control" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-times me-2"></i>Reject
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Workflow Status -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-sitemap me-2"></i>Approval Workflow</h6>
            </div>
            <div class="card-body">
                <div class="workflow-steps">
                    <div class="step {{ $leave->status !== 'pending' ? 'completed' : 'current' }}">
                        <div class="step-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="step-content">
                            <h6 class="mb-1">Employee Request</h6>
                            <small class="text-muted">{{ $leave->created_at->format('M d, Y') }}</small>
                        </div>
                    </div>

                    @if($leave->appliedBy->isManager())
                        <!-- Manager's leave goes directly to HR -->
                        <div class="step {{ in_array($leave->status, ['hr_approved', 'hr_rejected']) ? 'completed' : ($leave->status === 'pending' ? 'current' : '') }}">
                            <div class="step-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="step-content">
                                <h6 class="mb-1">HR Approval</h6>
                                <small class="text-muted">
                                    @if($leave->hr_approved_at)
                                        {{ $leave->hr_approved_at->format('M d, Y') }}
                                    @else
                                        Pending
                                    @endif
                                </small>
                            </div>
                        </div>
                    @else
                        <!-- Employee's leave goes to Manager first, then HR -->
                        <div class="step {{ in_array($leave->status, ['manager_approved', 'manager_rejected', 'hr_approved', 'hr_rejected']) ? 'completed' : ($leave->status === 'pending' ? 'current' : '') }}">
                            <div class="step-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="step-content">
                                <h6 class="mb-1">Manager Approval</h6>
                                <small class="text-muted">
                                    @if($leave->manager_approved_at)
                                        {{ $leave->manager_approved_at->format('M d, Y') }}
                                    @else
                                        Pending
                                    @endif
                                </small>
                            </div>
                        </div>

                        @if(in_array($leave->status, ['manager_approved', 'hr_approved', 'hr_rejected']))
                        <div class="step {{ in_array($leave->status, ['hr_approved', 'hr_rejected']) ? 'completed' : 'current' }}">
                            <div class="step-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="step-content">
                                <h6 class="mb-1">HR Approval</h6>
                                <small class="text-muted">
                                    @if($leave->hr_approved_at)
                                        {{ $leave->hr_approved_at->format('M d, Y') }}
                                    @else
                                        Pending
                                    @endif
                                </small>
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.workflow-steps {
    position: relative;
}

.workflow-steps::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
    z-index: 1;
}

.step {
    position: relative;
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    z-index: 2;
}

.step:last-child {
    margin-bottom: 0;
}

.step-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: #6c757d;
    font-size: 14px;
}

.step.completed .step-icon {
    background: #28a745;
    color: white;
}

.step.current .step-icon {
    background: #007bff;
    color: white;
}

.step-content {
    flex: 1;
    padding-top: 5px;
}

.step-content h6 {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 600;
}

.step-content small {
    font-size: 0.75rem;
}
</style>
@endsection
