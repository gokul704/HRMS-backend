@extends('layouts.app')

@section('title', 'Employees - StaffIQ')

@section('page-title', 'Employees')

@section('page-actions')
<a href="{{ route('web.employees.create') }}" class="btn btn-primary">
    <i class="fas fa-user-plus me-2"></i>
    Add Employee
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>
            All Employees
        </h5>
    </div>
    <div class="card-body">
        @if(isset($employees) && count($employees) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Online Status</th>
                            <th>Onboarding</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>
                                <strong>{{ $employee->employee_id }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $employee->phone }}</small>
                                </div>
                            </td>
                            <td>
                                {{ $employee->position }}
                            </td>
                            <td>
                                <span class="badge bg-primary rounded-pill">
                                    {{ $employee->department->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                @if($employee->employment_status == 'active')
                                    <span class="badge bg-success rounded-pill">Active</span>
                                @elseif($employee->employment_status == 'inactive')
                                    <span class="badge bg-secondary rounded-pill">Inactive</span>
                                @else
                                    <span class="badge bg-warning rounded-pill">{{ ucfirst($employee->employment_status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($employee->user && $employee->user->last_login_at && $employee->user->last_login_at->diffInMinutes(now()) < 5)
                                    <span class="badge bg-success rounded-pill">
                                        <i class="fas fa-circle me-1"></i>Online
                                    </span>
                                @else
                                    <span class="badge bg-danger rounded-pill">
                                        <i class="fas fa-circle me-1"></i>Offline
                                    </span>
                                    @if($employee->user && $employee->user->last_login_at)
                                        <br><small class="text-muted">Last login: {{ $employee->user->last_login_at->diffForHumans() }}</small>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($employee->is_onboarded)
                                    <span class="badge bg-success rounded-pill">Completed</span>
                                @else
                                    <span class="badge bg-warning rounded-pill">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('web.employees.show', $employee) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('web.employees.edit', $employee) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$employee->is_onboarded)
                                        <form method="POST" action="{{ route('web.employees.complete-onboarding', $employee) }}"
                                              class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success"
                                                    title="Complete Onboarding">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('web.employees.destroy', $employee) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(isset($employees) && method_exists($employees, 'links'))
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }} results
                    </div>
                    <div>
                        {{ $employees->links() }}
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Employees Found</h5>
                <p class="text-muted">Get started by adding your first employee.</p>
                <a href="{{ route('web.employees.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>
                    Add Employee
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
