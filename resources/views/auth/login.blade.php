@extends('layouts.app')

@section('title', 'Login - HRMS')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary">HRMS</h2>
                        <p class="text-muted">Human Resource Management System</p>
                    </div>

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}"
                                       placeholder="Enter your email" required autofocus>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" placeholder="Enter your password" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Sign In
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <small class="text-muted">
                            Demo Accounts Available:<br>
                            <strong>HR:</strong> gokul.kumar@company.com<br>
                            <strong>Manager:</strong> vardhan.kumar@company.com<br>
                            <strong>Employee:</strong> gokul.kumar.dev@company.com<br>
                            <strong>Password:</strong> password123
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
