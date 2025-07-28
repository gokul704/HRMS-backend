<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HRMS - Human Resource Management System')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin: 0.25rem 0;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 0.5rem;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
        .navbar-brand {
            font-weight: 700;
            color: white !important;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stats-card .card-body {
            padding: 1.5rem;
        }
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
        }
        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
        }
    </style>
    @yield('styles')
</head>
<body>
    @auth
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white fw-bold">HRMS</h4>
                        <small class="text-white-50">Human Resource Management</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>

                        @if(auth()->user()->isHr() || auth()->user()->isManager())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('departments*') ? 'active' : '' }}" href="{{ route('departments.index') }}">
                                <i class="fas fa-building"></i>
                                Departments
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employees*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                                <i class="fas fa-users"></i>
                                Employees
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('offer-letters*') ? 'active' : '' }}" href="{{ route('offer-letters.index') }}">
                                <i class="fas fa-file-contract"></i>
                                Offer Letters
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('payrolls*') ? 'active' : '' }}" href="{{ route('payrolls.index') }}">
                                <i class="fas fa-money-bill-wave"></i>
                                Payroll
                            </a>
                        </li>
                        @endif

                        @if(auth()->user()->isManager())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('manager.approvals') ? 'active' : '' }}" href="{{ route('manager.approvals') }}">
                                <i class="fas fa-check-circle"></i>
                                Approvals
                            </a>
                        </li>
                        @endif

                        @if(auth()->user()->isEmployee())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employee.profile') ? 'active' : '' }}" href="{{ route('employee.profile') }}">
                                <i class="fas fa-user"></i>
                                My Profile
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employee.payrolls') ? 'active' : '' }}" href="{{ route('employee.payrolls') }}">
                                <i class="fas fa-money-bill"></i>
                                My Payrolls
                            </a>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('profile*') ? 'active' : '' }}" href="{{ route('profile') }}">
                                <i class="fas fa-cog"></i>
                                Settings
                            </a>
                        </li>
                    </ul>

                    <hr class="text-white-50">

                    <div class="text-center">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @yield('page-actions')
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @else
        @yield('content')
    @endauth

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>
