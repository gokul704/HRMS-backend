@extends('layouts.app')

@section('title', 'Profile - StaffIQ')

@section('page-title', 'Profile Settings')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    Account Information
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">New Password (optional)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" placeholder="Leave blank to keep current password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation"
                                   placeholder="Confirm new password">
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Account Details
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Role:</strong>
                    <span class="badge bg-{{ auth()->user()->role == 'hr' ? 'danger' : (auth()->user()->role == 'manager' ? 'warning' : 'info') }} rounded-pill">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>

                <div class="mb-3">
                    <strong>Account Status:</strong>
                    @if(auth()->user()->is_active)
                        <span class="badge bg-success rounded-pill">Active</span>
                    @else
                        <span class="badge bg-secondary rounded-pill">Inactive</span>
                    @endif
                </div>

                <div class="mb-3">
                    <strong>Member Since:</strong>
                    <br>
                    <small class="text-muted">
                        {{ auth()->user()->created_at ? auth()->user()->created_at->format('M d, Y') : 'N/A' }}
                    </small>
                </div>

                <div class="mb-3">
                    <strong>Last Updated:</strong>
                    <br>
                    <small class="text-muted">
                        {{ auth()->user()->updated_at ? auth()->user()->updated_at->format('M d, Y H:i') : 'N/A' }}
                    </small>
                </div>

                @if(auth()->user()->isEmployee())
                    @if(isset($employee))
                    <hr>
                    <div class="mb-3">
                        <strong>Employee ID:</strong>
                        <br>
                        <span class="text-muted">{{ $employee->employee_id ?? 'N/A' }}</span>
                    </div>

                    <div class="mb-3">
                        <strong>Position:</strong>
                        <br>
                        <span class="text-muted">{{ $employee->position ?? 'N/A' }}</span>
                    </div>

                    <div class="mb-3">
                        <strong>Department:</strong>
                        <br>
                        <span class="text-muted">{{ $employee->department->name ?? 'N/A' }}</span>
                    </div>

                    <div class="mb-3">
                        <strong>Hire Date:</strong>
                        <br>
                        <span class="text-muted">
                            {{ $employee->hire_date ? \Carbon\Carbon::parse($employee->hire_date)->format('M d, Y') : 'N/A' }}
                        </span>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
