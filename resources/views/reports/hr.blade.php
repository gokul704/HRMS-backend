@extends('layouts.app')

@section('title', 'HR Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">HR Reports</li>
                    </ol>
                </div>
                <h4 class="page-title">HR Reports Dashboard</h4>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded bg-primary">
                                <i class="mdi mdi-account-group font-size-20 text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $totalEmployees }}</h5>
                            <p class="text-muted mb-0">Total Employees</p>
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
                                <i class="mdi mdi-account-check font-size-20 text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $activeEmployees }}</h5>
                            <p class="text-muted mb-0">Active Employees</p>
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
                            <div class="avatar-sm rounded bg-info">
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
                                <i class="mdi mdi-cash-multiple font-size-20 text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $totalPayrolls }}</h5>
                            <p class="text-muted mb-0">Total Payrolls</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quick Actions</h5>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.leave') }}" class="btn btn-primary btn-block w-100">
                                <i class="mdi mdi-calendar-text"></i> Leave Reports
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.department') }}" class="btn btn-info btn-block w-100">
                                <i class="mdi mdi-domain"></i> Department Reports
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('web.payrolls.index') }}" class="btn btn-success btn-block w-100">
                                <i class="mdi mdi-cash-multiple"></i> Payroll Reports
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('web.employees.index') }}" class="btn btn-warning btn-block w-100">
                                <i class="mdi mdi-account-group"></i> Employee Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="row">
        <!-- Leave Statistics -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Leave Statistics</h5>
                        <div class="btn-group" role="group">
                            <a href="{{ route('reports.leave.export.excel') }}" class="btn btn-sm btn-outline-success">
                                <i class="mdi mdi-file-excel"></i> Excel
                            </a>
                            <a href="{{ route('reports.leave.export.pdf') }}" class="btn btn-sm btn-outline-danger">
                                <i class="mdi mdi-file-pdf"></i> PDF
                            </a>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-primary">{{ $totalLeaves }}</h4>
                                <p class="text-muted mb-0">Total Leaves</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-warning">{{ $pendingLeaves }}</h4>
                                <p class="text-muted mb-0">Pending</p>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center mt-3">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-success">{{ $approvedLeaves }}</h4>
                                <p class="text-muted mb-0">Approved</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-danger">{{ $rejectedLeaves }}</h4>
                                <p class="text-muted mb-0">Rejected</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payroll Statistics -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Payroll Statistics</h5>
                        <div class="btn-group" role="group">
                            <a href="{{ route('reports.payroll.export.excel') }}" class="btn btn-sm btn-outline-success">
                                <i class="mdi mdi-file-excel"></i> Excel
                            </a>
                            <a href="{{ route('reports.payroll.export.pdf') }}" class="btn btn-sm btn-outline-danger">
                                <i class="mdi mdi-file-pdf"></i> PDF
                            </a>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-primary">{{ $totalPayrolls }}</h4>
                                <p class="text-muted mb-0">Total Payrolls</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-success">{{ $paidPayrolls }}</h4>
                                <p class="text-muted mb-0">Paid</p>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center mt-3">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-warning">{{ $pendingPayrolls }}</h4>
                                <p class="text-muted mb-0">Pending</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-info">{{ $totalOffers }}</h4>
                                <p class="text-muted mb-0">Offer Letters</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Recent Leave Requests</h5>
                    @if($recentLeaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Type</th>
                                        <th>Dates</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLeaves->take(5) as $leave)
                                        <tr>
                                            <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                                            <td><span class="badge bg-info">{{ $leave->leave_type_label }}</span></td>
                                            <td>{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $leave->status_badge }}">
                                                    {{ $leave->status_label }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('reports.leave') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                    @else
                        <p class="text-muted text-center">No recent leave requests.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Recent Employees</h5>
                    @if($recentEmployees->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentEmployees->take(5) as $employee)
                                        <tr>
                                            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                            <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                            <td>{{ $employee->position }}</td>
                                            <td>
                                                <span class="badge bg-{{ $employee->employment_status === 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($employee->employment_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('web.employees.index') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                    @else
                        <p class="text-muted text-center">No recent employees.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Department Statistics -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Department Statistics</h5>
                    <div class="table-responsive">
                        <table class="table table-centered">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Employees</th>
                                    <th>Active</th>
                                    <th>Leaves This Year</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departmentStats as $department)
                                    <tr>
                                        <td>{{ $department->name }}</td>
                                        <td>{{ $department->employees_count }}</td>
                                        <td>{{ $department->employees->where('employment_status', 'active')->count() }}</td>
                                        <td>{{ $department->employees->sum(function($emp) { return $emp->leaves->filter(function($leave) { return $leave->start_date && $leave->start_date->year == date('Y'); })->count(); }) }}</td>
                                        <td>
                                            <a href="{{ route('web.departments.show', $department) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="mdi mdi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Type Distribution -->
    @if($leaveTypeDistribution->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
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
</div>
@endsection

@push('styles')
<style>
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
    }
</style>
@endpush
