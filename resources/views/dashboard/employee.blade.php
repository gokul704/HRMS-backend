@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Employee Dashboard</li>
                    </ol>
                </div>
                <h4 class="page-title">Welcome, {{ $employee->first_name }}!</h4>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quick Actions</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('employees.request-leave') }}" class="btn btn-primary btn-block">
                                <i class="mdi mdi-calendar-plus"></i> Request Leave
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('employees.my-leaves') }}" class="btn btn-info btn-block">
                                <i class="mdi mdi-calendar-check"></i> My Leaves
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('web.employee.payrolls') }}" class="btn btn-success btn-block">
                                <i class="mdi mdi-cash-multiple"></i> My Payrolls
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('web.employee.profile') }}" class="btn btn-warning btn-block">
                                <i class="mdi mdi-account"></i> My Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Leave Statistics -->
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
                            <h5 class="mb-1">{{ $leaveStats['total_leaves'] }}</h5>
                            <p class="text-muted mb-0">Total Leave Requests</p>
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
                                <i class="mdi mdi-check-circle font-size-20 text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $leaveStats['approved_leaves'] }}</h5>
                            <p class="text-muted mb-0">Approved Leaves</p>
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
                            <h5 class="mb-1">{{ $leaveStats['pending_leaves'] }}</h5>
                            <p class="text-muted mb-0">Pending Leaves</p>
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
                                <i class="mdi mdi-cash-multiple font-size-20 text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $payrollStats['total_payrolls'] }}</h5>
                            <p class="text-muted mb-0">Total Payrolls</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Balance -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Leave Balance</h5>
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="border rounded p-3">
                                <h4 class="text-primary">{{ $leaveBalance['annual'] }}</h4>
                                <p class="text-muted mb-0">Annual Leave</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="border rounded p-3">
                                <h4 class="text-success">{{ $leaveBalance['sick'] }}</h4>
                                <p class="text-muted mb-0">Sick Leave</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="border rounded p-3">
                                <h4 class="text-warning">{{ $leaveBalance['casual'] }}</h4>
                                <p class="text-muted mb-0">Casual Leave</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Payroll Summary</h5>
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-success">{{ $payrollStats['paid_payrolls'] }}</h4>
                            <p class="text-muted mb-0">Paid</p>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning">{{ $payrollStats['pending_payrolls'] }}</h4>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h5 class="text-primary">${{ number_format($recentPayrolls->sum('net_salary'), 2) }}</h5>
                        <p class="text-muted mb-0">Total Earned This Year</p>
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
                                        <th>Type</th>
                                        <th>Dates</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLeaves->take(5) as $leave)
                                        <tr>
                                            <td>{{ $leave->leave_type_label }}</td>
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
                            <a href="{{ route('employees.my-leaves') }}" class="btn btn-sm btn-primary">View All</a>
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
                    <h5 class="card-title">Recent Payrolls</h5>
                    @if($recentPayrolls->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Period</th>
                                        <th>Net Salary</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPayrolls->take(5) as $payroll)
                                        <tr>
                                            <td>{{ ucfirst($payroll->month) }} {{ $payroll->year }}</td>
                                            <td>${{ number_format($payroll->net_salary, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $payroll->payment_status === 'paid' ? 'success' : ($payroll->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($payroll->payment_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('web.employee.payrolls') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                    @else
                        <p class="text-muted text-center">No recent payrolls.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
