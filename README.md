# HRMS (Human Resource Management System) - Laravel Backend

A comprehensive Human Resource Management System built with Laravel, designed to handle employee management, offer letters, payroll processing, and role-based access control.

## Features

### 🔐 Authentication & Authorization
- **Role-based Access Control**: Employee, HR, Manager roles
- **JWT Token Authentication**: Secure API access with Laravel Sanctum
- **Profile Management**: User profile updates and password changes

### 👥 Employee Management
- **Employee Onboarding**: Complete employee lifecycle management
- **Department Management**: Organize employees by departments
- **Employee Records**: Comprehensive employee information storage
- **Status Tracking**: Active, inactive, terminated, resigned statuses

### 📄 Offer Letter Management
- **Offer Creation**: Create and manage job offers
- **Approval Workflow**: Multi-level approval process
- **Status Tracking**: Draft, sent, accepted, rejected, expired statuses
- **Document Management**: PDF generation and storage

### 💰 Payroll System
- **Salary Management**: Basic salary, allowances, bonuses, overtime
- **Deduction Tracking**: Tax, insurance, and other deductions
- **Payment Processing**: Mark payrolls as paid/failed
- **Bulk Generation**: Generate payroll for all employees at once

### 📊 Reporting & Analytics
- **Dashboard Statistics**: Employee, department, offer, payroll counts
- **Role-based Reports**: Different views for different user roles
- **Search & Filtering**: Advanced search and filtering capabilities

## Technology Stack

- **Backend**: Laravel 12.x
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Sanctum
- **API**: RESTful API with JSON responses
- **Validation**: Laravel Form Request Validation

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL/PostgreSQL
- Node.js (for frontend assets)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd HRMS-backend
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hrms_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

## API Documentation

### Base URL
```
http://localhost:8000/api
```

### Authentication

#### Login
```http
POST /api/login
Content-Type: application/json

{
    "email": "sarah.johnson@company.com",
    "password": "password123"
}
```

#### Response
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Sarah Johnson",
            "email": "sarah.johnson@company.com",
            "role": "hr",
            "is_active": true
        },
        "token": "1|abc123..."
    }
}
```

### Employee Management

#### Get All Employees
```http
GET /api/employees
Authorization: Bearer {token}
```

#### Create Employee
```http
POST /api/employees
Authorization: Bearer {token}
Content-Type: application/json

{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@company.com",
    "phone": "+1234567890",
    "position": "Software Developer",
    "department_id": 2,
    "hire_date": "2024-01-15",
    "salary": 65000,
    "employment_status": "active"
}
```

#### Complete Employee Onboarding
```http
PATCH /api/employees/{id}/complete-onboarding
Authorization: Bearer {token}
```

### Offer Letter Management

#### Get All Offer Letters
```http
GET /api/offer-letters
Authorization: Bearer {token}
```

#### Create Offer Letter
```http
POST /api/offer-letters
Authorization: Bearer {token}
Content-Type: application/json

{
    "candidate_name": "Jane Smith",
    "candidate_email": "jane.smith@email.com",
    "position": "Senior Developer",
    "department_id": 2,
    "offered_salary": 75000,
    "salary_currency": "USD",
    "offer_date": "2024-01-15",
    "joining_date": "2024-02-01",
    "job_description": "Senior software development role",
    "benefits": "Health insurance, 401k, flexible hours"
}
```

#### Send Offer Letter
```http
PATCH /api/offer-letters/{id}/send
Authorization: Bearer {token}
```

### Payroll Management

#### Get All Payrolls
```http
GET /api/payrolls
Authorization: Bearer {token}
```

#### Create Payroll
```http
POST /api/payrolls
Authorization: Bearer {token}
Content-Type: application/json

{
    "employee_id": 1,
    "month": "January",
    "year": 2024,
    "basic_salary": 5000,
    "allowances": 500,
    "bonus": 1000,
    "overtime_pay": 200,
    "tax_deduction": 800,
    "insurance_deduction": 200
}
```

#### Mark Payroll as Paid
```http
PATCH /api/payrolls/{id}/mark-as-paid
Authorization: Bearer {token}
Content-Type: application/json

{
    "payment_method": "Bank Transfer",
    "payment_notes": "Payment processed successfully"
}
```

### Department Management

#### Get All Departments
```http
GET /api/departments
Authorization: Bearer {token}
```

#### Create Department
```http
POST /api/departments
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Product Management",
    "description": "Product strategy and roadmap management",
    "location": "Product Suite",
    "is_active": true
}
```

## User Roles & Permissions

### Employee Role
- View own profile and payroll information
- Update personal information
- Access limited dashboard features

### HR Role
- Full employee management (CRUD operations)
- Offer letter creation and management
- Employee onboarding process
- Department management
- Payroll processing

### Manager Role
- View team members and their information
- Approve offer letters
- Access managerial reports and statistics
- Limited payroll management

## Default Users

The system comes with pre-seeded users for testing:

### HR Users
- **Sarah Johnson** (sarah.johnson@company.com) - HR Manager
- **Jennifer Smith** (jennifer.smith@company.com) - HR Specialist

### Manager Users
- **Michael Chen** (michael.chen@company.com) - IT Manager
- **Emily Rodriguez** (emily.rodriguez@company.com) - Finance Manager

### Employee User
- **David Wilson** (david.wilson@company.com) - Software Developer

**Default Password**: `password123`

## Database Schema

### Core Tables
- `users` - User accounts and authentication
- `departments` - Company departments
- `employees` - Employee information and records
- `offer_letters` - Job offer management
- `payrolls` - Payroll processing and records

### Key Relationships
- Users have one Employee record
- Employees belong to one Department
- Departments have many Employees and Offer Letters
- Employees have many Payroll records

## API Response Format

All API responses follow a consistent format:

```json
{
    "success": true,
    "message": "Operation successful",
    "data": {
        // Response data
    }
}
```

## Error Handling

The API returns appropriate HTTP status codes:
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Database Migrations
```bash
php artisan migrate
php artisan migrate:rollback
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support and questions, please contact the development team or create an issue in the repository.
