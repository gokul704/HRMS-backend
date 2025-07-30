@extends('layouts.app')

@section('title', 'Departments - StaffIQ')

@section('page-title', 'Departments')

@section('page-actions')
<a href="{{ route('web.departments.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>
    Add Department
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-building me-2"></i>
            All Departments
        </h5>
    </div>
    <div class="card-body">
        @if(isset($departments) && count($departments) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Employee Count</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departments as $department)
                        <tr>
                            <td>
                                <strong>{{ $department->name }}</strong>
                            </td>
                            <td>
                                {{ Str::limit($department->description, 50) }}
                            </td>
                            <td>
                                {{ $department->location }}
                            </td>
                            <td>
                                <span class="badge bg-info rounded-pill">
                                    {{ $department->employees_count ?? 0 }} employees
                                </span>
                            </td>
                            <td>
                                @if($department->is_active)
                                    <span class="badge bg-success rounded-pill">Active</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                                                        <a href="{{ route('web.departments.show', $department) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('web.departments.edit', $department) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('web.departments.statistics', $department) }}"
                                       class="btn btn-sm btn-outline-info"
                                       title="Statistics">
                                        <i class="fas fa-chart-bar"></i>
                                    </a>
                                    <form method="POST" action="{{ route('web.departments.toggle-status', $department) }}"
                                          class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $department->is_active ? 'warning' : 'success' }}"
                                                title="{{ $department->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $department->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('web.departments.destroy', $department) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this department?')">
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

            @if(isset($departments) && method_exists($departments, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $departments->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Departments Found</h5>
                <p class="text-muted">Get started by creating your first department.</p>
                <a href="{{ route('web.departments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Create Department
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
