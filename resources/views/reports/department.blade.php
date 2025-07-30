@extends('layouts.app')

@section('title', 'Department Reports - StaffIQ Enterprise')
@section('page-title', 'Department Reports')
@section('page-subtitle', 'Comprehensive department analytics and insights')

@section('content')
<!-- Department Statistics Overview -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="metric-card primary">
            <div class="metric-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ $departments->count() }}</div>
                <div class="metric-label">Total Departments</div>
                <div class="metric-trend positive">
                    <i class="fas fa-check"></i>
                    <span>{{ $departments->where('is_active', true)->count() }} active</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="metric-card success">
            <div class="metric-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ $departments->sum('employees_count') }}</div>
                <div class="metric-label">Total Employees</div>
                <div class="metric-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>{{ $departments->sum('active_employees') }} active</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="metric-card warning">
            <div class="metric-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ $departments->sum('total_leaves') }}</div>
                <div class="metric-label">Total Leaves</div>
                <div class="metric-trend neutral">
                    <i class="fas fa-clock"></i>
                    <span>{{ $departments->sum('pending_leaves') }} pending</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="metric-card info">
            <div class="metric-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ number_format($departments->avg('employees_count'), 1) }}</div>
                <div class="metric-label">Avg Employees/Dept</div>
                <div class="metric-trend positive">
                    <i class="fas fa-chart-bar"></i>
                    <span>Average</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Department Reports Table -->
<div class="row">
    <div class="col-12">
        <div class="enterprise-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Department Analytics
                </h5>
                <div class="header-actions">
                    <a href="{{ route('reports.leave') }}" class="btn btn-primary">
                        <i class="fas fa-calendar me-2"></i>
                        View Leave Reports
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($departments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Department</th>
                                <th>Description</th>
                                <th>Employees</th>
                                <th>Active Employees</th>
                                <th>Total Leaves</th>
                                <th>Pending Leaves</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departments as $department)
                            <tr>
                                <td>
                                    <div class="department-info">
                                        <div class="department-name">{{ $department->name }}</div>
                                        <div class="department-code">{{ $department->code ?? 'No Code' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="department-description">
                                        {{ Str::limit($department->description ?? 'No description available', 60) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="employee-count">
                                        <span class="count-value">{{ $department->employees_count }}</span>
                                        <span class="count-label">employees</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="active-employee-count">
                                        <span class="count-value">{{ $department->active_employees }}</span>
                                        <span class="count-label">active</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="leave-count">
                                        <span class="count-value">{{ $department->total_leaves }}</span>
                                        <span class="count-label">leaves</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="pending-leave-count">
                                        <span class="count-value">{{ $department->pending_leaves }}</span>
                                        <span class="count-label">pending</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $department->is_active ? 'success' : 'danger' }}">
                                        {{ $department->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('web.departments.show', $department) }}" class="btn btn-sm btn-outline-primary" title="View Department">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('web.departments.employees', $department) }}" class="btn btn-sm btn-outline-info" title="View Employees">
                                            <i class="fas fa-users"></i>
                                        </a>
                                        <a href="{{ route('reports.leave') }}?department_id={{ $department->id }}" class="btn btn-sm btn-outline-warning" title="View Leaves">
                                            <i class="fas fa-calendar"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <h5>No Departments Found</h5>
                    <p>No departments are currently available in the system.</p>
                    <a href="{{ route('web.departments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Create Department
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Department Performance Chart -->
<div class="row mt-4">
    <div class="col-12">
        <div class="enterprise-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Department Performance Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="department-performance">
                    @foreach($departments as $department)
                    <div class="performance-item">
                        <div class="performance-header">
                            <h6 class="performance-title">{{ $department->name }}</h6>
                            <span class="performance-status badge bg-{{ $department->is_active ? 'success' : 'danger' }}">
                                {{ $department->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="performance-metrics">
                            <div class="metric-group">
                                <div class="metric">
                                    <span class="metric-icon">
                                        <i class="fas fa-users"></i>
                                    </span>
                                    <div class="metric-content">
                                        <span class="metric-value">{{ $department->employees_count }}</span>
                                        <span class="metric-label">Employees</span>
                                    </div>
                                </div>
                                <div class="metric">
                                    <span class="metric-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                    <div class="metric-content">
                                        <span class="metric-value">{{ $department->active_employees }}</span>
                                        <span class="metric-label">Active</span>
                                    </div>
                                </div>
                            </div>
                            <div class="metric-group">
                                <div class="metric">
                                    <span class="metric-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <div class="metric-content">
                                        <span class="metric-value">{{ $department->total_leaves }}</span>
                                        <span class="metric-label">Total Leaves</span>
                                    </div>
                                </div>
                                <div class="metric">
                                    <span class="metric-icon">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                    <div class="metric-content">
                                        <span class="metric-value">{{ $department->pending_leaves }}</span>
                                        <span class="metric-label">Pending</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="performance-actions">
                            <a href="{{ route('web.departments.show', $department) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>
                                View Details
                            </a>
                            <a href="{{ route('reports.leave') }}?department_id={{ $department->id }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-calendar me-1"></i>
                                Leave Report
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Department Info */
    .department-info {
        display: flex;
        flex-direction: column;
    }

    .department-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .department-code {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Department Description */
    .department-description {
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Employee Count */
    .employee-count, .active-employee-count, .leave-count, .pending-leave-count {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .count-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
    }

    .count-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.25rem;
    }

    .action-buttons .btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        font-size: 0.75rem;
    }

    /* Department Performance */
    .department-performance {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .performance-item {
        background: var(--light-bg);
        border-radius: 0.75rem;
        padding: 1.5rem;
        transition: all 0.2s ease;
    }

    .performance-item:hover {
        background: white;
        box-shadow: var(--shadow-sm);
        transform: translateY(-2px);
    }

    .performance-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .performance-title {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0;
    }

    .performance-status {
        font-size: 0.75rem;
    }

    .performance-metrics {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .metric-group {
        display: flex;
        gap: 1rem;
    }

    .metric {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
    }

    .metric-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .metric-content {
        display: flex;
        flex-direction: column;
    }

    .metric-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
    }

    .metric-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .performance-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .performance-actions .btn {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }

        .department-description {
            max-width: 150px;
        }

        .metric-group {
            flex-direction: column;
            gap: 0.5rem;
        }

        .performance-actions {
            flex-direction: column;
        }

        .performance-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush
