@extends('layouts.app')
@section('title', 'Request Leave - StaffIQ')
@section('page-title', 'Request Leave')

@section('page-actions')
    <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Leaves
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">New Leave Request</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('leaves.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="leave_type" class="form-label">Leave Type *</label>
                                <select name="leave_type" id="leave_type" class="form-select @error('leave_type') is-invalid @enderror" required>
                                    <option value="">Select Leave Type</option>
                                    @foreach($leaveTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('leave_type') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('leave_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="total_days" class="form-label">Total Days</label>
                                <input type="text" id="total_days" class="form-control" readonly>
                                <small class="text-muted">Automatically calculated based on start and end dates</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date *</label>
                                <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date *</label>
                                <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Leave *</label>
                        <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror"
                                  rows="4" placeholder="Please provide a detailed reason for your leave request..." required>{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Leave Policy Information -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Leave Policy Information</h6>
                        <ul class="mb-0">
                            <li><strong>Annual Leave:</strong> Up to 21 days per year</li>
                            <li><strong>Sick Leave:</strong> Up to 15 days per year with medical certificate</li>
                            <li><strong>Casual Leave:</strong> Up to 12 days per year</li>
                            <li><strong>Maternity Leave:</strong> Up to 26 weeks for female employees</li>
                            <li><strong>Paternity Leave:</strong> Up to 15 days for male employees</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const totalDays = document.getElementById('total_days');

    function calculateDays() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);

            if (end >= start) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                totalDays.value = diffDays + ' day(s)';
            } else {
                totalDays.value = 'Invalid date range';
            }
        } else {
            totalDays.value = '';
        }
    }

    startDate.addEventListener('change', calculateDays);
    endDate.addEventListener('change', calculateDays);

    // Set minimum end date based on start date
    startDate.addEventListener('change', function() {
        endDate.min = this.value;
        if (endDate.value && endDate.value < this.value) {
            endDate.value = this.value;
        }
        calculateDays();
    });
});
</script>
@endsection
