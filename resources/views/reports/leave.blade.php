@extends('layouts.app')

@section('title', 'Leave Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                        <li class="breadcrumb-item active">Leave Reports</li>
                    </ol>
                </div>
                <h4 class="page-title">Leave Reports</h4>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Filters</h5>
                    <form method="GET" action="{{ route('reports.leave') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="department_id" class="form-label">Department</label>
                            <select name="department_id" id="department_id" class="form-select">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="manager_approved" {{ request('status') == 'manager_approved' ? 'selected' : '' }}>Manager Approved</option>
                                <option value="hr_approved" {{ request('status') == 'hr_approved' ? 'selected' : '' }}>HR Approved</option>
                                <option value="manager_rejected" {{ request('status') == 'manager_rejected' ? 'selected' : '' }}>Manager Rejected</option>
                                <option value="hr_rejected" {{ request('status') == 'hr_rejected' ? 'selected' : '' }}>HR Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-filter"></i> Filter
                                </button>
                                <a href="{{ route('reports.leave') }}" class="btn btn-outline-secondary">
                                    <i class="mdi mdi-refresh"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded bg-primary">
                                <i class="mdi mdi-calendar font-size-20 text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $totalLeaves }}</h5>
                            <p class="text-muted mb-0">Total Leaves</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded bg-warning">
                                <i class="mdi mdi-clock font-size-20 text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $pendingLeaves }}</h5>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded bg-success">
                                <i class="mdi mdi-check font-size-20 text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $approvedLeaves }}</h5>
                            <p class="text-muted mb-0">Approved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded bg-danger">
                                <i class="mdi mdi-close font-size-20 text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $rejectedLeaves }}</h5>
                            <p class="text-muted mb-0">Rejected</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Export Reports</h5>
                        <div class="btn-group" role="group">
                            <a href="{{ route('reports.leave.export.excel') }}?{{ http_build_query(request()->all()) }}"
                               class="btn btn-success">
                                <i class="mdi mdi-file-excel"></i> Export to Excel
                            </a>
                            <a href="{{ route('reports.leave.export.pdf') }}?{{ http_build_query(request()->all()) }}"
                               class="btn btn-danger">
                                <i class="mdi mdi-file-pdf"></i> Export to PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Leave Applications</h5>

                    @if($leaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-centered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Department</th>
                                        <th>Leave Type</th>
                                        <th>Duration</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Applied Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaves as $leave)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm rounded-circle bg-light me-2">
                                                        <span class="avatar-title text-primary">
                                                            {{ strtoupper(substr($leave->employee->first_name, 0, 1)) }}{{ strtoupper(substr($leave->employee->last_name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</h6>
                                                        <small class="text-muted">{{ $leave->employee->employee_id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $leave->employee->department->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $leave->leave_type_label }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $leave->start_date->format('M d, Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">to {{ $leave->end_date->format('M d, Y') }}</small>
                                                    <br>
                                                    <small class="text-muted">{{ $leave->total_days }} days</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $leave->reason }}">
                                                    {{ $leave->reason }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $leave->status_badge }}">
                                                    {{ $leave->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $leave->created_at->format('M d, Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $leave->created_at->format('H:i') }}</small>
                                                    <br>
                                                    <small class="text-muted">By: {{ $leave->appliedBy->name ?? 'N/A' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal" data-bs-target="#leaveModal{{ $leave->id }}">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button>

                                                    @if(auth()->user()->isManager())
                                                        <a href="{{ route('web.employees.show', $leave->employee->id) }}"
                                                           class="btn btn-sm btn-outline-info" title="View Employee">
                                                            <i class="mdi mdi-account"></i>
                                                        </a>
                                                        <a href="{{ route('leaves.index', ['employee_id' => $leave->employee->id]) }}"
                                                           class="btn btn-sm btn-outline-warning" title="View Employee Leaves">
                                                            <i class="mdi mdi-calendar-multiple"></i>
                                                        </a>
                                                    @endif

                                                    @if($leave->status === 'pending' && auth()->user()->isHr())
                                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                                onclick="approveLeave({{ $leave->id }})">
                                                            <i class="mdi mdi-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                                onclick="rejectLeave({{ $leave->id }})">
                                                            <i class="mdi mdi-close"></i>
                                                        </button>
                                                    @endif

                                                    @if($leave->status === 'pending' && auth()->user()->isManager())
                                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                                onclick="approveLeave({{ $leave->id }})" title="Approve Leave">
                                                            <i class="mdi mdi-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                                onclick="rejectLeave({{ $leave->id }})" title="Reject Leave">
                                                            <i class="mdi mdi-close"></i>
                                                        </button>
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
                            {{ $leaves->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="mdi mdi-calendar-remove font-size-48 text-muted"></i>
                            </div>
                            <h5>No Leave Applications Found</h5>
                            <p class="text-muted">No leave applications match your current filters.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leave Detail Modals -->
@foreach($leaves as $leave)
    <div class="modal fade" id="leaveModal{{ $leave->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Leave Application Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Employee Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Employee ID:</strong></td>
                                    <td>{{ $leave->employee->employee_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Department:</strong></td>
                                    <td>{{ $leave->employee->department->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Position:</strong></td>
                                    <td>{{ $leave->employee->position }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Leave Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Leave Type:</strong></td>
                                    <td>{{ $leave->leave_type_label }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Start Date:</strong></td>
                                    <td>{{ $leave->start_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>End Date:</strong></td>
                                    <td>{{ $leave->end_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Days:</strong></td>
                                    <td>{{ $leave->total_days }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $leave->status_badge }}">
                                            {{ $leave->status_label }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Reason</h6>
                            <p class="border rounded p-3 bg-light">{{ $leave->reason }}</p>
                        </div>
                    </div>
                    @if($leave->approved_at)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Approval Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Approved By:</strong></td>
                                        <td>{{ $leave->approvedBy->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Approved Date:</strong></td>
                                        <td>{{ $leave->approved_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
function approveLeave(leaveId) {
    if (confirm('Are you sure you want to approve this leave application?')) {
        // Create a form to submit the approval
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("reports.leave.approve", ":leaveId") }}'.replace(':leaveId', leaveId);

        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        document.body.appendChild(form);
        form.submit();
    }
}

function rejectLeave(leaveId) {
    if (confirm('Are you sure you want to reject this leave application?')) {
        // Create a form to submit the rejection
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("reports.leave.reject", ":leaveId") }}'.replace(':leaveId', leaveId);

        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
