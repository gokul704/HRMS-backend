@extends('layouts.app')

@section('title', 'HR Dashboard - StaffIQ')

@section('page-title', 'HR Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1">
                            Total Employees
                        </div>
                        <div class="stats-number">{{ $totalEmployees ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1">
                            Active Departments
                        </div>
                        <div class="stats-number">{{ $activeDepartments ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1">
                            Pending Leaves
                        </div>
                        <div class="stats-number">{{ $pendingLeaves ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1">
                            Pending Offers
                        </div>
                        <div class="stats-number">{{ $pendingOffers ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-contract fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tasks me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('leaves.hr-approval') }}" class="btn btn-primary">
                        <i class="fas fa-check-circle me-2"></i>
                        Review Leave Approvals
                    </a>
                    <a href="{{ route('my.request-leave') }}" class="btn btn-success">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Apply for Leave
                    </a>
                    <a href="{{ route('web.offer-letters.create') }}" class="btn btn-info">
                        <i class="fas fa-plus me-2"></i>
                        Create Offer Letter
                    </a>
                    <a href="{{ route('web.employees.create') }}" class="btn btn-warning">
                        <i class="fas fa-user-plus me-2"></i>
                        Add New Employee
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    System Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Total Employees:</strong> {{ $totalEmployees ?? 0 }}</p>
                        <p><strong>Active Employees:</strong> {{ $activeEmployees ?? 0 }}</p>
                        <p><strong>Total Departments:</strong> {{ $totalDepartments ?? 0 }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Leaves:</strong> {{ $totalLeaves ?? 0 }}</p>
                        <p><strong>Approved Leaves:</strong> {{ $approvedLeaves ?? 0 }}</p>
                        <p><strong>Total Payrolls:</strong> {{ $totalPayrolls ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Employees -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Recent Employees
                </h5>
            </div>
            <div class="card-body">
                @if(isset($recentEmployees) && count($recentEmployees) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentEmployees as $employee)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ $employee->first_name }} {{ $employee->last_name }}</h6>
                                <small class="text-muted">{{ $employee->position }} - {{ $employee->department->name ?? 'No Department' }}</small>
                            </div>
                            <span class="badge bg-{{ $employee->employment_status === 'active' ? 'success' : 'secondary' }} rounded-pill">
                                {{ ucfirst($employee->employment_status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No recent employees found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Leave Requests -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Recent Leave Requests
                </h5>
            </div>
            <div class="card-body">
                @if(isset($recentLeaves) && count($recentLeaves) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentLeaves as $leave)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</h6>
                                    <small class="text-muted">{{ $leave->leave_type_label }} - {{ $leave->total_days }} days</small>
                                    <br>
                                    <small class="text-muted">{{ $leave->start_date->format('M d') }} to {{ $leave->end_date->format('M d, Y') }}</small>
                                </div>
                                <span class="badge bg-{{ $leave->status_badge }} rounded-pill">
                                    {{ $leave->status_label }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No recent leave requests.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Statistics Overview -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    HR Statistics Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h3 class="text-primary">{{ $totalEmployees ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Employees</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h3 class="text-success">{{ $activeEmployees ?? 0 }}</h3>
                            <p class="text-muted mb-0">Active Employees</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h3 class="text-warning">{{ $pendingLeaves ?? 0 }}</h3>
                            <p class="text-muted mb-0">Pending Leaves</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h3 class="text-info">{{ $pendingOffers ?? 0 }}</h3>
                            <p class="text-muted mb-0">Pending Offers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
