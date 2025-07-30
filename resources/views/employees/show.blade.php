@extends('layouts.app')

@section('title', 'Employee Details - StaffIQ')

@section('page-title', 'Employee Details')

@section('page-actions')
<a href="{{ route('web.employees.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-2"></i>
    Back to Employees
</a>
<a href="{{ route('web.employees.edit', $employee) }}" class="btn btn-warning">
    <i class="fas fa-edit me-2"></i>
    Edit Employee
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    {{ $employee->first_name }} {{ $employee->last_name }}
                    <span class="badge bg-primary ms-2">{{ $employee->employee_id }}</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Personal Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Full Name:</strong></td>
                                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $employee->user->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td>{{ $employee->phone }}</td>
                            </tr>
                            <tr>
                                <td><strong>Date of Birth:</strong></td>
                                <td>{{ $employee->date_of_birth?->format('M d, Y') ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Gender:</strong></td>
                                <td>{{ ucfirst($employee->gender ?? 'N/A') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Address:</strong></td>
                                <td>{{ $employee->address }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Employment Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Position:</strong></td>
                                <td>{{ $employee->position }}</td>
                            </tr>
                            <tr>
                                <td><strong>Department:</strong></td>
                                <td>
                                    <span class="badge bg-primary rounded-pill">
                                        {{ $employee->department->name ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Hire Date:</strong></td>
                                <td>{{ $employee->hire_date?->format('M d, Y') ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Salary:</strong></td>
                                <td>₹{{ number_format($employee->salary, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($employee->employment_status == 'active')
                                        <span class="badge bg-success rounded-pill">Active</span>
                                    @elseif($employee->employment_status == 'inactive')
                                        <span class="badge bg-secondary rounded-pill">Inactive</span>
                                    @else
                                        <span class="badge bg-warning rounded-pill">{{ ucfirst($employee->employment_status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Onboarding:</strong></td>
                                <td>
                                    @if($employee->is_onboarded)
                                        <span class="badge bg-success rounded-pill">Completed</span>
                                    @else
                                        <span class="badge bg-warning rounded-pill">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Emergency Contact</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $employee->emergency_contact_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td>{{ $employee->emergency_contact_phone }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Account Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>User Status:</strong></td>
                                <td>
                                    @if($employee->user && $employee->user->is_active)
                                        <span class="badge bg-success rounded-pill">Active</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Last Login:</strong></td>
                                <td>
                                    @if($employee->user && $employee->user->last_login_at)
                                        {{ $employee->user->last_login_at->diffForHumans() }}
                                    @else
                                        <span class="text-muted">Never logged in</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Online Status:</strong></td>
                                <td>
                                    @if($employee->user && $employee->user->last_login_at && $employee->user->last_login_at->diffInMinutes(now()) < 5)
                                        <span class="badge bg-success rounded-pill">
                                            <i class="fas fa-circle me-1"></i>Online
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill">
                                            <i class="fas fa-circle me-1"></i>Offline
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Recent Payrolls
                </h6>
            </div>
            <div class="card-body">
                @if($employee->payrolls->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($employee->payrolls->take(5) as $payroll)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $payroll->month }} {{ $payroll->year }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $payroll->payment_status }}</small>
                                </div>
                                <span class="badge bg-{{ $payroll->payment_status == 'paid' ? 'success' : 'warning' }} rounded-pill">
                                    ₹{{ number_format($payroll->net_salary, 2) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    @if($employee->payrolls->count() > 5)
                        <div class="text-center mt-3">
                            <small class="text-muted">+{{ $employee->payrolls->count() - 5 }} more payrolls</small>
                        </div>
                    @endif
                @else
                    <p class="text-muted text-center">No payroll records found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
