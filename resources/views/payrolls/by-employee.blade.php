@extends('layouts.app')
@section('title', 'Employee Payrolls - HRMS')
@section('page-title', 'Payrolls for {{ $employee->name }}')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Payrolls for {{ $employee->name }}</h5>
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
                            <td>${{ number_format($payroll->basic_salary, 2) }}</td>
                            <td>${{ number_format($payroll->net_salary, 2) }}</td>
                            <td>{{ ucfirst($payroll->status) }}</td>
                            <td>
                                <a href="{{ route('web.payrolls.show', $payroll) }}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No payrolls found for this employee.</p>
        @endif
    </div>
</div>
@endsection
