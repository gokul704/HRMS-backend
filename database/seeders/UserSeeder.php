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
        // Create HR Manager
        $hrManager = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah.johnson@company.com',
            'password' => Hash::make('password123'),
            'role' => 'hr',
            'is_active' => true,
        ]);

        // Create HR Manager Employee Record
        Employee::create([
            'user_id' => $hrManager->id,
            'department_id' => Department::where('name', 'Human Resources')->first()->id,
            'employee_id' => 'EMP001',
            'first_name' => 'Sarah',
            'last_name' => 'Johnson',
            'phone' => '+1234567890',
            'date_of_birth' => '1985-03-15',
            'gender' => 'female',
            'address' => '123 Main St, City, State 12345',
            'emergency_contact_name' => 'John Johnson',
            'emergency_contact_phone' => '+1234567891',
            'position' => 'HR Manager',
            'hire_date' => '2020-01-15',
            'salary' => 75000.00,
            'employment_status' => 'active',
            'is_onboarded' => true,
        ]);

        // Create IT Manager
        $itManager = User::create([
            'name' => 'Michael Chen',
            'email' => 'michael.chen@company.com',
            'password' => Hash::make('password123'),
            'role' => 'manager',
            'is_active' => true,
        ]);

        // Create IT Manager Employee Record
        Employee::create([
            'user_id' => $itManager->id,
            'department_id' => Department::where('name', 'Information Technology')->first()->id,
            'employee_id' => 'EMP002',
            'first_name' => 'Michael',
            'last_name' => 'Chen',
            'phone' => '+1234567892',
            'date_of_birth' => '1988-07-22',
            'gender' => 'male',
            'address' => '456 Tech Ave, City, State 12345',
            'emergency_contact_name' => 'Lisa Chen',
            'emergency_contact_phone' => '+1234567893',
            'position' => 'IT Manager',
            'hire_date' => '2019-06-10',
            'salary' => 85000.00,
            'employment_status' => 'active',
            'is_onboarded' => true,
        ]);

        // Create Finance Manager
        $financeManager = User::create([
            'name' => 'Emily Rodriguez',
            'email' => 'emily.rodriguez@company.com',
            'password' => Hash::make('password123'),
            'role' => 'manager',
            'is_active' => true,
        ]);

        // Create Finance Manager Employee Record
        Employee::create([
            'user_id' => $financeManager->id,
            'department_id' => Department::where('name', 'Finance')->first()->id,
            'employee_id' => 'EMP003',
            'first_name' => 'Emily',
            'last_name' => 'Rodriguez',
            'phone' => '+1234567894',
            'date_of_birth' => '1983-11-08',
            'gender' => 'female',
            'address' => '789 Finance Blvd, City, State 12345',
            'emergency_contact_name' => 'Carlos Rodriguez',
            'emergency_contact_phone' => '+1234567895',
            'position' => 'Finance Manager',
            'hire_date' => '2018-09-20',
            'salary' => 80000.00,
            'employment_status' => 'active',
            'is_onboarded' => true,
        ]);

        // Create Regular Employee
        $employee = User::create([
            'name' => 'David Wilson',
            'email' => 'david.wilson@company.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'is_active' => true,
        ]);

        // Create Regular Employee Record
        Employee::create([
            'user_id' => $employee->id,
            'department_id' => Department::where('name', 'Information Technology')->first()->id,
            'employee_id' => 'EMP004',
            'first_name' => 'David',
            'last_name' => 'Wilson',
            'phone' => '+1234567896',
            'date_of_birth' => '1992-04-12',
            'gender' => 'male',
            'address' => '321 Developer St, City, State 12345',
            'emergency_contact_name' => 'Mary Wilson',
            'emergency_contact_phone' => '+1234567897',
            'position' => 'Software Developer',
            'hire_date' => '2021-03-01',
            'salary' => 65000.00,
            'employment_status' => 'active',
            'is_onboarded' => true,
        ]);

        // Create another HR Employee
        $hrEmployee = User::create([
            'name' => 'Jennifer Smith',
            'email' => 'jennifer.smith@company.com',
            'password' => Hash::make('password123'),
            'role' => 'hr',
            'is_active' => true,
        ]);

        // Create HR Employee Record
        Employee::create([
            'user_id' => $hrEmployee->id,
            'department_id' => Department::where('name', 'Human Resources')->first()->id,
            'employee_id' => 'EMP005',
            'first_name' => 'Jennifer',
            'last_name' => 'Smith',
            'phone' => '+1234567898',
            'date_of_birth' => '1990-09-25',
            'gender' => 'female',
            'address' => '654 HR Lane, City, State 12345',
            'emergency_contact_name' => 'Robert Smith',
            'emergency_contact_phone' => '+1234567899',
            'position' => 'HR Specialist',
            'hire_date' => '2022-01-10',
            'salary' => 55000.00,
            'employment_status' => 'active',
            'is_onboarded' => true,
        ]);
    }
}
