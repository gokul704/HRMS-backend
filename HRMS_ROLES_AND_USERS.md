# HRMS Roles and Users - Complete Reference

## 🏢 HRMS System Overview

The HRMS (Human Resource Management System) implements a **Role-Based Access Control (RBAC)** system with three distinct roles, each with specific permissions and access levels.

---

## 🔐 Role Hierarchy & Access Levels

### 📊 Access Matrix

| Feature Category | HR | Manager | Employee |
|------------------|----|---------|----------|
| **Authentication** | ✅ Full | ✅ Full | ✅ Full |
| **Profile Management** | ✅ Full | ✅ Full | ✅ Full |
| **Department Management** | ✅ Full CRUD | ✅ Full CRUD | ✅ Read Only |
| **Employee Management** | ✅ Full CRUD | ✅ Full CRUD | ✅ Read Only |
| **Offer Letter Management** | ✅ Full CRUD | ✅ Full CRUD | ✅ Read Only |
| **Payroll Management** | ✅ Full CRUD | ✅ Full CRUD | ✅ Read Only |
| **System Statistics** | ✅ Dashboard | ✅ Dashboard | ❌ No Access |
| **Approval Workflows** | ❌ No Access | ✅ Manager Approvals | ❌ No Access |
| **Personal Data** | ❌ No Access | ❌ No Access | ✅ Personal Profile & Payroll |

---

## 👥 Role Details

### 🏛️ **HR (Human Resources)**

**Role Description:** Human Resources professionals with full administrative access to all HRMS features.

**Primary Responsibilities:**
- Employee lifecycle management
- Department administration
- Offer letter processing
- Payroll oversight
- HR policy enforcement

**Access Level:** `Full Administrative Access`

**Key Features:**
- ✅ **Complete CRUD Operations** on all modules
- ✅ **Department Management** - Create, update, delete departments
- ✅ **Employee Management** - Full employee lifecycle management
- ✅ **Offer Letter Processing** - Create, send, track offer letters
- ✅ **Payroll Management** - Full payroll processing and oversight
- ✅ **System Statistics** - Access to dashboard and analytics
- ✅ **User Management** - Manage all user accounts and roles

**Restrictions:**
- ❌ Cannot access manager-specific approval workflows
- ❌ Cannot access personal employee data

---

### 👨‍💼 **Manager**

**Role Description:** Department and team managers with administrative access plus approval workflows.

**Primary Responsibilities:**
- Team management
- Department oversight
- Approval workflows
- Performance management
- Resource allocation

**Access Level:** `Full Administrative Access + Approvals`

**Key Features:**
- ✅ **Complete CRUD Operations** on all modules
- ✅ **Department Management** - Full department administration
- ✅ **Employee Management** - Team member management
- ✅ **Offer Letter Processing** - Create and manage offer letters
- ✅ **Payroll Management** - Full payroll processing
- ✅ **System Statistics** - Access to dashboard and analytics
- ✅ **Approval Workflows** - Manager-specific approval processes
- ✅ **Team Oversight** - Manage team members and performance

**Special Capabilities:**
- 🔐 **Manager Approvals** - Exclusive access to approval workflows
- 📊 **Team Analytics** - Department-specific statistics
- 👥 **Team Management** - Direct team oversight

**Restrictions:**
- ❌ Cannot access personal employee data
- ❌ Cannot override HR policies

---

### 👤 **Employee**

**Role Description:** Regular employees with limited access and personal data access.

**Primary Responsibilities:**
- Personal profile management
- Payroll information access
- Company information viewing
- Self-service features

**Access Level:** `Limited Access + Personal Data`

**Key Features:**
- ✅ **Authentication** - Login and profile management
- ✅ **Read Access** - View departments, employees, offer letters, payrolls
- ✅ **Personal Profile** - Access to own employee profile
- ✅ **Personal Payroll** - Access to own payroll history
- ✅ **Company Information** - View company structure and policies

**Personal Data Access:**
- 👤 **Employee Profile** - View and update personal information
- 💰 **Payroll History** - Access to personal payroll records
- 📋 **Personal Documents** - Access to personal HR documents

**Restrictions:**
- ❌ Cannot create, update, or delete system data
- ❌ Cannot access other employees' personal data
- ❌ Cannot access system statistics or approvals
- ❌ Read-only access to most features

---

## 🔌 Available APIs by Role

### 🏛️ **HR APIs (Full Access)**

**Authentication APIs:**
```http
POST /api/login                    # Login
POST /api/logout                   # Logout
GET  /api/profile                  # Get profile
PUT  /api/profile                  # Update profile
GET  /api/check-auth              # Check authentication
```

