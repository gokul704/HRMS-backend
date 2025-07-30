@extends('layouts.app')

@section('title', 'Manager Dashboard - StaffIQ')

@section('page-title', 'Manager Dashboard')

@section('content')
<div class="row">
    <!-- Department Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1">
                            Department Employees
                        </div>
                        <div class="stats-number">{{ $departmentEmployees ?? 0 }}</div>
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
                            Active Team Members
                        </div>
                        <div class="stats-number">{{ $departmentActiveEmployees ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-white-50"></i>
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
                            Pending Approvals
                        </div>
                        <div class="stats-number">{{ $pendingDepartmentLeaves ?? 0 }}</div>
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
                            Total Leaves
                        </div>
                        <div class="stats-number">{{ $totalDepartmentLeaves ?? 0 }}</div>
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

    <!-- Department Information -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-building me-2"></i>
                    Department Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Department:</strong> {{ $employee->department->name ?? 'N/A' }}</p>
                        <p><strong>Your Position:</strong> {{ $employee->position ?? 'Manager' }}</p>
                        <p><strong>Team Size:</strong> {{ $departmentEmployees ?? 0 }} employees</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Active Members:</strong> {{ $departmentActiveEmployees ?? 0 }}</p>
                        <p><strong>Leave Requests:</strong> {{ $totalDepartmentLeaves ?? 0 }}</p>
                        <p><strong>Pending Approvals:</strong> {{ $pendingDepartmentLeaves ?? 0 }}</p>
                        <p><strong>Offer Letters:</strong> {{ $totalDepartmentOffers ?? 0 }}</p>
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
                    <a href="{{ route('leaves.manager-approval') }}" class="btn btn-primary">
                        <i class="fas fa-check-circle me-2"></i>
                        Review Leave Requests
                    </a>
                    <a href="{{ route('my.request-leave') }}" class="btn btn-success">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Apply for Leave
                    </a>
                    <a href="{{ route('web.offer-letters.index') }}" class="btn btn-info">
                        <i class="fas fa-file-contract me-2"></i>
                        Review Offer Letters
                    </a>
                    <a href="{{ route('web.employees.index') }}" class="btn btn-warning">
                        <i class="fas fa-users me-2"></i>
                        View Team Members
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Members -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Your Team
                </h5>
            </div>
            <div class="card-body">
                @if(isset($teamMembers) && count($teamMembers) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($teamMembers as $member)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ $member->first_name }} {{ $member->last_name }}</h6>
                                <small class="text-muted">{{ $member->position }}</small>
                            </div>
                            <span class="badge bg-{{ $member->employment_status === 'active' ? 'success' : 'secondary' }} rounded-pill">
                                {{ ucfirst($member->employment_status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No team members found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Department Leaves -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Recent Leave Requests
                </h5>
            </div>
            <div class="card-body">
                @if(isset($recentDepartmentLeaves) && count($recentDepartmentLeaves) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentDepartmentLeaves as $leave)
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

    <!-- Recent Department Offer Letters -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-contract me-2"></i>
                    Recent Offer Letters
                </h5>
            </div>
            <div class="card-body">
                @if(isset($recentDepartmentOffers) && count($recentDepartmentOffers) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentDepartmentOffers as $offer)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $offer->candidate_name }}</h6>
                                    <small class="text-muted">{{ $offer->position }} - ₹{{ number_format($offer->offered_salary, 2) }}</small>
                                    <br>
                                    <small class="text-muted">{{ $offer->created_at->format('M d, Y') }}</small>
                                </div>
                                <span class="badge bg-{{ $offer->status === 'accepted' ? 'success' : ($offer->status === 'rejected' ? 'danger' : ($offer->status === 'sent' ? 'info' : 'secondary')) }} rounded-pill">
                                    {{ ucfirst($offer->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-file-contract fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No recent offer letters.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Leave Statistics Chart -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Department Leave Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h3 class="text-primary">{{ $totalDepartmentLeaves ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Requests</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h3 class="text-warning">{{ $pendingDepartmentLeaves ?? 0 }}</h3>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h3 class="text-success">{{ $approvedDepartmentLeaves ?? 0 }}</h3>
                            <p class="text-muted mb-0">Approved</p>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="border rounded p-3">
                            <h3 class="text-danger">{{ ($totalDepartmentLeaves ?? 0) - ($approvedDepartmentLeaves ?? 0) - ($pendingDepartmentLeaves ?? 0) }}</h3>
                            <p class="text-muted mb-0">Rejected</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
