@extends('layouts.app')

@section('title', 'Request Leave')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('employees.my-leaves') }}">My Leaves</a></li>
                        <li class="breadcrumb-item active">Request Leave</li>
                    </ol>
                </div>
                <h4 class="page-title">Request Leave</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Leave Request Form</h5>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('employees.store-leave-request') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="leave_type" class="form-label">Leave Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('leave_type') is-invalid @enderror" id="leave_type" name="leave_type" required>
                                        <option value="">Select Leave Type</option>
                                        <option value="annual" {{ old('leave_type') == 'annual' ? 'selected' : '' }}>Annual Leave</option>
                                        <option value="sick" {{ old('leave_type') == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                                        <option value="casual" {{ old('leave_type') == 'casual' ? 'selected' : '' }}>Casual Leave</option>
                                        <option value="maternity" {{ old('leave_type') == 'maternity' ? 'selected' : '' }}>Maternity Leave</option>
                                        <option value="paternity" {{ old('leave_type') == 'paternity' ? 'selected' : '' }}>Paternity Leave</option>
                                        <option value="other" {{ old('leave_type') == 'other' ? 'selected' : '' }}>Other Leave</option>
                                    </select>
                                    @error('leave_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                           id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                           id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Total Days</label>
                                    <input type="text" class="form-control" id="total_days" readonly>
                                    <small class="text-muted">Calculated automatically</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('reason') is-invalid @enderror"
                                      id="reason" name="reason" rows="4"
                                      placeholder="Please provide a detailed reason for your leave request..." required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Leave Balance Information -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">Leave Balance Information</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Annual Leave:</strong> <span id="annual-balance">21</span> days remaining
                                </div>
                                <div class="col-md-4">
                                    <strong>Sick Leave:</strong> <span id="sick-balance">10</span> days remaining
                                </div>
                                <div class="col-md-4">
                                    <strong>Casual Leave:</strong> <span id="casual-balance">7</span> days remaining
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('employees.my-leaves') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const totalDaysInput = document.getElementById('total_days');
    const leaveTypeSelect = document.getElementById('leave_type');

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    startDateInput.min = today;
    endDateInput.min = today;

    function calculateTotalDays() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (startDate && endDate && endDate >= startDate) {
            const timeDiff = endDate.getTime() - startDate.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
            totalDaysInput.value = daysDiff + ' day(s)';
        } else {
            totalDaysInput.value = '';
        }
    }

    function updateEndDateMin() {
        if (startDateInput.value) {
            endDateInput.min = startDateInput.value;
            if (endDateInput.value && endDateInput.value < startDateInput.value) {
                endDateInput.value = startDateInput.value;
            }
        }
    }

    startDateInput.addEventListener('change', function() {
        updateEndDateMin();
        calculateTotalDays();
    });

    endDateInput.addEventListener('change', calculateTotalDays);

    // Initialize
    updateEndDateMin();
    calculateTotalDays();
});
</script>
@endpush
@endsection