**Department APIs:**
```http
GET    /api/departments                    # List all departments
GET    /api/departments/{id}              # Get specific department
POST   /api/departments                   # Create department
PUT    /api/departments/{id}              # Update department
DELETE /api/departments/{id}              # Delete department
GET    /api/departments/{id}/statistics   # Department statistics
GET    /api/departments-with-employee-count # Departments with employee count
PATCH  /api/departments/{id}/toggle-status # Toggle department status
```

**Employee APIs:**
```http
GET    /api/employees                     # List all employees
GET    /api/employees/{id}                # Get specific employee
POST   /api/employees                     # Create employee
PUT    /api/employees/{id}                # Update employee
DELETE /api/employees/{id}                # Delete employee
PATCH  /api/employees/{id}/complete-onboarding # Complete onboarding
GET    /api/employees/statistics          # Employee statistics
GET    /api/departments/{id}/employees   # Employees by department
```

**Offer Letter APIs:**
```http
GET    /api/offer-letters                 # List all offer letters
GET    /api/offer-letters/{id}            # Get specific offer letter
POST   /api/offer-letters                 # Create offer letter
PUT    /api/offer-letters/{id}            # Update offer letter
DELETE /api/offer-letters/{id}            # Delete offer letter
PATCH  /api/offer-letters/{id}/send       # Send offer letter
PATCH  /api/offer-letters/{id}/approve    # Approve offer letter
PATCH  /api/offer-letters/{id}/update-status # Update offer status
GET    /api/offer-letters/statistics      # Offer letter statistics
GET    /api/departments/{id}/offer-letters # Offer letters by department
```

**Payroll APIs:**
```http
GET    /api/payrolls                      # List all payrolls
GET    /api/payrolls/{id}                 # Get specific payroll
POST   /api/payrolls                      # Create payroll
PUT    /api/payrolls/{id}                 # Update payroll
DELETE /api/payrolls/{id}                 # Delete payroll
PATCH  /api/payrolls/{id}/mark-as-paid    # Mark payroll as paid
PATCH  /api/payrolls/{id}/mark-as-failed  # Mark payroll as failed
GET    /api/payrolls/statistics           # Payroll statistics
GET    /api/employees/{id}/payrolls       # Payrolls by employee
POST   /api/payrolls/generate-bulk        # Generate bulk payrolls
```

**Dashboard APIs:**
```http
GET /api/dashboard/statistics             # Dashboard statistics
```

**Total HR APIs: 32 endpoints**

---

### 👨‍💼 **Manager APIs (Full Access + Approvals)**

**All HR APIs + Manager-Specific APIs:**

**Manager Approval APIs:**
```http
GET /api/manager/approvals               # Manager approvals dashboard
```

**Manager-Specific Features:**
- All CRUD operations on departments, employees, offer letters, payrolls
- Dashboard statistics and analytics
- Team-specific reports and analytics
- Approval workflow management

**Total Manager APIs: 33 endpoints**

---

### 👤 **Employee APIs (Limited Access + Personal Data)**

**Authentication APIs:**
```http
POST /api/login                    # Login
POST /api/logout                   # Logout
GET  /api/profile                  # Get profile
PUT  /api/profile                  # Update profile
GET  /api/check-auth              # Check authentication
```

**Read-Only APIs:**
```http
GET /api/departments               # List all departments (read-only)
GET /api/departments/{id}          # Get specific department (read-only)
GET /api/employees                 # List all employees (read-only)
GET /api/employees/{id}            # Get specific employee (read-only)
GET /api/offer-letters             # List all offer letters (read-only)
GET /api/offer-letters/{id}        # Get specific offer letter (read-only)
GET /api/payrolls                  # List all payrolls (read-only)
GET /api/payrolls/{id}             # Get specific payroll (read-only)
```

**Employee-Specific APIs:**
```http
GET /api/employee/profile           # Get own employee profile
GET /api/employee/payrolls         # Get own payroll history
```

**Total Employee APIs: 12 endpoints**

---

## 📊 API Access Summary

| API Category | HR | Manager | Employee |
|--------------|----|---------|----------|
| **Authentication** | ✅ Full (5) | ✅ Full (5) | ✅ Full (5) |
| **Departments** | ✅ Full CRUD (8) | ✅ Full CRUD (8) | ✅ Read Only (2) |
| **Employees** | ✅ Full CRUD (8) | ✅ Full CRUD (8) | ✅ Read Only (2) |
| **Offer Letters** | ✅ Full CRUD (10) | ✅ Full CRUD (10) | ✅ Read Only (2) |
| **Payroll** | ✅ Full CRUD (10) | ✅ Full CRUD (10) | ✅ Read Only (2) |
| **Dashboard** | ✅ Statistics (1) | ✅ Statistics (1) | ❌ None |
| **Manager Approvals** | ❌ None | ✅ Approvals (1) | ❌ None |
| **Employee Personal** | ❌ None | ❌ None | ✅ Personal (2) |
| **Total Endpoints** | **32** | **33** | **12** |

