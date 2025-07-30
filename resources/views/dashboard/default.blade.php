@extends('layouts.app')

@section('title', 'Dashboard - StaffIQ')

@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Basic Statistics Cards -->
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
                            Departments
                        </div>
                        <div class="stats-number">{{ $totalDepartments ?? 0 }}</div>
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
                            Leave Requests
                        </div>
                        <div class="stats-number">{{ $totalLeaves ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-white-50"></i>
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
                            Payroll Records
                        </div>
                        <div class="stats-number">{{ $totalPayrolls ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Message -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-home me-2"></i>
                    Welcome to HRMS
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Welcome, {{ auth()->user()->name }}!</h4>
                        <p class="text-muted">You are logged in as <strong>{{ ucfirst(auth()->user()->role) }}</strong>.</p>
                        <p class="text-muted">This is your dashboard overview. You can navigate to different sections using the sidebar menu.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="border rounded p-3">
                            <i class="fas fa-user-circle fa-3x text-primary mb-2"></i>
                            <h6>{{ auth()->user()->name }}</h6>
                            <small class="text-muted">{{ ucfirst(auth()->user()->role) }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Navigation -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-compass me-2"></i>
                    Quick Navigation
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('web.employees.index') }}" class="btn btn-primary">
                        <i class="fas fa-users me-2"></i>
                        View Employees
                    </a>
                    <a href="{{ route('web.departments.index') }}" class="btn btn-info">
                        <i class="fas fa-building me-2"></i>
                        View Departments
                    </a>
                    <a href="{{ route('leaves.index') }}" class="btn btn-success">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Leave Management
                    </a>
                    @if(auth()->user()->isHr())
                    <a href="{{ route('web.payrolls.index') }}" class="btn btn-warning">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Payroll Management
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    System Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Total Employees:</strong> {{ $totalEmployees ?? 0 }}</p>
                        <p><strong>Total Departments:</strong> {{ $totalDepartments ?? 0 }}</p>
                        <p><strong>Total Leave Requests:</strong> {{ $totalLeaves ?? 0 }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Payrolls:</strong> {{ $totalPayrolls ?? 0 }}</p>
                        <p><strong>Your Role:</strong> {{ ucfirst(auth()->user()->role) }}</p>
                        <p><strong>Login Time:</strong> {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('M d, Y H:i') : 'N/A' }}</p>
                    </div>
                </div>
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
                    System Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h3 class="text-primary">{{ $totalEmployees ?? 0 }}</h3>
                            <p class="text-muted mb-0">Employees</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h3 class="text-success">{{ $totalDepartments ?? 0 }}</h3>
                            <p class="text-muted mb-0">Departments</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h3 class="text-warning">{{ $totalLeaves ?? 0 }}</h3>
                            <p class="text-muted mb-0">Leave Requests</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h3 class="text-info">{{ $totalPayrolls ?? 0 }}</h3>
                            <p class="text-muted mb-0">Payroll Records</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
