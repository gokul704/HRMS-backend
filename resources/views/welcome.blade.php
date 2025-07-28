<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS - Human Resource Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .hero-section {
            padding: 100px 0;
            color: white;
        }
        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 30px;
            margin: 20px 0;
            color: white;
        }
        .btn-custom {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
        }
        .stats-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 40px;
            margin: 40px 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-users me-2"></i>
                HRMS
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Human Resource Management System
                    </h1>
                    <p class="lead mb-4">
                        Streamline your HR processes with our comprehensive management system.
                        Manage employees, departments, payroll, and offer letters all in one place.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('login') }}" class="btn btn-custom btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Get Started
                        </a>
                        <a href="#features" class="btn btn-custom btn-lg btn-outline-light">
                            <i class="fas fa-info-circle me-2"></i>
                            Learn More
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-users fa-10x text-white-50"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <h4>Employee Management</h4>
                        <p>Comprehensive employee profiles, onboarding tracking, and performance management.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-building fa-3x mb-3"></i>
                        <h4>Department Management</h4>
                        <p>Organize your workforce with department structures and hierarchical management.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-money-bill-wave fa-3x mb-3"></i>
                        <h4>Payroll Processing</h4>
                        <p>Automated payroll calculations, salary management, and payment tracking.</p>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-file-contract fa-3x mb-3"></i>
                        <h4>Offer Letters</h4>
                        <p>Streamlined offer letter creation, approval workflows, and status tracking.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                        <h4>Analytics & Reports</h4>
                        <p>Comprehensive reporting and analytics for data-driven HR decisions.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-shield-alt fa-3x mb-3"></i>
                        <h4>Role-Based Access</h4>
                        <p>Secure access control with HR, Manager, and Employee role permissions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <h2 class="display-6 fw-bold text-white">3</h2>
                    <p class="text-white-50">User Roles</p>
                </div>
                <div class="col-md-3">
                    <h2 class="display-6 fw-bold text-white">50+</h2>
                    <p class="text-white-50">API Endpoints</p>
                </div>
                <div class="col-md-3">
                    <h2 class="display-6 fw-bold text-white">100%</h2>
                    <p class="text-white-50">Secure</p>
                </div>
                <div class="col-md-3">
                    <h2 class="display-6 fw-bold text-white">24/7</h2>
                    <p class="text-white-50">Available</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Accounts Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="feature-card text-center">
                        <h3 class="mb-4">Demo Accounts Available</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="text-warning">HR Access</h5>
                                <p class="small">gokul.kumar@company.com<br>password123</p>
                            </div>
                            <div class="col-md-4">
                                <h5 class="text-warning">Manager Access</h5>
                                <p class="small">vardhan.kumar@company.com<br>password123</p>
                            </div>
                            <div class="col-md-4">
                                <h5 class="text-warning">Employee Access</h5>
                                <p class="small">gokul.kumar.dev@company.com<br>password123</p>
                            </div>
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-custom btn-lg mt-3">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Try Demo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-4 text-white-50">
        <div class="container">
            <p>&copy; 2024 HRMS - Human Resource Management System. All rights reserved.</p>
            <p class="small">
                Built with Laravel, Bootstrap, and modern web technologies.
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
