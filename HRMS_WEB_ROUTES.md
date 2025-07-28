# HRMS Web Routes - Complete Reference

## Base URL
```
https://hrms-backend-production-2e6d.up.railway.app
```

## 🌐 Web Interface Overview

The HRMS system provides a comprehensive web interface built with Laravel Blade templates and Bootstrap 5. The interface includes:

- **Modern, responsive design** with gradient backgrounds and clean UI
- **Role-based navigation** that adapts to user permissions
- **Interactive dashboards** with statistics and quick actions
- **CRUD operations** for all major entities
- **Form validation** and error handling
- **Real-time notifications** and success messages

---

## 🔓 Public Web Routes

### 1. Welcome Page
```http
GET /
```
**Purpose:** Landing page with system overview and demo account information
**Features:**
- System introduction and features
- Demo account credentials
- Call-to-action buttons
- Modern gradient design

### 2. Login Page
```http
GET /login
```
**Purpose:** User authentication form
**Features:**
- Email and password fields
- Form validation
- Demo account information
- Responsive design

### 3. Login Form Submission
```http
POST /login
Content-Type: application/x-www-form-urlencoded

email=gokul.kumar.dev@company.com&password=password123
```
**Purpose:** Process user authentication
**Features:**
- Session-based authentication
- Role-based redirects
- Error handling
- Success messages

---

## 🔐 Protected Web Routes (Authentication Required)

### 4. Dashboard
```http
GET /dashboard
```
**Purpose:** Main dashboard with role-based content
**Features:**
- **HR/Manager Dashboard:**
  - Statistics cards (employees, departments, offers, payroll)
  - Recent activities
  - Quick action buttons
  - Employee and offer letter lists
- **Employee Dashboard:**
  - Personal information display
  - Quick links to profile and payrolls
  - Welcome message

### 5. Profile Management
```http
GET /profile
PUT /profile
```
**Purpose:** User account management
**Features:**
- Account information form
- Password change option
- Role and status display
- Employee details (for employees)

---

## 🏢 Department Management

### 6. List Departments
```http
GET /departments
```
**Features:**
- Table with department information
- Employee count badges
- Status indicators
- Action buttons (view, edit, statistics, toggle, delete)

### 7. Create Department
```http
GET /departments/create
POST /departments
```
**Features:**
- Form with name, description, location fields
- Validation and error handling
- Success redirect

### 8. View Department
```http
GET /departments/{id}
```
**Features:**
- Detailed department information
- Employee list
- Statistics overview
- Action buttons

### 9. Edit Department
```http
GET /departments/{id}/edit
PUT /departments/{id}
```
**Features:**
- Pre-filled form with current data
- Validation
- Success/error messages

### 10. Department Statistics
```http
GET /departments/{id}/statistics
```
**Features:**
- Employee count
- Average salary
- Active/inactive status
- Charts and graphs

### 11. Toggle Department Status
```http
PATCH /departments/{id}/toggle-status
```
**Features:**
- Activate/deactivate departments
- Confirmation dialogs
- Status updates

### 12. Delete Department
```http
DELETE /departments/{id}
```
**Features:**
- Confirmation dialog
- Cascade handling
- Success messages

---

## 👥 Employee Management

### 13. List Employees
```http
GET /employees
```
**Features:**
- Employee table with key information
- Status badges (active/inactive)
- Onboarding status
- Action buttons

### 14. Create Employee
```http
GET /employees/create
POST /employees
```
**Features:**
- Comprehensive employee form
- Department selection
- Validation rules
- File uploads (if needed)

### 15. View Employee
```http
GET /employees/{id}
```
**Features:**
- Detailed employee profile
- Personal information
- Employment details
- Related data (payrolls, etc.)

### 16. Edit Employee
```http
GET /employees/{id}/edit
PUT /employees/{id}
```
**Features:**
- Editable form
- Field validation
- Success handling

### 17. Complete Onboarding
```http
PATCH /employees/{id}/complete-onboarding
```
**Features:**
- One-click onboarding completion
- Status updates
- Notifications

### 18. Employee Statistics
```http
GET /employees/statistics
```
**Features:**
- Total employee count
- Department distribution
- Salary statistics
- Charts and analytics

### 19. Employees by Department
```http
GET /departments/{id}/employees
```
**Features:**
- Filtered employee list
- Department-specific view
- Employee details

### 20. Delete Employee
```http
DELETE /employees/{id}
```
**Features:**
- Confirmation dialog
- Data cleanup
- Success messages

