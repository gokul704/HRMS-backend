# Manager Leave Features Added

## Overview
Successfully added "View Employee" and "View Leaves" functionality for managers in the leave reports page, along with proper approval/rejection capabilities.

## New Features Implemented

### 1. Enhanced Leave Report Actions for Managers
- **View Employee Button**: Managers can now click to view detailed employee information
- **View Employee Leaves Button**: Managers can view all leaves for a specific employee
- **Approve/Reject Buttons**: Managers can approve or reject pending leaves from their department

### 2. Role-Based Access Control
- **Manager Permissions**: Managers can only view and manage leaves from their department
- **Employee View Access**: Managers can only view employees from their department
- **Leave Approval**: Managers can approve/reject leaves with proper validation

### 3. Enhanced LeaveController
- **Employee Filtering**: Added `employee_id` filter support in leave index
- **Department Filtering**: Managers automatically see only their department's leaves
- **Proper Validation**: Ensures managers can only access authorized data

### 4. Enhanced EmployeeController
- **Role-Based Show Method**: Added proper access control for employee details
- **Manager Access**: Managers can view employees from their department only
- **Employee Access**: Employees can only view their own profile
- **HR Access**: HR can view all employees

## Technical Implementation

### 1. Updated Leave Report View
```php
// Added manager-specific buttons
@if(auth()->user()->isManager())
    <a href="{{ route('web.employees.show', $leave->employee->id) }}" 
       class="btn btn-sm btn-outline-info" title="View Employee">
        <i class="mdi mdi-account"></i>
    </a>
    <a href="{{ route('leaves.index', ['employee_id' => $leave->employee->id]) }}" 
       class="btn btn-sm btn-outline-warning" title="View Employee Leaves">
        <i class="mdi mdi-calendar-multiple"></i>
    </a>
@endif

// Added manager approval buttons
@if($leave->status === 'pending' && auth()->user()->isManager())
    <button type="button" class="btn btn-sm btn-outline-success"
            onclick="approveLeave({{ $leave->id }})" title="Approve Leave">
        <i class="mdi mdi-check"></i>
    </button>
    <button type="button" class="btn btn-sm btn-outline-danger"
            onclick="rejectLeave({{ $leave->id }})" title="Reject Leave">
        <i class="mdi mdi-close"></i>
    </button>
@endif
```

### 2. New Controller Methods
```php
// ReportsController - Approve Leave
public function approveLeave(Request $request, $leaveId)
{
    // Role-based validation
    // Department-based access control
    // Status validation
    // Database update
}

// ReportsController - Reject Leave  
public function rejectLeave(Request $request, $leaveId)
{
    // Role-based validation
    // Department-based access control
    // Status validation
    // Database update
}
```

### 3. Enhanced LeaveController
```php
// Added employee_id filtering
if ($request->filled('employee_id')) {
    $query->where('employee_id', $request->employee_id);
}
```

### 4. Enhanced EmployeeController
```php
// Added role-based access control
public function show(Employee $employee)
{
    $user = auth()->user();
    
    if ($user->isEmployee()) {
        // Employees can only view their own profile
        if ($user->employee->id !== $employee->id) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
    } elseif ($user->isManager()) {
        // Managers can only view employees from their department
        if ($user->employee->department_id !== $employee->department_id) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
    } elseif (!$user->isHr()) {
        // Only HR can view all employees
        return redirect()->route('dashboard')->with('error', 'Access denied.');
    }
    
    $employee->load(['user', 'department', 'payrolls']);
    return view('employees.show', compact('employee'));
}
```

## New Routes Added
```php
// Leave Approval Routes
Route::post('/reports/leave/{leave}/approve', [ReportsController::class, 'approveLeave'])->name('reports.leave.approve');
Route::post('/reports/leave/{leave}/reject', [ReportsController::class, 'rejectLeave'])->name('reports.leave.reject');
```

## JavaScript Functions
```javascript
function approveLeave(leaveId) {
    if (confirm('Are you sure you want to approve this leave application?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("reports.leave.approve", ":leaveId") }}'.replace(':leaveId', leaveId);
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectLeave(leaveId) {
    if (confirm('Are you sure you want to reject this leave application?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("reports.leave.reject", ":leaveId") }}'.replace(':leaveId', leaveId);
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
    }
}
```

## Key Benefits

### 1. Enhanced Manager Workflow
- **Quick Access**: Managers can quickly view employee details and leave history
- **Efficient Approval**: Direct approval/rejection from the reports page
- **Department Focus**: Managers only see relevant data from their department

### 2. Improved Security
- **Role-Based Access**: Proper access control for all user roles
- **Department Isolation**: Managers can only access their department's data
- **Validation**: Proper validation for all actions

### 3. Better User Experience
- **Visual Indicators**: Clear buttons with tooltips
- **Confirmation Dialogs**: Safe approval/rejection process
- **Seamless Navigation**: Easy access to related information

### 4. Data Integrity
- **Status Validation**: Prevents processing already processed leaves
- **Permission Checks**: Ensures proper authorization
- **Audit Trail**: Proper logging of all actions

## Usage Examples

### For Managers:
1. **View Leave Reports**: Navigate to Reports > Leave Reports
2. **View Employee**: Click the employee icon to see employee details
3. **View Employee Leaves**: Click the calendar icon to see all leaves for that employee
4. **Approve/Reject**: Click check/cross icons to approve or reject pending leaves

### Access Control:
- **Managers**: Can only view/manage their department's data
- **HR**: Can view/manage all data
- **Employees**: Can only view their own data

## Testing Checklist

### Manager Features:
- [ ] View Employee button works for managers
- [ ] View Employee Leaves button works for managers
- [ ] Approve Leave button works for managers
- [ ] Reject Leave button works for managers
- [ ] Access control prevents unauthorized access
- [ ] Department filtering works correctly

### Security Testing:
- [ ] Managers cannot access other departments
- [ ] Employees cannot access other employees
- [ ] Proper error messages for unauthorized access
- [ ] CSRF protection works correctly

### UI Testing:
- [ ] Buttons appear only for appropriate roles
- [ ] Tooltips display correctly
- [ ] Confirmation dialogs work
- [ ] Success/error messages display

## Conclusion

The manager leave features have been successfully implemented with proper role-based access control, enhanced UI, and secure functionality. Managers now have efficient tools to manage their team's leave requests while maintaining proper data isolation and security. 
