@extends('layouts.app')
@section('title', 'Leave Management - StaffIQ')
@section('page-title', 'Leave Management')

@section('page-actions')
    @if(auth()->user()->isEmployee())
        <a href="{{ route('leaves.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Request Leave
        </a>
    @endif
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Leave Requests</h5>
            </div>
            <div class="col-md-6">
                <button class="btn btn-outline-secondary btn-sm float-end" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="fas fa-filter me-2"></i>Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="collapse" id="filterCollapse">
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('leaves.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="manager_approved" {{ request('status') == 'manager_approved' ? 'selected' : '' }}>Manager Approved</option>
                        <option value="manager_rejected" {{ request('status') == 'manager_rejected' ? 'selected' : '' }}>Manager Rejected</option>
                        <option value="hr_approved" {{ request('status') == 'hr_approved' ? 'selected' : '' }}>HR Approved</option>
                        <option value="hr_rejected" {{ request('status') == 'hr_rejected' ? 'selected' : '' }}>HR Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="leave_type" class="form-label">Leave Type</label>
                    <select name="leave_type" id="leave_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="annual" {{ request('leave_type') == 'annual' ? 'selected' : '' }}>Annual Leave</option>
                        <option value="sick" {{ request('leave_type') == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                        <option value="casual" {{ request('leave_type') == 'casual' ? 'selected' : '' }}>Casual Leave</option>
                        <option value="maternity" {{ request('leave_type') == 'maternity' ? 'selected' : '' }}>Maternity Leave</option>
                        <option value="paternity" {{ request('leave_type') == 'paternity' ? 'selected' : '' }}>Paternity Leave</option>
                        <option value="other" {{ request('leave_type') == 'other' ? 'selected' : '' }}>Other Leave</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search me-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary btn-sm ms-2">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card-body">
        @if($leaves->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Applied By</th>
                            <th>Applied Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaves as $leave)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</div>
                                        <small class="text-muted">{{ $leave->employee->department->name ?? 'No Department' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $leave->leave_type_label }}</span>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <div>{{ $leave->start_date->format('M d, Y') }}</div>
                                        <div class="text-muted">to {{ $leave->end_date->format('M d, Y') }}</div>
                                        <div class="text-muted">{{ $leave->total_days }} days</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $leave->status_badge }}">
                                        {{ $leave->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="applicant-info">
                                        <div class="fw-bold">{{ $leave->appliedBy->name }}</div>
                                        <small class="text-muted">{{ ucfirst($leave->appliedBy->role) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <div>{{ $leave->created_at->format('M d, Y') }}</div>
                                        <div class="text-muted">{{ $leave->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('leaves.show', $leave) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if(auth()->user()->isEmployee() && $leave->employee_id === auth()->user()->employee->id && $leave->status === 'pending')
                                            <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('leaves.destroy', $leave) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this leave request?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancel">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $leaves->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-calendar-times fa-3x text-muted"></i>
                </div>
                <h5 class="text-muted">No Leave Requests Found</h5>
                <p class="text-muted">No leave requests match your current filters.</p>
                @if(auth()->user()->isEmployee())
                    <a href="{{ route('leaves.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Request Your First Leave
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Quick Actions Card -->
@if(auth()->user()->isManager() || auth()->user()->isHr())
<div class="row mt-4">
    <div class="col-md-6">
        @if(auth()->user()->isManager())
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i>Manager Approvals</h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Review and approve leave requests from your department employees.</p>
                <a href="{{ route('leaves.manager-approval') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-2"></i>Go to Approvals
                </a>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-6">
        @if(auth()->user()->isHr())
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-user-tie me-2"></i>HR Approvals</h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Review and approve leave requests approved by managers or from managers.</p>
                <a href="{{ route('leaves.hr-approval') }}" class="btn btn-success">
                    <i class="fas fa-arrow-right me-2"></i>Go to HR Approvals
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endif

<style>
.date-info {
    font-size: 0.875rem;
}

.applicant-info {
    font-size: 0.875rem;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endsection
