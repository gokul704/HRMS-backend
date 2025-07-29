@extends('layouts.app')

@section('title', 'Payrolls - HRMS')

@section('page-title', 'Payrolls')

@section('page-actions')
<a href="{{ route('web.payrolls.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>
    Generate Payroll
</a>
<a href="{{ route('web.payrolls.generate-bulk') }}" class="btn btn-success">
    <i class="fas fa-download me-2"></i>
    Bulk Generate
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-money-bill-wave me-2"></i>
            All Payrolls
        </h5>
    </div>
    <div class="card-body">
        @if(isset($payrolls) && count($payrolls) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Period</th>
                            <th>Basic Salary</th>
                            <th>Allowances</th>
                            <th>Deductions</th>
                            <th>Net Salary</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payrolls as $payroll)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-light rounded-circle">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $payroll->employee->name }}</h6>
                                        <small class="text-muted">{{ $payroll->employee->employee_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $payroll->month }}/{{ $payroll->year }}</strong>
                            </td>
                            <td>
                                <span class="text-success">₹{{ number_format($payroll->basic_salary, 2) }}</span>
                            </td>
                            <td>
                                <span class="text-info">₹{{ number_format($payroll->allowances, 2) }}</span>
                            </td>
                            <td>
                                <span class="text-danger">₹{{ number_format($payroll->deductions, 2) }}</span>
                            </td>
                            <td>
                                <strong class="text-primary">₹{{ number_format($payroll->net_salary, 2) }}</strong>
                            </td>
                            <td>
                                @if($payroll->status === 'paid')
                                    <span class="badge bg-success rounded-pill">Paid</span>
                                @elseif($payroll->status === 'pending')
                                    <span class="badge bg-warning rounded-pill">Pending</span>
                                @elseif($payroll->status === 'failed')
                                    <span class="badge bg-danger rounded-pill">Failed</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">{{ ucfirst($payroll->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('web.payrolls.show', $payroll) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('web.payrolls.edit', $payroll) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($payroll->status === 'pending')
                                        <form method="POST" action="{{ route('web.payrolls.mark-as-paid', $payroll) }}"
                                              class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success"
                                                    title="Mark as Paid">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($payroll->status === 'pending')
                                        <form method="POST" action="{{ route('web.payrolls.mark-as-failed', $payroll) }}"
                                              class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    title="Mark as Failed">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('web.payrolls.destroy', $payroll) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this payroll?')">
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

            @if(isset($payrolls) && method_exists($payrolls, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $payrolls->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Payrolls Found</h5>
                <p class="text-muted">Get started by generating your first payroll.</p>
                <a href="{{ route('web.payrolls.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Generate Payroll
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
