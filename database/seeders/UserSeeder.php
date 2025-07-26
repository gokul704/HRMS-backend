<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get department IDs
        $hrDept = Department::where('name', 'Human Resources')->first();
        $itDept = Department::where('name', 'Information Technology')->first();
        $financeDept = Department::where('name', 'Finance')->first();
        $marketingDept = Department::where('name', 'Marketing')->first();
        $salesDept = Department::where('name', 'Sales')->first();
        $operationsDept = Department::where('name', 'Operations')->first();
        $supportDept = Department::where('name', 'Customer Support')->first();

        // ============================================================================
        // HR USERS (Role: hr)
        // ============================================================================

        // HR Director - Gokul
        $hrDirector = User::firstOrCreate(
            ['email' => 'gokul.kumar@company.com'],
            [
                'name' => 'Gokul Kumar',
                'password' => Hash::make('password123'),
                'role' => 'hr',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $hrDirector->id],
            [
                'department_id' => $hrDept->id,
                'employee_id' => 'EMP001',
                'first_name' => 'Gokul',
                'last_name' => 'Kumar',
                'phone' => '+1234567890',
                'date_of_birth' => '1985-03-15',
                'gender' => 'male',
                'address' => '123 Main St, City, State 12345',
                'emergency_contact_name' => 'John Kumar',
                'emergency_contact_phone' => '+1234567891',
                'position' => 'HR Director',
                'hire_date' => '2020-01-15',
                'salary' => 85000.00,
                'employment_status' => 'active',
                'is_onboarded' => true,
            ]
        );

        // HR Specialist - Vardhan
        $hrSpecialist = User::firstOrCreate(
            ['email' => 'vardhan.sharma@company.com'],
            [
                'name' => 'Vardhan Sharma',
                'password' => Hash::make('password123'),
                'role' => 'hr',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $hrSpecialist->id],
            [
                'department_id' => $hrDept->id,
                'employee_id' => 'EMP002',
                'first_name' => 'Vardhan',
                'last_name' => 'Sharma',
                'phone' => '+1234567892',
                'date_of_birth' => '1990-09-25',
                'gender' => 'male',
                'address' => '456 HR Lane, City, State 12345',
                'emergency_contact_name' => 'Robert Sharma',
                'emergency_contact_phone' => '+1234567893',
                'position' => 'HR Specialist',
                'hire_date' => '2022-01-10',
                'salary' => 55000.00,
                'employment_status' => 'active',
                'is_onboarded' => true,
            ]
        );

        // HR Assistant - Gokul
        $hrAssistant = User::firstOrCreate(
            ['email' => 'gokul.patel@company.com'],
            [
                'name' => 'Gokul Patel',
                'password' => Hash::make('password123'),
                'role' => 'hr',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $hrAssistant->id],
            [
                'department_id' => $hrDept->id,
                'employee_id' => 'EMP003',
                'first_name' => 'Gokul',
                'last_name' => 'Patel',
                'phone' => '+1234567894',
                'date_of_birth' => '1992-07-12',
                'gender' => 'male',
                'address' => '789 HR Ave, City, State 12345',
                'emergency_contact_name' => 'Lisa Patel',
                'emergency_contact_phone' => '+1234567895',
                'position' => 'HR Assistant',
                'hire_date' => '2023-03-20',
                'salary' => 45000.00,
                'employment_status' => 'active',
                'is_onboarded' => true,
            ]
        );

        // ============================================================================
        // MANAGER USERS (Role: manager)
        // ============================================================================

        // IT Manager - Vardhan
        $itManager = User::firstOrCreate(
            ['email' => 'vardhan.kumar@company.com'],
            [
                'name' => 'Vardhan Kumar',
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $itManager->id],
            [
                'department_id' => $itDept->id,
                'employee_id' => 'EMP004',
                'first_name' => 'Vardhan',
                'last_name' => 'Kumar',
                'phone' => '+1234567896',
                'date_of_birth' => '1988-07-22',
                'gender' => 'male',
                'address' => '456 Tech Ave, City, State 12345',
                'emergency_contact_name' => 'Lisa Kumar',
                'emergency_contact_phone' => '+1234567897',
                'position' => 'IT Manager',
                'hire_date' => '2019-06-10',
                'salary' => 85000.00,
                'employment_status' => 'active',
                'is_onboarded' => true,
            ]
        );

        // Finance Manager - Gokul
        $financeManager = User::firstOrCreate(
            ['email' => 'gokul.reddy@company.com'],
            [
                'name' => 'Gokul Reddy',
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $financeManager->id],
            [
                'department_id' => $financeDept->id,
                'employee_id' => 'EMP005',
                'first_name' => 'Gokul',
                'last_name' => 'Reddy',
                'phone' => '+1234567898',
                'date_of_birth' => '1983-11-08',
                'gender' => 'male',
                'address' => '789 Finance Blvd, City, State 12345',
                'emergency_contact_name' => 'Carlos Reddy',
                'emergency_contact_phone' => '+1234567899',
                'position' => 'Finance Manager',
                'hire_date' => '2018-09-20',
                'salary' => 80000.00,
                'employment_status' => 'active',
                'is_onboarded' => true,
            ]
        );

        // Marketing Manager - Vardhan
        $marketingManager = User::firstOrCreate(
            ['email' => 'vardhan.sharma.marketing@company.com'],
            [
                'name' => 'Vardhan Sharma',
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $marketingManager->id],
            [
                'department_id' => $marketingDept->id,
                'employee_id' => 'EMP006',
                'first_name' => 'Vardhan',
                'last_name' => 'Sharma',
                'phone' => '+1234567900',
                'date_of_birth' => '1987-04-15',
                'gender' => 'male',
                'address' => '321 Marketing St, City, State 12345',
                'emergency_contact_name' => 'Tom Sharma',
                'emergency_contact_phone' => '+1234567901',
                'position' => 'Marketing Manager',
                'hire_date' => '2021-02-15',
                'salary' => 75000.00,
                'employment_status' => 'active',
                'is_onboarded' => true,
            ]
        );

        // Sales Manager - Gokul
        $salesManager = User::firstOrCreate(
            ['email' => 'gokul.patel.sales@company.com'],
            [
                'name' => 'Gokul Patel',
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $salesManager->id],
            [
                'department_id' => $salesDept->id,
                'employee_id' => 'EMP007',
                'first_name' => 'Gokul',
                'last_name' => 'Patel',
                'phone' => '+1234567902',
                'date_of_birth' => '1985-12-03',
                'gender' => 'male',
                'address' => '654 Sales Rd, City, State 12345',
                'emergency_contact_name' => 'Mary Patel',
                'emergency_contact_phone' => '+1234567903',
                'position' => 'Sales Manager',
                'hire_date' => '2020-08-10',
                'salary' => 70000.00,
                'employment_status' => 'active',
                'is_onboarded' => true,
            ]
        );

        // Operations Manager - Vardhan
        $operationsManager = User::firstOrCreate(
            ['email' => 'vardhan.reddy@company.com'],
            [
                'name' => 'Vardhan Reddy',
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $operationsManager->id],
            [
                'department_id' => $operationsDept->id,
                'employee_id' => 'EMP008',
                'first_name' => 'Vardhan',
                'last_name' => 'Reddy',
                'phone' => '+1234567904',
                'date_of_birth' => '1986-09-18',
                'gender' => 'male',
                'address' => '987 Operations Blvd, City, State 12345',
                'emergency_contact_name' => 'Sarah Reddy',
                'emergency_contact_phone' => '+1234567905',
                'position' => 'Operations Manager',
                'hire_date' => '2020-03-15',
                'salary' => 72000.00,
                'employment_status' => 'active',
                'is_onboarded' => true,
            ]
        );

        // ============================================================================
        // EMPLOYEE USERS (Role: employee)
        // ============================================================================

        // Software Developer - Gokul
        $softwareDeveloper = User::firstOrCreate(
            ['email' => 'gokul.kumar.dev@company.com'],
            [
                'name' => 'Gokul Kumar',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $softwareDeveloper->id],
            [
                'department_id' => $itDept->id,
                'employee_id' => 'EMP009',
                'first_name' => 'Gokul',
                'last_name' => 'Kumar',
                'phone' => '+1234567906',
                'date_of_birth' => '1992-04-12',
                'gender' => 'male',
                'address' => '321 Developer St, City, State 12345',
                'emergency_contact_name' => 'Mary Kumar',
                'emergency_contact_phone' => '+1234567907',
                'position' => 'Software Developer',
                'hire_date' => '2021-03-01',
                'salary' => 65000.00,
                'employment_status' => 'active',
                'is_onboarded' => true,
            ]
        );

        // QA Tester - Vardhan
        $qaTester = User::firstOrCreate(
            ['email' => 'vardhan.sharma.qa@company.com'],
            [
                'name' => 'Vardhan Sharma',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $qaTester->id],
            [
                'department_id' => $itDept->id,
                'employee_id' => 'EMP010',
                'first_name' => 'Vardhan',
                'last_name' => 'Sharma',
                'phone' => '+1234567908',
                'date_of_birth' => '1994-08-20',
                'gender' => 'male',
                'address' => '654 Tester Ave, City, State 12345',
                'emergency_contact_name' => 'James Sharma',
                'emergency_contact_phone' => '+1234567909',
                'position' => 'QA Tester',
                'hire_date' => '2022-06-15',
                'salary' => 55000.00,
                'employment_status' => 'active',
                'is_onboarded' => true,
            ]
        );

        // Accountant - Gokul
        $accountant = User::firstOrCreate(
            ['email' => 'gokul.patel.finance@company.com'],
            [
                'name' => 'Gokul Patel',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $accountant->id],
            [
                'department_id' => $financeDept->id,
                'employee_id' => 'EMP011',
                'first_name' => 'Gokul',
                'last_name' => 'Patel',
                'phone' => '+1234567910',
                'date_of_birth' => '1991-01-30',
                'gender' => 'male',
                'address' => '987 Finance Way, City, State 12345',
                'emergency_contact_name' => 'Maria Patel',
                'emergency_contact_phone' => '+1234567911',
                'position' => 'Accountant',
                'hire_date' => '2021-09-05',
                'salary' => 60000.00,
                'employment_status' => 'active',
                'is_onboarded' => true,
            ]
        );

        // Marketing Specialist - Vardhan
        $marketingSpecialist = User::firstOrCreate(
            ['email' => 'vardhan.kumar.marketing@company.com'],
            [
                'name' => 'Vardhan Kumar',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $marketingSpecialist->id],
            [
                'department_id' => $marketingDept->id,
                'employee_id' => 'EMP012',
                'first_name' => 'Vardhan',
                'last_name' => 'Kumar',
                'phone' => '+1234567912',
                'date_of_birth' => '1993-05-18',
                'gender' => 'male',
                'address' => '147 Marketing Dr, City, State 12345',
                'emergency_contact_name' => 'John Kumar',
                'emergency_contact_phone' => '+1234567913',
                'position' => 'Marketing Specialist',
                'hire_date' => '2022-04-12',
                'salary' => 52000.00,
                'employment_status' => 'active',
                'is_onboarded' => true,
            ]
        );

        // Sales Representative - Gokul
        $salesRep = User::firstOrCreate(
            ['email' => 'gokul.reddy.sales@company.com'],
            [
                'name' => 'Gokul Reddy',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $salesRep->id],
            [
                'department_id' => $salesDept->id,
                'employee_id' => 'EMP013',
                'first_name' => 'Gokul',
                'last_name' => 'Reddy',
                'phone' => '+1234567914',
                'date_of_birth' => '1989-11-25',
                'gender' => 'male',
                'address' => '258 Sales Blvd, City, State 12345',
                'emergency_contact_name' => 'Sarah Reddy',
                'emergency_contact_phone' => '+1234567915',
                'position' => 'Sales Representative',
                'hire_date' => '2021-07-20',
                'salary' => 48000.00,
                'employment_status' => 'active',
                'is_onboarded' => true,
            ]
        );

        // Operations Coordinator - Vardhan
        $opsCoordinator = User::firstOrCreate(
            ['email' => 'vardhan.sharma.ops@company.com'],
            [
                'name' => 'Vardhan Sharma',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $opsCoordinator->id],
            [
                'department_id' => $operationsDept->id,
                'employee_id' => 'EMP014',
                'first_name' => 'Vardhan',
                'last_name' => 'Sharma',
                'phone' => '+1234567916',
                'date_of_birth' => '1990-03-10',
                'gender' => 'male',
                'address' => '369 Operations St, City, State 12345',
                'emergency_contact_name' => 'Carlos Sharma',
                'emergency_contact_phone' => '+1234567917',
                'position' => 'Operations Coordinator',
                'hire_date' => '2022-01-30',
                'salary' => 50000.00,
                'employment_status' => 'active',
                'is_onboarded' => true,
            ]
        );

        // Customer Support Specialist - Gokul
        $supportSpecialist = User::firstOrCreate(
            ['email' => 'gokul.kumar.support@company.com'],
            [
                'name' => 'Gokul Kumar',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $supportSpecialist->id],
            [
                'department_id' => $supportDept->id,
                'employee_id' => 'EMP015',
                'first_name' => 'Gokul',
                'last_name' => 'Kumar',
                'phone' => '+1234567918',
                'date_of_birth' => '1995-12-05',
                'gender' => 'male',
                'address' => '741 Support Ave, City, State 12345',
                'emergency_contact_name' => 'Lisa Kumar',
                'emergency_contact_phone' => '+1234567919',
                'position' => 'Customer Support Specialist',
                'hire_date' => '2023-02-15',
                'salary' => 42000.00,
                'employment_status' => 'active',
                'is_onboarded' => true,
            ]
        );

        // ============================================================================
        // SPECIAL STATUS USERS
        // ============================================================================

        // Inactive Employee - Vardhan
        $inactiveEmployee = User::firstOrCreate(
            ['email' => 'vardhan.patel.inactive@company.com'],
            [
                'name' => 'Vardhan Patel',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'is_active' => false,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $inactiveEmployee->id],
            [
                'department_id' => $itDept->id,
                'employee_id' => 'EMP016',
                'first_name' => 'Vardhan',
                'last_name' => 'Patel',
                'phone' => '+1234567920',
                'date_of_birth' => '1988-06-14',
                'gender' => 'male',
                'address' => '852 Inactive St, City, State 12345',
                'emergency_contact_name' => 'Patricia Patel',
                'emergency_contact_phone' => '+1234567921',
                'position' => 'System Administrator',
                'hire_date' => '2020-05-10',
                'salary' => 70000.00,
                'employment_status' => 'inactive',
                'is_onboarded' => true,
            ]
        );

        // Terminated Employee - Gokul
        $terminatedEmployee = User::firstOrCreate(
            ['email' => 'gokul.sharma.terminated@company.com'],
            [
                'name' => 'Gokul Sharma',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'is_active' => false,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $terminatedEmployee->id],
            [
                'department_id' => $marketingDept->id,
                'employee_id' => 'EMP017',
                'first_name' => 'Gokul',
                'last_name' => 'Sharma',
                'phone' => '+1234567922',
                'date_of_birth' => '1992-09-22',
                'gender' => 'male',
                'address' => '963 Terminated Rd, City, State 12345',
                'emergency_contact_name' => 'Michael Sharma',
                'emergency_contact_phone' => '+1234567923',
                'position' => 'Content Writer',
                'hire_date' => '2021-11-15',
                'salary' => 45000.00,
                'employment_status' => 'terminated',
                'termination_date' => '2024-01-15',
                'termination_reason' => 'Position eliminated',
                'is_onboarded' => true,
            ]
        );

        // Pending Onboarding - Vardhan
        $pendingEmployee = User::firstOrCreate(
            ['email' => 'vardhan.reddy.pending@company.com'],
            [
                'name' => 'Vardhan Reddy',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'is_active' => true,
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $pendingEmployee->id],
            [
                'department_id' => $salesDept->id,
                'employee_id' => 'EMP018',
                'first_name' => 'Vardhan',
                'last_name' => 'Reddy',
                'phone' => '+1234567924',
                'date_of_birth' => '1994-02-28',
                'gender' => 'male',
                'address' => '159 Pending Ave, City, State 12345',
                'emergency_contact_name' => 'Jennifer Reddy',
                'emergency_contact_phone' => '+1234567925',
                'position' => 'Junior Sales Representative',
                'hire_date' => '2024-01-20',
                'salary' => 40000.00,
                'employment_status' => 'active',
                'is_onboarded' => false,
            ]
        );

        $this->command->info('Users and employees seeded successfully!');
    }
}
