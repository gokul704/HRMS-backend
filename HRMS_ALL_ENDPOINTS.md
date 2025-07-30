# StaffIQ API Endpoints - Complete Reference

## Base URL
```
https://hrms-backend-production-2e6d.up.railway.app/api
```

## Authentication
All protected endpoints require the `Authorization: Bearer {token}` header.

---

## 🌐 Web Routes (Browser Interface)

### Base URL
```
https://hrms-backend-production-2e6d.up.railway.app
```

### Public Web Routes

#### 1. Welcome Page
```http
GET /
```

#### 2. Login Page
```http
GET /login
```

#### 3. Login Form Submission
```http
POST /login
Content-Type: application/x-www-form-urlencoded

email=gokul.kumar.dev@company.com&password=password123
```

### Protected Web Routes (Authentication Required)

#### 4. Dashboard
```http
GET /dashboard
```

#### 5. Profile Management
```http
GET /profile
PUT /profile
```

#### 6. Department Management
```http
GET /departments                    # List all departments
GET /departments/create             # Show create form
POST /departments                   # Create department
GET /departments/{id}               # Show department details
GET /departments/{id}/edit          # Show edit form
PUT /departments/{id}               # Update department
DELETE /departments/{id}            # Delete department
GET /departments/{id}/statistics    # Department statistics
PATCH /departments/{id}/toggle-status # Toggle department status
```

#### 7. Employee Management
```http
GET /employees                      # List all employees
GET /employees/create               # Show create form
POST /employees                     # Create employee
GET /employees/{id}                 # Show employee details
GET /employees/{id}/edit            # Show edit form
PUT /employees/{id}                 # Update employee
DELETE /employees/{id}              # Delete employee
PATCH /employees/{id}/complete-onboarding # Complete onboarding
GET /employees/statistics           # Employee statistics
GET /departments/{id}/employees     # Employees by department
```

#### 8. Offer Letter Management
```http
GET /offer-letters                  # List all offer letters
GET /offer-letters/create           # Show create form
POST /offer-letters                 # Create offer letter
GET /offer-letters/{id}             # Show offer letter details
GET /offer-letters/{id}/edit        # Show edit form
PUT /offer-letters/{id}             # Update offer letter
DELETE /offer-letters/{id}          # Delete offer letter
PATCH /offer-letters/{id}/send      # Send offer letter
PATCH /offer-letters/{id}/approve   # Approve offer letter
PATCH /offer-letters/{id}/update-status # Update status
GET /offer-letters/statistics       # Offer letter statistics
GET /departments/{id}/offer-letters # Offer letters by department
```

#### 9. Payroll Management
```http
GET /payrolls                       # List all payrolls
GET /payrolls/create                # Show create form
POST /payrolls                      # Create payroll
GET /payrolls/{id}                  # Show payroll details
GET /payrolls/{id}/edit             # Show edit form
PUT /payrolls/{id}                  # Update payroll
DELETE /payrolls/{id}               # Delete payroll
PATCH /payrolls/{id}/mark-as-paid   # Mark as paid
PATCH /payrolls/{id}/mark-as-failed # Mark as failed
GET /payrolls/statistics            # Payroll statistics
GET /employees/{id}/payrolls        # Payrolls by employee
POST /payrolls/generate-bulk        # Generate bulk payrolls
```

### Role-Based Web Routes

#### HR & Manager Only
```http
GET /dashboard/statistics            # Dashboard statistics
```

#### Manager Only
```http
GET /manager/approvals              # Manager approvals
```

#### Employee Only
```http
GET /employee/profile                # Employee profile
GET /employee/payrolls              # Employee payrolls
```

---

## 🔓 Public Endpoints (No Authentication Required)

### 1. Login
```http
POST /login
Content-Type: application/json
Accept: application/json

{
  "email": "gokul.kumar.dev@company.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 9,
      "name": "Gokul Kumar",
      "email": "gokul.kumar.dev@company.com",
      "role": "employee",
      "is_active": true
    },
    "token": "1|RMbcqdnZWi5tTOStOI7nS0edaOkdxtiLfyLAfcnEe13fd53c"
  }
}
```

---

## 🔐 Protected Endpoints (Authentication Required)

### Authentication Endpoints

#### 2. Logout
```http
POST /logout
Authorization: Bearer {token}
Accept: application/json
```

#### 3. Get Profile
```http
GET /profile
Authorization: Bearer {token}
Accept: application/json
```

#### 4. Update Profile
```http
PUT /profile
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json

{
  "name": "Updated Name",
  "email": "updated@email.com"
}
```

#### 5. Check Authentication
```http
GET /check-auth
Authorization: Bearer {token}
Accept: application/json
```

---

### Department Endpoints

#### 6. List All Departments
```http
GET /departments
Authorization: Bearer {token}
Accept: application/json
```

#### 7. Get Specific Department
```http
GET /departments/{id}
Authorization: Bearer {token}
Accept: application/json
```

