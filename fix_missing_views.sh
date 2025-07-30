#!/bin/bash

# Fix Missing Views Script
# This script creates all missing view files that are causing 500 errors

echo "🔧 Fixing Missing Views"
echo "======================"
echo ""

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

print_status() {
    echo -e "${GREEN}✅${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠️${NC} $1"
}

# Create missing view directories
echo "Creating view directories..."
mkdir -p resources/views/payrolls
mkdir -p resources/views/offer-letters
mkdir -p resources/views/employees
mkdir -p resources/views/departments
mkdir -p resources/views/dashboard
mkdir -p resources/views/auth
mkdir -p resources/views/profile
print_status "View directories created"

# Create basic view files for payrolls
echo "Creating payroll views..."

# payrolls/edit.blade.php
cat > resources/views/payrolls/edit.blade.php << 'EOF'
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
                        <label for="basic_salary" class="form-label">Basic Salary</label>
                        <input type="number" name="basic_salary" value="{{ $payroll->basic_salary }}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="allowances" class="form-label">Allowances</label>
                        <input type="number" name="allowances" value="{{ $payroll->allowances }}" class="form-control">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="deductions" class="form-label">Deductions</label>
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
EOF

# payrolls/statistics.blade.php
cat > resources/views/payrolls/statistics.blade.php << 'EOF'
@extends('layouts.app')
@section('title', 'Payroll Statistics - StaffIQ')
@section('page-title', 'Payroll Statistics')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Payroll Statistics</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Total Payrolls</h5>
                        <h3>{{ $data['total_payrolls'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Paid</h5>
                        <h3>{{ $data['paid_payrolls'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5>Pending</h5>
                        <h3>{{ $data['pending_payrolls'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5>Failed</h5>
                        <h3>{{ $data['failed_payrolls'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOF

# payrolls/by-employee.blade.php
cat > resources/views/payrolls/by-employee.blade.php << 'EOF'
@extends('layouts.app')
@section('title', 'Employee Payrolls - StaffIQ')
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
EOF

# payrolls/employee-payrolls.blade.php
cat > resources/views/payrolls/employee-payrolls.blade.php << 'EOF'
@extends('layouts.app')
@section('title', 'My Payrolls - StaffIQ')
@section('page-title', 'My Payrolls')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">My Payrolls</h5>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->month }}/{{ $payroll->year }}</td>
                            <td>${{ number_format($payroll->basic_salary, 2) }}</td>
                            <td>${{ number_format($payroll->net_salary, 2) }}</td>
                            <td>{{ ucfirst($payroll->status) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No payrolls found.</p>
        @endif
    </div>
</div>
@endsection
EOF

print_status "Payroll views created"

# Create basic view files for offer-letters
echo "Creating offer letter views..."

# offer-letters/create.blade.php
cat > resources/views/offer-letters/create.blade.php << 'EOF'
@extends('layouts.app')
@section('title', 'Create Offer Letter - StaffIQ')
@section('page-title', 'Create Offer Letter')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Create Offer Letter</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('web.offer-letters.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="candidate_name" class="form-label">Candidate Name</label>
                        <input type="text" name="candidate_name" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="candidate_email" class="form-label">Candidate Email</label>
                        <input type="email" name="candidate_email" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" name="position" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select name="department_id" class="form-control">
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="salary" class="form-label">Salary</label>
                <input type="number" name="salary" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Offer Letter Content</label>
                <textarea name="content" class="form-control" rows="10"></textarea>
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('web.offer-letters.index') }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Create Offer Letter</button>
            </div>
        </form>
    </div>
</div>
@endsection
EOF

# offer-letters/show.blade.php
cat > resources/views/offer-letters/show.blade.php << 'EOF'
@extends('layouts.app')
@section('title', 'Offer Letter Details - StaffIQ')
@section('page-title', 'Offer Letter Details')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Offer Letter for {{ $offerLetter->candidate_name }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Candidate Information</h6>
                <p><strong>Name:</strong> {{ $offerLetter->candidate_name }}</p>
                <p><strong>Email:</strong> {{ $offerLetter->candidate_email }}</p>
                <p><strong>Position:</strong> {{ $offerLetter->position }}</p>
                <p><strong>Department:</strong> {{ $offerLetter->department->name ?? 'N/A' }}</p>
                <p><strong>Salary:</strong> ${{ number_format($offerLetter->salary, 2) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($offerLetter->status) }}</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <h6>Offer Letter Content</h6>
                <div class="border p-3 bg-light">
                    {!! nl2br(e($offerLetter->content)) !!}
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('web.offer-letters.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('web.offer-letters.edit', $offerLetter) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
</div>
@endsection
EOF

# offer-letters/edit.blade.php
cat > resources/views/offer-letters/edit.blade.php << 'EOF'
@extends('layouts.app')
@section('title', 'Edit Offer Letter - StaffIQ')
@section('page-title', 'Edit Offer Letter')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Offer Letter</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('web.offer-letters.update', $offerLetter) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="candidate_name" class="form-label">Candidate Name</label>
                        <input type="text" name="candidate_name" value="{{ $offerLetter->candidate_name }}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="candidate_email" class="form-label">Candidate Email</label>
                        <input type="email" name="candidate_email" value="{{ $offerLetter->candidate_email }}" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" name="position" value="{{ $offerLetter->position }}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select name="department_id" class="form-control">
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $offerLetter->department_id == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="salary" class="form-label">Salary</label>
                <input type="number" name="salary" value="{{ $offerLetter->salary }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Offer Letter Content</label>
                <textarea name="content" class="form-control" rows="10">{{ $offerLetter->content }}</textarea>
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('web.offer-letters.index') }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Update Offer Letter</button>
            </div>
        </form>
    </div>
</div>
@endsection
EOF

# offer-letters/statistics.blade.php
cat > resources/views/offer-letters/statistics.blade.php << 'EOF'
@extends('layouts.app')
@section('title', 'Offer Letter Statistics - StaffIQ')
@section('page-title', 'Offer Letter Statistics')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Offer Letter Statistics</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Total Offers</h5>
                        <h3>{{ $data['total_offers'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Accepted</h5>
                        <h3>{{ $data['accepted_offers'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5>Sent</h5>
                        <h3>{{ $data['sent_offers'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5>Rejected</h5>
                        <h3>{{ $data['rejected_offers'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOF

# offer-letters/by-department.blade.php
cat > resources/views/offer-letters/by-department.blade.php << 'EOF'
@extends('layouts.app')
@section('title', 'Department Offer Letters - StaffIQ')
@section('page-title', 'Offer Letters for {{ $department->name }}')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Offer Letters for {{ $department->name }}</h5>
    </div>
    <div class="card-body">
        @if(count($offerLetters) > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Position</th>
                            <th>Salary</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offerLetters as $offerLetter)
                        <tr>
                            <td>{{ $offerLetter->candidate_name }}</td>
                            <td>{{ $offerLetter->position }}</td>
                            <td>${{ number_format($offerLetter->salary, 2) }}</td>
                            <td>{{ ucfirst($offerLetter->status) }}</td>
                            <td>
                                <a href="{{ route('web.offer-letters.show', $offerLetter) }}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No offer letters found for this department.</p>
        @endif
    </div>
</div>
@endsection
EOF

print_status "Offer letter views created"

# Clear caches
echo "Clearing caches..."
php artisan view:clear
php artisan cache:clear
print_status "Caches cleared"

echo ""
print_status "All missing views have been created!"
echo ""
echo "🎯 Next Steps:"
echo "1. Test your application: http://localhost:8000"
echo "2. Check if 500 errors are resolved"
echo "3. Navigate to different pages to ensure they work"
echo ""
echo "📖 If you still see errors, check the logs:"
echo "   tail -f storage/logs/laravel.log"
