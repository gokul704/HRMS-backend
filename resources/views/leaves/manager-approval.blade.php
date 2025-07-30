@extends('layouts.app')
@section('title', 'Manager Approvals - StaffIQ')
@section('page-title', 'Manager Approvals')

@section('page-actions')
    <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Leaves
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pending Leave Approvals</h5>
            <span class="badge bg-primary">{{ $pendingLeaves->total() }} pending requests</span>
        </div>
    </div>
    <div class="card-body">
        @if($pendingLeaves->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Employee</th>
                            <th>Leave Details</th>
                            <th>Reason</th>
                            <th>Applied Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingLeaves as $leave)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</div>
                                        <small class="text-muted">{{ $leave->employee->position }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="leave-info">
                                        <span class="badge bg-info mb-2">{{ $leave->leave_type_label }}</span>
                                        <div class="date-range">
                                            <div>{{ $leave->start_date->format('M d, Y') }}</div>
                                            <div class="text-muted">to {{ $leave->end_date->format('M d, Y') }}</div>
                                            <div class="text-muted">{{ $leave->total_days }} days</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="reason-text">
                                        {{ Str::limit($leave->reason, 100) }}
                                        @if(strlen($leave->reason) > 100)
                                            <button type="button" class="btn btn-link btn-sm p-0 ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $leave->reason }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <div>{{ $leave->created_at->format('M d, Y') }}</div>
                                        <div class="text-muted">{{ $leave->created_at->format('H:i') }}</div>
                                        <div class="text-muted">By: {{ $leave->appliedBy->name }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('leaves.show', $leave) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                data-bs-toggle="modal" data-bs-target="#approveModal{{ $leave->id }}" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>

                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $leave->id }}" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Approve Modal -->
                            <div class="modal fade" id="approveModal{{ $leave->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Approve Leave Request</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('leaves.manager-approve', $leave) }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <div class="alert alert-info">
                                                    <h6 class="alert-heading">Leave Request Details</h6>
                                                    <p class="mb-1"><strong>Employee:</strong> {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</p>
                                                    <p class="mb-1"><strong>Leave Type:</strong> {{ $leave->leave_type_label }}</p>
                                                    <p class="mb-1"><strong>Duration:</strong> {{ $leave->start_date->format('M d, Y') }} to {{ $leave->end_date->format('M d, Y') }} ({{ $leave->total_days }} days)</p>
                                                    <p class="mb-0"><strong>Reason:</strong> {{ $leave->reason }}</p>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="manager_remarks{{ $leave->id }}" class="form-label">Remarks (Optional)</label>
                                                    <textarea name="manager_remarks" id="manager_remarks{{ $leave->id }}" class="form-control" rows="3"
                                                              placeholder="Add any remarks or comments..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check me-2"></i>Approve Leave
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Leave Request</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('leaves.manager-reject', $leave) }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <div class="alert alert-warning">
                                                    <h6 class="alert-heading">Leave Request Details</h6>
                                                    <p class="mb-1"><strong>Employee:</strong> {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</p>
                                                    <p class="mb-1"><strong>Leave Type:</strong> {{ $leave->leave_type_label }}</p>
                                                    <p class="mb-1"><strong>Duration:</strong> {{ $leave->start_date->format('M d, Y') }} to {{ $leave->end_date->format('M d, Y') }} ({{ $leave->total_days }} days)</p>
                                                    <p class="mb-0"><strong>Reason:</strong> {{ $leave->reason }}</p>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="rejection_remarks{{ $leave->id }}" class="form-label">Rejection Reason *</label>
                                                    <textarea name="manager_remarks" id="rejection_remarks{{ $leave->id }}" class="form-control" rows="3"
                                                              placeholder="Please provide a reason for rejection..." required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-times me-2"></i>Reject Leave
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $pendingLeaves->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-check-circle fa-3x text-success"></i>
                </div>
                <h5 class="text-success">No Pending Approvals</h5>
                <p class="text-muted">All leave requests from your department have been processed.</p>
            </div>
        @endif
    </div>
</div>

<style>
.leave-info {
    font-size: 0.875rem;
}

.date-range {
    margin-top: 0.5rem;
}

.reason-text {
    font-size: 0.875rem;
    max-width: 200px;
}

.date-info {
    font-size: 0.875rem;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>

<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
