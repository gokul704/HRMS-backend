@extends('layouts.app')

@section('title', 'Manager Reports - StaffIQ Enterprise')
@section('page-title', 'Manager Reports')
@section('page-subtitle', 'Department-specific analytics and leave management')

@section('content')
<!-- Department Overview -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="metric-card primary">
            <div class="metric-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ $departmentEmployees }}</div>
                <div class="metric-label">Total Employees</div>
                <div class="metric-trend positive">
                    <i class="fas fa-check"></i>
                    <span>{{ $departmentActiveEmployees }} active</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="metric-card warning">
            <div class="metric-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ $totalDepartmentLeaves }}</div>
                <div class="metric-label">Total Leaves</div>
                <div class="metric-trend neutral">
                    <i class="fas fa-clock"></i>
                    <span>{{ $pendingDepartmentLeaves }} pending</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="metric-card success">
            <div class="metric-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ $approvedDepartmentLeaves }}</div>
                <div class="metric-label">Approved Leaves</div>
                <div class="metric-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>This year</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="metric-card info">
            <div class="metric-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ $managerDepartment->name }}</div>
                <div class="metric-label">Department</div>
                <div class="metric-trend positive">
                    <i class="fas fa-check"></i>
                    <span>{{ $managerDepartment->status }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Department Employees with Leave Statistics -->
<div class="row mb-4">
    <div class="col-12">
        <div class="enterprise-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Department Employees - Leave Statistics
                </h5>
            </div>
            <div class="card-body">
                @if($departmentEmployeesWithLeaves->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Total Leaves</th>
                                <th>Approved</th>
                                <th>Pending</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departmentEmployeesWithLeaves as $employee)
                            <tr>
                                <td>
                                    <div class="employee-info">
                                        <div class="employee-name">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                        <div class="employee-id">ID: {{ $employee->employee_id ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="position-badge">{{ $employee->position ?? 'Not set' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $employee->employment_status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($employee->employment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="leave-count">
                                        <span class="count-value">{{ $employee->total_leaves }}</span>
                                        <span class="count-label">leaves</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="leave-count approved">
                                        <span class="count-value">{{ $employee->approved_leaves }}</span>
                                        <span class="count-label">approved</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="leave-count pending">
                                        <span class="count-value">{{ $employee->pending_leaves }}</span>
                                        <span class="count-label">pending</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="btn btn-sm btn-outline-primary" title="View Employee">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-outline-info" title="View Leaves">
                                            <i class="fas fa-calendar"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5>No Employees Found</h5>
                    <p>No employees are currently assigned to this department.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Department Leaves -->
<div class="row">
    <div class="col-12">
        <div class="enterprise-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Recent Leave Applications
                </h5>
                <div class="header-actions">
                    <a href="{{ route('reports.leave') }}" class="btn btn-primary">
                        <i class="fas fa-list me-2"></i>
                        View All Leaves
                    </a>
                    <a href="{{ route('reports.leave.export') }}" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>
                        Export Report
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recentDepartmentLeaves->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>Duration</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Applied Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDepartmentLeaves as $leave)
                            <tr>
                                <td>
                                    <div class="employee-info">
                                        <div class="employee-name">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</div>
                                        <div class="employee-position">{{ $leave->employee->position ?? 'No position' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $leave->leave_type_label }}</span>
                                </td>
                                <td>
                                    <div class="leave-duration">
                                        <div>{{ $leave->start_date->format('M d, Y') }}</div>
                                        <div class="text-muted">to {{ $leave->end_date->format('M d, Y') }}</div>
                                        <div class="text-muted">{{ $leave->total_days }} days</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="leave-reason">
                                        {{ Str::limit($leave->reason, 50) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $leave->status_badge }}">
                                        {{ ucfirst($leave->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <div>{{ $leave->created_at->format('M d, Y') }}</div>
                                        <div class="text-muted">{{ $leave->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($leave->status === 'pending')
                                        <button class="btn btn-sm btn-outline-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h5>No Recent Leave Applications</h5>
                    <p>No leave applications have been submitted by department employees recently.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Leave Type Distribution -->
@if($leaveTypeDistribution->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="enterprise-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Leave Type Distribution
                </h5>
            </div>
            <div class="card-body">
                <div class="leave-type-distribution">
                    @php
                        $totalLeaves = $leaveTypeDistribution->sum('count');
                    @endphp
                    @foreach($leaveTypeDistribution as $type)
                    @php
                        $percentage = $totalLeaves > 0 ? round(($type->count / $totalLeaves) * 100, 1) : 0;
                        $leaveTypeColors = [
                            'annual' => ['bg' => 'bg-primary', 'icon' => 'fas fa-sun', 'text' => 'Annual Leave'],
                            'sick' => ['bg' => 'bg-danger', 'icon' => 'fas fa-heartbeat', 'text' => 'Sick Leave'],
                            'casual' => ['bg' => 'bg-info', 'icon' => 'fas fa-coffee', 'text' => 'Casual Leave'],
                            'maternity' => ['bg' => 'bg-success', 'icon' => 'fas fa-baby', 'text' => 'Maternity Leave'],
                            'paternity' => ['bg' => 'bg-warning', 'icon' => 'fas fa-male', 'text' => 'Paternity Leave'],
                            'unpaid' => ['bg' => 'bg-secondary', 'icon' => 'fas fa-calendar-times', 'text' => 'Unpaid Leave'],
                            'other' => ['bg' => 'bg-dark', 'icon' => 'fas fa-calendar-alt', 'text' => 'Other Leave']
                        ];
                        $leaveType = strtolower($type->leave_type);
                        $color = $leaveTypeColors[$leaveType] ?? $leaveTypeColors['other'];
                    @endphp
                    <div class="leave-type-card">
                        <div class="leave-type-header">
                            <div class="leave-type-icon {{ $color['bg'] }}">
                                <i class="{{ $color['icon'] }}"></i>
                            </div>
                            <div class="leave-type-details">
                                <h6 class="leave-type-name">{{ $color['text'] }}</h6>
                                <p class="leave-type-count">{{ $type->count }} applications</p>
                            </div>
                            <div class="leave-type-percentage">
                                <span class="percentage-badge">{{ $percentage }}%</span>
                            </div>
                        </div>
                        <div class="leave-type-progress">
                            <div class="progress">
                                <div class="progress-bar {{ $color['bg'] }}"
                                     style="width: {{ $percentage }}%"
                                     role="progressbar"
                                     aria-valuenow="{{ $percentage }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    /* Employee Info */
    .employee-info {
        display: flex;
        flex-direction: column;
    }

    .employee-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .employee-id {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .employee-position {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Position Badge */
    .position-badge {
        background: var(--light-bg);
        color: var(--text-secondary);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Leave Count */
    .leave-count {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .count-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
    }

    .count-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .leave-count.approved .count-value {
        color: var(--success-color);
    }

    .leave-count.pending .count-value {
        color: var(--warning-color);
    }

    /* Leave Duration */
    .leave-duration {
        display: flex;
        flex-direction: column;
    }

    /* Leave Reason */
    .leave-reason {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Date Info */
    .date-info {
        display: flex;
        flex-direction: column;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.25rem;
    }

    .action-buttons .btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        font-size: 0.75rem;
    }

    /* Leave Type Distribution */
    .leave-type-distribution {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .leave-type-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .leave-type-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    .leave-type-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .leave-type-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        margin-right: 1rem;
    }

    .leave-type-details {
        flex: 1;
    }

    .leave-type-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }

    .leave-type-count {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0;
    }

    .leave-type-percentage {
        text-align: right;
    }

    .percentage-badge {
        background: #f8f9fa;
        color: var(--text-primary);
        padding: 0.375rem 0.75rem;
        border-radius: 1rem;
        font-weight: 600;
        font-size: 0.875rem;
        border: 1px solid #e9ecef;
    }

    .leave-type-progress {
        margin-top: 0.5rem;
    }

    .progress {
        height: 8px;
        background-color: #f8f9fa;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        border-radius: 4px;
        transition: width 0.6s ease;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .leave-type-distribution {
            grid-template-columns: 1fr;
        }

        .leave-type-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .leave-type-percentage {
            text-align: left;
        }

        .action-buttons {
            flex-direction: column;
        }

        .leave-reason {
            max-width: 150px;
        }
    }
</style>
@endpush
