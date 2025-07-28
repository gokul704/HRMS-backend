<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Unavailable - HRMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body text-center p-5">
                        <i class="fas fa-database text-warning" style="font-size: 4rem;"></i>
                        <h2 class="mt-3 text-danger">Database Unavailable</h2>
                        <p class="text-muted mt-3">
                            {{ $message ?? 'The database connection is currently unavailable. Please try again later.' }}
                        </p>
                        <div class="mt-4">
                            <a href="{{ url('/health') }}" class="btn btn-primary">
                                <i class="fas fa-heartbeat me-2"></i>Check Application Status
                            </a>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                The application is running but cannot connect to the database.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
