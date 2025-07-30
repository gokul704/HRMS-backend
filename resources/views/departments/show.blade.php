@extends('layouts.app')

@section('title', 'Department Details - StaffIQ')

@section('page-title', 'Department Details')

@section('page-actions')
<a href="{{ route('web.departments.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-2"></i>
    Back to Departments
</a>
<a href="{{ route('web.departments.edit', $department) }}" class="btn btn-warning">
    <i class="fas fa-edit me-2"></i>
    Edit Department
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-building me-2"></i>
                    {{ $department->name }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Department Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $department->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Location:</strong></td>
                                <td>{{ $department->location ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($department->is_active)
                                        <span class="badge bg-success rounded-pill">Active</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $department->created_at->format('M d, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Description</h6>
                        <p>{{ $department->description ?? 'No description available.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Employees ({{ $department->employees->count() }})
                </h6>
            </div>
            <div class="card-body">
                @if($department->employees->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($department->employees->take(5) as $employee)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $employee->position }}</small>
                                </div>
                                <span class="badge bg-{{ $employee->employment_status == 'active' ? 'success' : 'secondary' }} rounded-pill">
                                    {{ ucfirst($employee->employment_status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    @if($department->employees->count() > 5)
                        <div class="text-center mt-3">
                            <small class="text-muted">+{{ $department->employees->count() - 5 }} more employees</small>
                        </div>
                    @endif
                @else
                    <p class="text-muted text-center">No employees in this department.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
