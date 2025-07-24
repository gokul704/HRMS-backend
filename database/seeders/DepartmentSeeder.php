<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Human Resources',
                'description' => 'Manages employee relations, recruitment, and HR policies',
                'location' => 'Main Office',
                'is_active' => true,
            ],
            [
                'name' => 'Information Technology',
                'description' => 'Handles software development, IT infrastructure, and technical support',
                'location' => 'Tech Building',
                'is_active' => true,
            ],
            [
                'name' => 'Finance',
                'description' => 'Manages financial operations, accounting, and budgeting',
                'location' => 'Finance Wing',
                'is_active' => true,
            ],
            [
                'name' => 'Marketing',
                'description' => 'Handles marketing campaigns, branding, and customer acquisition',
                'location' => 'Marketing Suite',
                'is_active' => true,
            ],
            [
                'name' => 'Sales',
                'description' => 'Manages sales operations, client relationships, and revenue generation',
                'location' => 'Sales Floor',
                'is_active' => true,
            ],
            [
                'name' => 'Operations',
                'description' => 'Manages day-to-day operations and process optimization',
                'location' => 'Operations Center',
                'is_active' => true,
            ],
            [
                'name' => 'Customer Support',
                'description' => 'Provides customer service and technical support',
                'location' => 'Support Center',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
