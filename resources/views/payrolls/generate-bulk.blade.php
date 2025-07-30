@extends('layouts.app')

@section('title', 'Bulk Generate Payrolls - StaffIQ')

@section('page-title', 'Bulk Generate Payrolls')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-download me-2"></i>
                    Bulk Generate Payrolls
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('web.payrolls.generate-bulk') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="payroll_period" class="form-label">Payroll Period *</label>
                        <input type="text" class="form-control @error('payroll_period') is-invalid @enderror"
                               id="payroll_period" name="payroll_period"
                               value="{{ old('payroll_period') }}"
                               placeholder="e.g., 2024-01, 2024-02" required>
                        <div class="form-text">Enter the payroll period in format YYYY-MM</div>
                        @error('payroll_period')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department (Optional)</label>
                        <select class="form-select @error('department_id') is-invalid @enderror"
                                id="department_id" name="department_id">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Leave empty to generate for all departments</div>
                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>What this will do:</h6>
                        <ul class="mb-0">
                            <li>Generate payroll records for all active employees</li>
                            <li>Use their current salary as basic salary</li>
                            <li>Set allowances and deductions to 0 initially</li>
                            <li>Set payment status to 'pending'</li>
                            <li>Skip employees who already have payroll for this period</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('web.payrolls.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Payrolls
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>
                            Generate Payrolls
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