#### 8. Create Department
```http
POST /departments
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json

{
  "name": "New Department",
  "description": "Department description",
  "location": "Building A",
  "is_active": true
}
```

#### 9. Update Department
```http
PUT /departments/{id}
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json

{
  "name": "Updated Department",
  "description": "Updated description",
  "location": "Building B",
  "is_active": true
}
```

#### 10. Delete Department
```http
DELETE /departments/{id}
Authorization: Bearer {token}
Accept: application/json
```

#### 11. Department Statistics
```http
GET /departments/{id}/statistics
Authorization: Bearer {token}
Accept: application/json
```

#### 12. Departments with Employee Count
```http
GET /departments-with-employee-count
Authorization: Bearer {token}
Accept: application/json
```

#### 13. Toggle Department Status
```http
PATCH /departments/{id}/toggle-status
Authorization: Bearer {token}
Accept: application/json
```

---

### Employee Endpoints

#### 14. List All Employees
```http
GET /employees
Authorization: Bearer {token}
Accept: application/json
```

#### 15. Get Specific Employee
```http
GET /employees/{id}
Authorization: Bearer {token}
Accept: application/json
```

#### 16. Create Employee
```http
POST /employees
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json

{
  "user_id": 1,
  "department_id": 1,
  "employee_id": "EMP001",
  "first_name": "John",
  "last_name": "Doe",
  "phone": "+1234567890",
  "date_of_birth": "1990-01-01",
  "gender": "male",
  "address": "123 Main St",
  "emergency_contact_name": "Jane Doe",
  "emergency_contact_phone": "+1234567891",
  "position": "Software Developer",
  "hire_date": "2023-01-01",
  "salary": 65000.00,
  "employment_status": "active",
  "is_onboarded": false
}
```

#### 17. Update Employee
```http
PUT /employees/{id}
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json

{
  "first_name": "Updated First Name",
  "last_name": "Updated Last Name",
  "position": "Senior Developer",
  "salary": 75000.00
}
```

#### 18. Delete Employee
```http
DELETE /employees/{id}
Authorization: Bearer {token}
Accept: application/json
```

#### 19. Complete Employee Onboarding
```http
PATCH /employees/{id}/complete-onboarding
Authorization: Bearer {token}
Accept: application/json
```

#### 20. Employee Statistics
```http
GET /employees/statistics
Authorization: Bearer {token}
Accept: application/json
```

#### 21. Employees by Department
```http
GET /departments/{id}/employees
Authorization: Bearer {token}
Accept: application/json
```

---

### Offer Letter Endpoints

#### 22. List All Offer Letters
```http
GET /offer-letters
Authorization: Bearer {token}
Accept: application/json
```

#### 23. Get Specific Offer Letter
```http
GET /offer-letters/{id}
Authorization: Bearer {token}
Accept: application/json
```

#### 24. Create Offer Letter
```http
POST /offer-letters
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json

{
  "candidate_name": "John Doe",
  "candidate_email": "john.doe@email.com",
  "candidate_phone": "+1234567890",
  "department_id": 1,
  "position": "Software Developer",
  "salary": 65000.00,
  "start_date": "2024-01-15",
  "offer_terms": "Full-time position with benefits",
  "status": "draft"
}
```

#### 25. Update Offer Letter
```http
PUT /offer-letters/{id}
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json

{
  "candidate_name": "Updated Name",
  "position": "Senior Developer",
  "salary": 75000.00,
  "status": "sent"
}
```

#### 26. Delete Offer Letter
```http
DELETE /offer-letters/{id}
Authorization: Bearer {token}
Accept: application/json
```

#### 27. Send Offer Letter
```http
PATCH /offer-letters/{id}/send
Authorization: Bearer {token}
Accept: application/json
```

#### 28. Approve Offer Letter
```http
PATCH /offer-letters/{id}/approve
Authorization: Bearer {token}
Accept: application/json
```

#### 29. Update Offer Letter Status
```http
PATCH /offer-letters/{id}/update-status
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json

{
  "status": "accepted"
}
```

#### 30. Offer Letter Statistics
```http
GET /offer-letters/statistics
Authorization: Bearer {token}
Accept: application/json
```

#### 31. Offer Letters by Department
```http
GET /departments/{id}/offer-letters
Authorization: Bearer {token}
Accept: application/json
```

---

### Payroll Endpoints

#### 32. List All Payrolls
```http
GET /payrolls
Authorization: Bearer {token}
Accept: application/json
```

#### 33. Get Specific Payroll
```http
GET /payrolls/{id}
Authorization: Bearer {token}
Accept: application/json
```

#### 34. Create Payroll
```http
POST /payrolls
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json

{
  "employee_id": 1,
  "payroll_period": "2024-01",
  "basic_salary": 5000.00,
  "allowances": 500.00,
  "deductions": 200.00,
  "net_salary": 5300.00,
  "payment_status": "pending"
}
```