---

## 📄 Offer Letter Management

### 21. List Offer Letters
```http
GET /offer-letters
```
**Features:**
- Offer letter table
- Status badges
- Candidate information
- Action buttons

### 22. Create Offer Letter
```http
GET /offer-letters/create
POST /offer-letters
```
**Features:**
- Offer letter form
- Department selection
- Salary and terms
- Status management

### 23. View Offer Letter
```http
GET /offer-letters/{id}
```
**Features:**
- Detailed offer information
- Status tracking
- Action history

### 24. Edit Offer Letter
```http
GET /offer-letters/{id}/edit
PUT /offer-letters/{id}
```
**Features:**
- Editable form
- Status updates
- Validation

### 25. Send Offer Letter
```http
PATCH /offer-letters/{id}/send
```
**Features:**
- Email integration
- Status updates
- Notifications

### 26. Approve Offer Letter
```http
PATCH /offer-letters/{id}/approve
```
**Features:**
- Approval workflow
- Status changes
- Notifications

### 27. Update Offer Status
```http
PATCH /offer-letters/{id}/update-status
```
**Features:**
- Status dropdown
- Workflow management
- History tracking

### 28. Offer Letter Statistics
```http
GET /offer-letters/statistics
```
**Features:**
- Status distribution
- Department breakdown
- Time analytics

### 29. Offer Letters by Department
```http
GET /departments/{id}/offer-letters
```
**Features:**
- Filtered view
- Department-specific offers
- Status overview

### 30. Delete Offer Letter
```http
DELETE /offer-letters/{id}
```
**Features:**
- Confirmation
- Data cleanup
- Success handling

---

## 💰 Payroll Management

### 31. List Payrolls
```http
GET /payrolls
```
**Features:**
- Payroll table
- Payment status badges
- Employee information
- Action buttons

### 32. Create Payroll
```http
GET /payrolls/create
POST /payrolls
```
**Features:**
- Payroll calculation form
- Employee selection
- Salary components
- Validation

### 33. View Payroll
```http
GET /payrolls/{id}
```
**Features:**
- Detailed payroll breakdown
- Salary components
- Payment status
- Employee details

### 34. Edit Payroll
```http
GET /payrolls/{id}/edit
PUT /payrolls/{id}
```
**Features:**
- Editable form
- Calculation updates
- Validation

### 35. Mark as Paid
```http
PATCH /payrolls/{id}/mark-as-paid
```
**Features:**
- Payment status update
- Notifications
- History tracking

### 36. Mark as Failed
```http
PATCH /payrolls/{id}/mark-as-failed
```
**Features:**
- Payment failure handling
- Status updates
- Notifications

### 37. Payroll Statistics
```http
GET /payrolls/statistics
```
**Features:**
- Total payroll amount
- Payment status distribution
- Department breakdown
- Charts and analytics

### 38. Payrolls by Employee
```http
GET /employees/{id}/payrolls
```
**Features:**
- Employee-specific payrolls
- Payment history
- Status overview

### 39. Generate Bulk Payrolls
```http
POST /payrolls/generate-bulk
```
**Features:**
- Bulk generation form
- Department selection
- Period specification
- Progress tracking

### 40. Delete Payroll
```http
DELETE /payrolls/{id}
```
**Features:**
- Confirmation dialog
- Data cleanup
- Success messages

---

## 👥 Role-Based Web Routes

### HR & Manager Only

#### 41. Dashboard Statistics
```http
GET /dashboard/statistics
```
**Features:**
- Advanced analytics
- Charts and graphs
- Performance metrics
- Export capabilities

### Manager Only

#### 42. Manager Approvals
```http
GET /manager/approvals
```
**Features:**
- Pending approvals list
- Approval actions
- Workflow management
- Status tracking

### Employee Only

#### 43. Employee Profile
```http
GET /employee/profile
```
**Features:**
- Personal information
- Employment details
- Document access
- Self-service features

#### 44. Employee Payrolls
```http
GET /employee/payrolls
```
**Features:**
- Personal payroll history
- Payment status
- Salary information
- Download capabilities

---

## 🎨 UI/UX Features

### Design System
- **Color Scheme:** Gradient backgrounds with purple/blue theme
- **Typography:** Clean, modern fonts with proper hierarchy
- **Icons:** Font Awesome icons throughout the interface
- **Responsive:** Mobile-first design with Bootstrap 5

