@extends('layouts.app')

@section('title', 'Payrolls - StaffIQ')

@section('page-title', 'Payrolls')

@section('page-actions')
<a href="{{ route('web.payrolls.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>
    Generate Payroll
</a>
<a href="{{ route('web.payrolls.generate-bulk-form') }}" class="btn btn-success">
    <i class="fas fa-download me-2"></i>
    Bulk Generate
</a>
<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#bulkPayslipModal">
    <i class="fas fa-file-pdf me-2"></i>
    Bulk Payslips
</button>
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
                                <div>
                                    <h6 class="mb-0">{{ $payroll->employee->name }}</h6>
                                    <small class="text-muted">{{ $payroll->employee->employee_id }}</small>
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
                                @if($payroll->payment_status === 'paid')
                                    <span class="badge bg-success rounded-pill">Paid</span>
                                @elseif($payroll->payment_status === 'pending')
                                    <span class="badge bg-warning rounded-pill">Pending</span>
                                @elseif($payroll->payment_status === 'failed')
                                    <span class="badge bg-danger rounded-pill">Failed</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">{{ ucfirst($payroll->payment_status) }}</span>
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
                                    @if($payroll->payment_status === 'pending')
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
                                    @if($payroll->payment_status === 'pending')
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
                                    <form method="POST" action="{{ route('web.payrolls.generate-payslip', $payroll) }}"
                                          class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-info" title="Generate Payslip">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('web.payrolls.download-payslip', $payroll) }}"
                                       class="btn btn-sm btn-outline-success"
                                       title="Download Payslip">
                                        <i class="fas fa-download"></i>
                                    </a>
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

<!-- Bulk Payslip Generation Modal -->
<div class="modal fade" id="bulkPayslipModal" tabindex="-1" aria-labelledby="bulkPayslipModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkPayslipModalLabel">
                    <i class="fas fa-file-pdf me-2"></i>
                    Generate Bulk Payslips
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('web.payrolls.generate-bulk-payslips') }}">
                @csrf
                <div class="modal-body">
                    <p class="text-muted">Select payrolls to generate payslips for:</p>
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>Employee</th>
                                    <th>Period</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payrolls as $payroll)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="payroll_ids[]" value="{{ $payroll->id }}" class="form-check-input payroll-checkbox">
                                    </td>
                                    <td>{{ $payroll->employee->name }}</td>
                                    <td>{{ $payroll->month }}/{{ $payroll->year }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payroll->payment_status === 'paid' ? 'success' : ($payroll->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($payroll->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-file-pdf me-2"></i>
                        Generate Payslips
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const payrollCheckboxes = document.querySelectorAll('.payroll-checkbox');

    selectAllCheckbox.addEventListener('change', function() {
        payrollCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    payrollCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.payroll-checkbox:checked').length;
            const totalCount = payrollCheckboxes.length;
            selectAllCheckbox.checked = checkedCount === totalCount;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
        });
    });
});
</script>
@endsection