---

## 🔐 API Security & Authentication

### **Authentication Flow:**
1. **Login:** `POST /api/login` with email/password
2. **Token:** Receive JWT token in response
3. **Authorization:** Include `Authorization: Bearer {token}` header
4. **Validation:** Middleware validates token and role permissions

### **Role-Based API Protection:**
- **HR & Manager:** Full access to all CRUD operations
- **Employee:** Read-only access to most endpoints + personal data
- **Manager Only:** Exclusive access to approval workflows
- **Employee Only:** Exclusive access to personal profile/payroll

### **API Response Formats:**

**Success Response:**
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    // Response data
  }
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Error details"]
  }
}
```

**Unauthorized Response (403):**
```json
{
  "success": false,
  "message": "Access denied. Insufficient permissions."
}
```

---

## 👥 User Directory

### 🏛️ **HR Users**

| Name | Email | Position | Department | Status |
|------|-------|----------|------------|--------|
| **Gokul Kumar** | `gokul.kumar@company.com` | HR Director | Human Resources | Active |
| **Vardhan Sharma** | `vardhan.sharma@company.com` | HR Specialist | Human Resources | Active |
| **Gokul Patel** | `gokul.patel@company.com` | HR Assistant | Human Resources | Active |

**Password for all HR users:** `password123`

---

### 👨‍💼 **Manager Users**

| Name | Email | Position | Department | Status |
|------|-------|----------|------------|--------|
| **Vardhan Kumar** | `vardhan.kumar@company.com` | IT Manager | Information Technology | Active |
| **Gokul Reddy** | `gokul.reddy@company.com` | Finance Manager | Finance | Active |
| **Vardhan Sharma** | `vardhan.sharma.marketing@company.com` | Marketing Manager | Marketing | Active |
| **Gokul Patel** | `gokul.patel.sales@company.com` | Sales Manager | Sales | Active |
| **Vardhan Reddy** | `vardhan.reddy@company.com` | Operations Manager | Operations | Active |

**Password for all Manager users:** `password123`

---

### 👤 **Employee Users**

| Name | Email | Position | Department | Status |
|------|-------|----------|------------|--------|
| **Gokul Kumar** | `gokul.kumar.dev@company.com` | Software Developer | Information Technology | Active |
| **Vardhan Sharma** | `vardhan.sharma.qa@company.com` | QA Tester | Information Technology | Active |
| **Gokul Patel** | `gokul.patel.finance@company.com` | Accountant | Finance | Active |
| **Vardhan Kumar** | `vardhan.kumar.marketing@company.com` | Marketing Specialist | Marketing | Active |
| **Gokul Reddy** | `gokul.reddy.sales@company.com` | Sales Representative | Sales | Active |
| **Vardhan Sharma** | `vardhan.sharma.ops@company.com` | Operations Coordinator | Operations | Active |
| **Gokul Kumar** | `gokul.kumar.support@company.com` | Customer Support Specialist | Customer Support | Active |

**Password for all Employee users:** `password123`

---

### 🚨 **Special Status Users**

| Name | Email | Position | Department | Status | Notes |
|------|-------|----------|------------|--------|-------|
| **Vardhan Patel** | `vardhan.patel.inactive@company.com` | System Administrator | Information Technology | Inactive | User account disabled |
| **Gokul Sharma** | `gokul.sharma.terminated@company.com` | Content Writer | Marketing | Terminated | Position eliminated |
| **Vardhan Reddy** | `vardhan.reddy.pending@company.com` | Junior Sales Representative | Sales | Pending Onboarding | New hire, onboarding incomplete |

---

## 🔐 Role-Specific Features

### 🏛️ **HR Features**

**Core HR Functions:**
- 👥 **Employee Lifecycle Management**
  - Employee onboarding and offboarding
  - Position management and transfers
  - Employment status updates
  - Document management

- 🏢 **Department Administration**
  - Department creation and management
  - Organizational structure maintenance
  - Location and facility management

- 📄 **Offer Letter Processing**
  - Create and manage offer letters
  - Track offer status and responses
  - Generate offer templates

- 💰 **Payroll Oversight**
  - Payroll generation and processing
  - Salary structure management
  - Benefits administration

- 📊 **HR Analytics**
  - Employee statistics and reports
  - Department performance metrics
  - HR policy compliance tracking

**HR-Specific Endpoints:**
- All CRUD operations on departments, employees, offer letters, payrolls
- System-wide statistics and analytics
- User account management

---

### 👨‍💼 **Manager Features**

**Core Management Functions:**
- 👥 **Team Management**
  - Direct team oversight
  - Performance management
  - Resource allocation
  - Team development

- ✅ **Approval Workflows**
  - Offer letter approvals
  - Payroll approvals
  - Expense approvals
  - Leave requests

- 📊 **Department Analytics**
  - Team performance metrics
  - Department statistics
  - Resource utilization

- 🎯 **Strategic Planning**
  - Department planning
  - Budget management
  - Goal setting and tracking

**Manager-Specific Endpoints:**
- All CRUD operations on departments, employees, offer letters, payrolls
- Dashboard statistics and analytics
- Manager approval workflows
- Team-specific reports

---

### 👤 **Employee Features**

**Core Employee Functions:**
- 👤 **Personal Profile Management**
  - View and update personal information
  - Emergency contact management
  - Document access

- 💰 **Payroll Information**
  - View salary details
  - Access payroll history
  - Benefits information

- 🏢 **Company Information**
  - View department structure
  - Access company policies
  - View organizational chart

- 📋 **Self-Service**
  - Profile updates
  - Document requests
  - Information access

**Employee-Specific Endpoints:**
- Read access to departments, employees, offer letters, payrolls
- Personal employee profile access
- Personal payroll history access
- Profile update capabilities

---

## 🔐 Security & Permissions

### **Authentication & Authorization**

**Login Process:**
1. User provides email and password
2. System validates credentials
3. Returns JWT token for authenticated sessions
4. Token used for all subsequent requests

**Role Validation:**
- Middleware checks user role on protected endpoints
- Role-based access control enforced at API level
- Unauthorized access returns 403 Forbidden

### **Data Access Patterns**

**HR Access Pattern:**
```
HR User → Full System Access → All CRUD Operations → System Administration
```

**Manager Access Pattern:**
```
Manager User → Full System Access + Approvals → Team Management → Approval Workflows
```

**Employee Access Pattern:**
```
Employee User → Limited Read Access + Personal Data → Self-Service → Personal Information
```

---

## 📊 Feature Comparison Matrix

| Feature | HR | Manager | Employee |
|---------|----|---------|----------|
| **User Management** | ✅ Full | ✅ Full | ❌ None |
| **Department CRUD** | ✅ Full | ✅ Full | ✅ Read |
| **Employee CRUD** | ✅ Full | ✅ Full | ✅ Read |
| **Offer Letter CRUD** | ✅ Full | ✅ Full | ✅ Read |
| **Payroll CRUD** | ✅ Full | ✅ Full | ✅ Read |
| **System Statistics** | ✅ Full | ✅ Full | ❌ None |
| **Approval Workflows** | ❌ None | ✅ Full | ❌ None |
| **Personal Profile** | ❌ None | ❌ None | ✅ Full |
| **Personal Payroll** | ❌ None | ❌ None | ✅ Full |
| **Team Management** | ✅ Full | ✅ Full | ❌ None |
| **Document Management** | ✅ Full | ✅ Full | ✅ Personal |
| **Analytics & Reports** | ✅ Full | ✅ Department | ❌ None |

---

## 🚀 Quick Start Guide

### **For HR Users:**
1. Login with HR credentials
2. Access full administrative features
3. Manage departments, employees, and payrolls
4. Process offer letters and HR workflows

### **For Managers:**
1. Login with manager credentials
2. Access team management features
3. Handle approval workflows
4. View department analytics

### **For Employees:**
1. Login with employee credentials
2. Access personal profile and payroll
3. View company information
4. Use self-service features

---

## 📝 Notes

1. **Password Security:** All users use `password123` for testing
2. **Token Management:** JWT tokens expire and need renewal
3. **Role Hierarchy:** Manager > Employee, HR has administrative privileges
4. **Data Privacy:** Personal data is role-restricted
5. **Audit Trail:** All actions are logged for compliance
6. **Mobile Access:** All features accessible via mobile API

---

## 🎯 Summary

**Total Users: 18**
- **HR Users:** 3
- **Manager Users:** 5  
- **Employee Users:** 7
- **Special Status Users:** 3

**Role Distribution:**
- **Gokul:** 8 users across all roles
- **Vardhan:** 10 users across all roles

**API Endpoints by Role:**
- **HR:** 32 endpoints (Full CRUD + Statistics)
- **Manager:** 33 endpoints (Full CRUD + Statistics + Approvals)
- **Employee:** 12 endpoints (Read-only + Personal data)

**System Capabilities:**
- **Full Administrative:** HR and Manager roles
- **Approval Workflows:** Manager role only
- **Personal Data Access:** Employee role only
- **Read-Only Access:** Employee role for most features

The HRMS system provides comprehensive role-based access control with clear separation of responsibilities and appropriate data access patterns for each user role. 
