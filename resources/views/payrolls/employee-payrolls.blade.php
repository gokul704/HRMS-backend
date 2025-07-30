@extends('layouts.app')
@section('title', 'My Payrolls - StaffIQ')
@section('page-title', 'My Payrolls')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">My Payrolls</h5>
    </div>
    <div class="card-body">
        @if(count($payrolls) > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Basic Salary</th>
                            <th>Net Salary</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->month }}/{{ $payroll->year }}</td>
                            <td>₹{{ number_format($payroll->basic_salary, 2) }}</td>
                            <td>₹{{ number_format($payroll->net_salary, 2) }}</td>
                            <td>{{ ucfirst($payroll->status) }}</td>
                            <td>
                                <a href="{{ route('web.employee.download-payslip', $payroll) }}"
                                   class="btn btn-sm btn-outline-success"
                                   title="Download Payslip">
                                    <i class="fas fa-download"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No payrolls found.</p>
        @endif
    </div>
</div>
@endsection
