@extends('layouts.app')

@section('title', 'Payroll Details - StaffIQ')

@section('page-title', 'Payroll Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-money-bill-wave me-2"></i>
            Payroll for {{ $payroll->employee->name }}
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Employee Information</h6>
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $payroll->employee->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Employee ID:</strong></td>
                        <td>{{ $payroll->employee->employee_id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Department:</strong></td>
                        <td>{{ $payroll->employee->department->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Position:</strong></td>
                        <td>{{ $payroll->employee->position ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Payroll Information</h6>
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Period:</strong></td>
                        <td>{{ date('F Y', mktime(0, 0, 0, $payroll->month, 1, $payroll->year)) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($payroll->status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($payroll->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($payroll->status === 'failed')
                                <span class="badge bg-danger">Failed</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($payroll->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Generated:</strong></td>
                        <td>{{ $payroll->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated:</strong></td>
                        <td>{{ $payroll->updated_at->format('M d, Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-12">
                <h6>Salary Breakdown</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td><strong>Basic Salary</strong></td>
                                <td class="text-end">₹{{ number_format($payroll->basic_salary, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Allowances</strong></td>
                                <td class="text-end text-info">+₹{{ number_format($payroll->allowances, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Deductions</strong></td>
                                <td class="text-end text-danger">-₹{{ number_format($payroll->deductions, 2) }}</td>
                            </tr>
                            <tr class="table-primary">
                                <td><strong>Net Salary</strong></td>
                                <td class="text-end"><strong>₹{{ number_format($payroll->net_salary, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($payroll->notes)
        <div class="row mt-3">
            <div class="col-md-12">
                <h6>Notes</h6>
                <div class="alert alert-info">
                    {{ $payroll->notes }}
                </div>
            </div>
        </div>
        @endif

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('web.payrolls.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Payrolls
            </a>
            <div>
                <form method="POST" action="{{ route('web.payrolls.generate-payslip', $payroll) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-file-pdf me-2"></i>
                        Generate Payslip
                    </button>
                </form>
                <a href="{{ route('web.payrolls.download-payslip', $payroll) }}" class="btn btn-success">
                    <i class="fas fa-download me-2"></i>
                    Download Payslip
                </a>
                <a href="{{ route('web.payrolls.edit', $payroll) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>
                    Edit
                </a>
                @if($payroll->status === 'pending')
                    <form method="POST" action="{{ route('web.payrolls.mark-as-paid', $payroll) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>
                            Mark as Paid
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
