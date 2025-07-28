@extends('layouts.app')

@section('title', 'Dashboard - HRMS')

@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    @if(auth()->user()->isHr() || auth()->user()->isManager())
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

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1">
                            Total Payroll
                        </div>
                        <div class="stats-number">${{ number_format($totalPayroll ?? 0) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Recent Activities
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Recent Employees</h6>
                        @if(isset($recentEmployees) && count($recentEmployees) > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentEmployees as $employee)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $employee->position }}</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $employee->department->name ?? 'N/A' }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No recent employees</p>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Recent Offer Letters</h6>
                        @if(isset($recentOffers) && count($recentOffers) > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentOffers as $offer)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $offer->candidate_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $offer->position }}</small>
                                    </div>
                                    <span class="badge bg-{{ $offer->status == 'sent' ? 'warning' : ($offer->status == 'accepted' ? 'success' : 'secondary') }} rounded-pill">
                                        {{ ucfirst($offer->status) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No recent offer letters</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('employees.create') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>
                            Add Employee
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('departments.create') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-building me-2"></i>
                            Add Department
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('offer-letters.create') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-file-contract me-2"></i>
                            Create Offer
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('payrolls.create') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-money-bill me-2"></i>
                            Process Payroll
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Employee Dashboard -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    Welcome, {{ auth()->user()->name }}!
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Your Information</h6>
                        @if(isset($employee))
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Employee ID:</strong></td>
                                <td>{{ $employee->employee_id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Position:</strong></td>
                                <td>{{ $employee->position ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Department:</strong></td>
                                <td>{{ $employee->department->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Hire Date:</strong></td>
                                <td>{{ $employee->hire_date ? \Carbon\Carbon::parse($employee->hire_date)->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                        </table>
                        @else
                            <p class="text-muted">Employee information not available</p>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Quick Links</h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('employee.profile') }}" class="btn btn-outline-primary">
                                <i class="fas fa-user me-2"></i>
                                View My Profile
                            </a>
                            <a href="{{ route('employee.payrolls') }}" class="btn btn-outline-primary">
                                <i class="fas fa-money-bill me-2"></i>
                                View My Payrolls
                            </a>
                            <a href="{{ route('profile') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-cog me-2"></i>
                                Account Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