### Navigation
- **Sidebar:** Role-based navigation with active states
- **Breadcrumbs:** Clear navigation path
- **Quick Actions:** Contextual action buttons
- **Search:** Global search functionality

### Forms
- **Validation:** Real-time form validation
- **Error Handling:** Clear error messages
- **Success Feedback:** Toast notifications
- **Auto-save:** Draft saving for long forms

### Tables
- **Sorting:** Column sorting capabilities
- **Filtering:** Advanced filtering options
- **Pagination:** Efficient data loading
- **Export:** CSV/PDF export options

### Dashboard
- **Statistics Cards:** Key metrics display
- **Charts:** Interactive charts and graphs
- **Recent Activity:** Timeline of recent actions
- **Quick Actions:** One-click common tasks

---

## 🔐 Security Features

### Authentication
- **Session Management:** Secure session handling
- **CSRF Protection:** Built-in CSRF tokens
- **Password Security:** Bcrypt hashing
- **Rate Limiting:** Login attempt protection

### Authorization
- **Role-Based Access:** HR, Manager, Employee roles
- **Route Protection:** Middleware-based access control
- **Permission Checks:** Granular permission system
- **Audit Trail:** Action logging

### Data Protection
- **Input Validation:** Server-side validation
- **SQL Injection Protection:** Eloquent ORM
- **XSS Prevention:** Blade template escaping
- **File Upload Security:** Secure file handling

---

## 📱 Mobile Responsiveness

### Breakpoints
- **Mobile:** < 768px
- **Tablet:** 768px - 1024px
- **Desktop:** > 1024px

### Features
- **Touch-Friendly:** Large touch targets
- **Responsive Tables:** Horizontal scrolling
- **Mobile Navigation:** Collapsible sidebar
- **Optimized Forms:** Mobile-friendly inputs

---

## 🚀 Performance Optimizations

### Frontend
- **CDN Resources:** Bootstrap and Font Awesome from CDN
- **Minified CSS/JS:** Optimized file sizes
- **Lazy Loading:** Images and heavy content
- **Caching:** Browser caching strategies

### Backend
- **Database Queries:** Optimized Eloquent queries
- **Eager Loading:** Relationship preloading
- **Caching:** Redis/Memcached integration
- **Pagination:** Efficient data loading

---

## 🧪 Testing

### Demo Accounts
- **HR:** gokul.kumar@company.com (password123)
- **Manager:** vardhan.kumar@company.com (password123)
- **Employee:** gokul.kumar.dev@company.com (password123)

### Test Scenarios
- **Role Access:** Test different user roles
- **CRUD Operations:** Create, read, update, delete
- **Form Validation:** Test input validation
- **Error Handling:** Test error scenarios

---

## 📊 Analytics & Reporting

### Dashboard Analytics
- **Employee Statistics:** Count, distribution, trends
- **Department Metrics:** Performance, headcount
- **Payroll Analytics:** Total, averages, trends
- **Offer Letter Tracking:** Status, conversion rates

### Export Features
- **CSV Export:** Data export capabilities
- **PDF Reports:** Printable reports
- **Email Reports:** Scheduled reporting
- **API Integration:** Third-party analytics

---

## 🔧 Technical Stack

### Frontend
- **Framework:** Laravel Blade
- **CSS Framework:** Bootstrap 5
- **Icons:** Font Awesome 6
- **JavaScript:** Vanilla JS + jQuery

### Backend
- **Framework:** Laravel 10
- **Database:** MySQL/PostgreSQL
- **Authentication:** Laravel Sanctum
- **Validation:** Laravel Form Requests

### Deployment
- **Platform:** Railway
- **Environment:** Production-ready
- **SSL:** HTTPS enabled
- **CDN:** Global content delivery

---

## 📝 Notes

1. **Base URL:** All web routes are relative to the base URL
2. **Authentication:** Session-based authentication required
3. **Role Access:** Routes are protected by role middleware
4. **Responsive:** All pages are mobile-responsive
5. **Progressive Enhancement:** Core functionality works without JavaScript
6. **Accessibility:** WCAG 2.1 compliant design
7. **SEO:** Meta tags and structured data
8. **Performance:** Optimized for fast loading

---

## 🚀 Quick Start

1. **Visit the site:** Navigate to the base URL
2. **Login:** Use demo account credentials
3. **Explore:** Navigate through different sections
4. **Test:** Try different user roles
5. **Customize:** Modify views and controllers as needed

**Total Web Routes: 44+**
- Public: 3
- Protected: 41+
- Role-based: 4 
