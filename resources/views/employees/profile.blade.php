@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">My Profile</li>
                    </ol>
                </div>
                <h4 class="page-title">My Profile</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Employee Information -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-primary rounded-circle font-size-20">
                                {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                        <p class="text-muted">{{ $employee->position }}</p>
                        <p class="text-muted mb-0">{{ $employee->department->name ?? 'N/A' }}</p>
                    </div>

                    <hr>

                    <div class="mt-3">
                        <h6 class="font-weight-bold">Employee Details</h6>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1"><strong>Employee ID:</strong></p>
                                <p class="text-muted">{{ $employee->employee_id }}</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1"><strong>Email:</strong></p>
                                <p class="text-muted">{{ $employee->user->email }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1"><strong>Phone:</strong></p>
                                <p class="text-muted">{{ $employee->phone ?? 'N/A' }}</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1"><strong>Status:</strong></p>
                                <span class="badge bg-{{ $employee->employment_status === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($employee->employment_status) }}
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1"><strong>Hire Date:</strong></p>
                                <p class="text-muted">{{ $employee->hire_date ? $employee->hire_date->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1"><strong>Salary:</strong></p>
                                <p class="text-muted">${{ number_format($employee->salary, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="col-lg-8">
            <div class="row">
                <!-- Leave Statistics -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Leave Statistics</h5>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h4 class="text-primary">{{ $leaveStats['total_leaves'] }}</h4>
                                        <p class="text-muted mb-0">Total Leaves</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success">{{ $leaveStats['approved_leaves'] }}</h4>
                                    <p class="text-muted mb-0">Approved</p>
                                </div>
                            </div>
                            <div class="row text-center mt-3">
                                <div class="col-6">
                                    <h4 class="text-warning">{{ $leaveStats['pending_leaves'] }}</h4>
                                    <p class="text-muted mb-0">Pending</p>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-danger">{{ $leaveStats['rejected_leaves'] }}</h4>
                                    <p class="text-muted mb-0">Rejected</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payroll Statistics -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Payroll Statistics</h5>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h4 class="text-primary">{{ $payrollStats['total_payrolls'] }}</h4>
                                        <p class="text-muted mb-0">Total Payrolls</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success">{{ $payrollStats['paid_payrolls'] }}</h4>
                                    <p class="text-muted mb-0">Paid</p>
                                </div>
                            </div>
                            <div class="row text-center mt-3">
                                <div class="col-6">
                                    <h4 class="text-warning">{{ $payrollStats['pending_payrolls'] }}</h4>
                                    <p class="text-muted mb-0">Pending</p>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-info">${{ number_format($recentPayrolls->sum('net_salary'), 2) }}</h4>
                                    <p class="text-muted mb-0">Total Earned</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Recent Activities</h5>
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#recent-leaves" role="tab">
                                        Recent Leaves
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#recent-payrolls" role="tab">
                                        Recent Payrolls
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="recent-leaves" role="tabpanel">
                                    @if($recentLeaves->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Type</th>
                                                        <th>Dates</th>
                                                        <th>Status</th>
                                                        <th>Applied</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentLeaves as $leave)
                                                        <tr>
                                                            <td>{{ $leave->leave_type_label }}</td>
                                                            <td>{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</td>
                                                            <td>
                                                                <span class="badge bg-{{ $leave->status_badge }}">
                                                                    {{ $leave->status_label }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $leave->created_at->format('M d, Y') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted text-center">No recent leave requests.</p>
                                    @endif
                                </div>

                                <div class="tab-pane" id="recent-payrolls" role="tabpanel">
                                    @if($recentPayrolls->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Period</th>
                                                        <th>Net Salary</th>
                                                        <th>Status</th>
                                                        <th>Processed</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentPayrolls as $payroll)
                                                        <tr>
                                                            <td>{{ ucfirst($payroll->month) }} {{ $payroll->year }}</td>
                                                            <td>${{ number_format($payroll->net_salary, 2) }}</td>
                                                            <td>
                                                                <span class="badge bg-{{ $payroll->payment_status === 'paid' ? 'success' : ($payroll->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                                                    {{ ucfirst($payroll->payment_status) }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $payroll->processed_at ? $payroll->processed_at->format('M d, Y') : 'N/A' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted text-center">No recent payrolls.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