#### 35. Update Payroll
```http
PUT /payrolls/{id}
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json

{
  "basic_salary": 5500.00,
  "allowances": 600.00,
  "deductions": 250.00,
  "net_salary": 5850.00
}
```

#### 36. Delete Payroll
```http
DELETE /payrolls/{id}
Authorization: Bearer {token}
Accept: application/json
```

#### 37. Mark Payroll as Paid
```http
PATCH /payrolls/{id}/mark-as-paid
Authorization: Bearer {token}
Accept: application/json
```

#### 38. Mark Payroll as Failed
```http
PATCH /payrolls/{id}/mark-as-failed
Authorization: Bearer {token}
Accept: application/json
```

#### 39. Payroll Statistics
```http
GET /payrolls/statistics
Authorization: Bearer {token}
Accept: application/json
```

#### 40. Payrolls by Employee
```http
GET /employees/{id}/payrolls
Authorization: Bearer {token}
Accept: application/json
```

#### 41. Generate Bulk Payrolls
```http
POST /payrolls/generate-bulk
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json

{
  "payroll_period": "2024-01",
  "department_id": 1
}
```

---

## 👥 HR & Manager Only Endpoints

### 42. Dashboard Statistics
```http
GET /dashboard/statistics
Authorization: Bearer {token}
Accept: application/json
```

**Access:** HR, Manager

---

## 👨‍💼 Manager Only Endpoints

### 43. Manager Approvals
```http
GET /manager/approvals
Authorization: Bearer {token}
Accept: application/json
```

**Access:** Manager only

---

## 👤 Employee Only Endpoints

### 44. Employee Profile
```http
GET /employee/profile
Authorization: Bearer {token}
Accept: application/json
```

**Access:** Employee only

### 45. Employee Payrolls
```http
GET /employee/payrolls
Authorization: Bearer {token}
Accept: application/json
```

**Access:** Employee only

---

## 🧪 Test Endpoint

### 46. Test Database
```http
GET /test-db
Accept: application/json
```

**Purpose:** Check database status and seed if needed

---

## 📊 Response Formats

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    // Response data
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Error details"]
  }
}
```

### Paginated Response
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      // Array of items
    ],
    "first_page_url": "...",
    "from": 1,
    "last_page": 1,
    "last_page_url": "...",
    "links": [...],
    "next_page_url": null,
    "path": "...",
    "per_page": 15,
    "prev_page_url": null,
    "to": 10,
    "total": 10
  }
}
```

---

## 🔐 Role-Based Access Summary

| Endpoint Category | HR | Manager | Employee |
|------------------|----|---------|----------|
| Authentication | ✅ | ✅ | ✅ |
| Profile Management | ✅ | ✅ | ✅ |
| Departments | ✅ | ✅ | ✅ |
| Employees | ✅ | ✅ | ✅ |
| Offer Letters | ✅ | ✅ | ✅ |
| Payroll | ✅ | ✅ | ✅ |
| Dashboard Statistics | ✅ | ✅ | ❌ |
| Manager Approvals | ❌ | ✅ | ❌ |
| Employee Profile | ❌ | ❌ | ✅ |
| Employee Payrolls | ❌ | ❌ | ✅ |

---

## 🧪 Testing Users

### HR Users
- `gokul.kumar@company.com` (password: password123)
- `vardhan.sharma@company.com` (password: password123)
- `gokul.patel@company.com` (password: password123)

### Manager Users
- `vardhan.kumar@company.com` (password: password123)
- `gokul.reddy@company.com` (password: password123)
- `vardhan.sharma.marketing@company.com` (password: password123)
- `gokul.patel.sales@company.com` (password: password123)
- `vardhan.reddy@company.com` (password: password123)

### Employee Users
- `gokul.kumar.dev@company.com` (password: password123)
- `vardhan.sharma.qa@company.com` (password: password123)
- `gokul.patel.finance@company.com` (password: password123)
- `vardhan.kumar.marketing@company.com` (password: password123)
- `gokul.reddy.sales@company.com` (password: password123)
- `vardhan.sharma.ops@company.com` (password: password123)
- `gokul.kumar.support@company.com` (password: password123)

---

## 📝 Notes

1. **Base URL**: All endpoints are prefixed with `/api`
2. **Authentication**: Use the token received from login in the `Authorization` header
3. **Content-Type**: Use `application/json` for POST/PUT requests
4. **Accept**: Use `application/json` for all requests
5. **Error Handling**: All endpoints return consistent error formats
6. **Pagination**: List endpoints support pagination with query parameters
7. **Filtering**: Many endpoints support filtering and search parameters

---

## 🚀 Quick Start

1. **Login** to get a token
2. **Use the token** in the Authorization header for all other requests
3. **Test role-based access** with different user accounts
4. **Check the response format** for consistent data structure

Total Endpoints: **46**
- Public: 1
- Protected: 45
- HR/Manager: 1
- Manager Only: 1
- Employee Only: 2 

**Web Routes: 50+**
- Public: 3
- Protected: 47+
- Role-based: 3 
