@extends('layouts.app')

@section('title', 'Employee Statistics - StaffIQ')

@section('page-title', 'Employee Statistics')

@section('page-actions')
<a href="{{ route('employees.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-2"></i>
    Back to Employees
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-users fa-2x text-primary"></i>
                </div>
                <h3 class="text-primary mb-1">{{ $totalEmployees ?? 0 }}</h3>
                <p class="text-muted mb-0 fw-medium">Total Employees</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-user-check fa-2x text-success"></i>
                </div>
                <h3 class="text-success mb-1">{{ $activeEmployees ?? 0 }}</h3>
                <p class="text-muted mb-0 fw-medium">Active Employees</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-circle mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-clock fa-2x text-warning"></i>
                </div>
                <h3 class="text-warning mb-1">{{ $pendingOnboarding ?? 0 }}</h3>
                <p class="text-muted mb-0 fw-medium">Pending Onboarding</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 rounded-circle mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-building fa-2x text-info"></i>
                </div>
                <h3 class="text-info mb-1">{{ $totalDepartments ?? 0 }}</h3>
                <p class="text-muted mb-0 fw-medium">Departments</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Employees by Department
                </h5>
            </div>
            <div class="card-body">
                @if(isset($employeesByDepartment) && count($employeesByDepartment) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Employees</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employeesByDepartment as $dept)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary rounded-pill">{{ $dept->name }}</span>
                                    </td>
                                    <td>{{ $dept->employees_count }}</td>
                                    <td>{{ number_format(($dept->employees_count / $totalEmployees) * 100, 1) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No Department Data</h6>
                        <p class="text-muted">No employees have been assigned to departments yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Recent Hires
                </h5>
            </div>
            <div class="card-body">
                @if(isset($recentHires) && count($recentHires) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentHires as $employee)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong>
                                <br>
                                <small class="text-muted">{{ $employee->position }} - {{ $employee->department->name ?? 'N/A' }}</small>
                            </div>
                            <span class="badge bg-success rounded-pill">
                                {{ $employee->hire_date?->format('M d, Y') ?? 'N/A' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No Recent Hires</h6>
                        <p class="text-muted">No employees have been hired recently.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Employment Status Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-user-check fa-3x text-success"></i>
                            </div>
                            <h4 class="text-success">{{ $activeEmployees ?? 0 }}</h4>
                            <p class="text-muted">Active Employees</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-circle mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-clock fa-3x text-warning"></i>
                            </div>
                            <h4 class="text-warning">{{ $pendingOnboarding ?? 0 }}</h4>
                            <p class="text-muted">Pending Onboarding</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="d-inline-flex align-items-center justify-content-center bg-secondary bg-opacity-10 rounded-circle mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-user-times fa-3x text-secondary"></i>
                            </div>
                            <h4 class="text-secondary">{{ ($totalEmployees ?? 0) - ($activeEmployees ?? 0) }}</h4>
                            <p class="text-muted">Inactive Employees</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.5em 0.75em;
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #f0f0f0;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@endpush
