@extends('layouts.app')
@section('title', 'Edit Payroll - StaffIQ')
@section('page-title', 'Edit Payroll')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Payroll</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('web.payrolls.update', $payroll) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="basic_salary" class="form-label">Basic Salary (₹)</label>
                        <input type="number" name="basic_salary" value="{{ $payroll->basic_salary }}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="allowances" class="form-label">Allowances (₹)</label>
                        <input type="number" name="allowances" value="{{ $payroll->allowances }}" class="form-control">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="deductions" class="form-label">Deductions (₹)</label>
                <input type="number" name="deductions" value="{{ $payroll->deductions }}" class="form-control">
            </div>
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" class="form-control">{{ $payroll->notes }}</textarea>
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('web.payrolls.index') }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Update Payroll</button>
            </div>
        </form>
    </div>
</div>
@endsection
