<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Leave;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $users = User::whereIn('role', ['hr', 'manager'])->get();

        if ($employees->count() === 0) {
            $this->command->info('No employees found. Skipping leave seeding.');
            return;
        }

        $leaveTypes = ['annual', 'sick', 'casual', 'maternity', 'paternity', 'other'];
        $statuses = ['pending', 'manager_approved', 'manager_rejected', 'hr_approved', 'hr_rejected'];

        foreach ($employees as $employee) {
            // Create 2-5 leaves per employee
            $numLeaves = rand(2, 5);

            for ($i = 0; $i < $numLeaves; $i++) {
                $startDate = Carbon::now()->subDays(rand(1, 90));
                $endDate = $startDate->copy()->addDays(rand(1, 14));
                $totalDays = $startDate->diffInDays($endDate) + 1;

                $status = $statuses[array_rand($statuses)];
                $appliedBy = $users->random();
                $managerId = null;
                $managerApprovedAt = null;
                $hrApprovedAt = null;
                $approvedBy = null;
                $approvedAt = null;

                if (in_array($status, ['manager_approved', 'manager_rejected'])) {
                    $managerId = $users->where('role', 'manager')->first();
                    $managerApprovedAt = $startDate->copy()->subDays(rand(1, 7));
                } elseif (in_array($status, ['hr_approved', 'hr_rejected'])) {
                    $approvedBy = $users->where('role', 'hr')->first();
                    $approvedAt = $startDate->copy()->subDays(rand(1, 7));
                    $hrApprovedAt = $approvedAt;
                }

                Leave::create([
                    'employee_id' => $employee->id,
                    'leave_type' => $leaveTypes[array_rand($leaveTypes)],
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'total_days' => $totalDays,
                    'reason' => $this->getRandomReason(),
                    'status' => $status,
                    'manager_id' => $managerId ? $managerId->id : null,
                    'manager_approved_at' => $managerApprovedAt,
                    'manager_remarks' => $status === 'manager_rejected' ? $this->getRandomRejectionReason() : null,
                    'hr_approved_at' => $hrApprovedAt,
                    'hr_remarks' => $status === 'hr_rejected' ? $this->getRandomRejectionReason() : null,
                    'applied_by' => $appliedBy->id,
                    'approved_by' => $approvedBy ? $approvedBy->id : null,
                    'approved_at' => $approvedAt,
                    'rejection_reason' => in_array($status, ['manager_rejected', 'hr_rejected']) ? $this->getRandomRejectionReason() : null,
                ]);
            }
        }

        $this->command->info('Leave data seeded successfully!');
    }

    private function getRandomReason(): string
    {
        $reasons = [
            'Annual vacation with family',
            'Medical appointment',
            'Personal emergency',
            'Family function',
            'Health checkup',
            'Wedding ceremony',
            'Religious ceremony',
            'Mental health day',
            'Family vacation',
            'Medical treatment',
            'Personal development',
            'Family emergency',
            'Holiday celebration',
            'Medical procedure',
            'Personal time off'
        ];

        return $reasons[array_rand($reasons)];
    }

    private function getRandomRejectionReason(): string
    {
        $reasons = [
            'Insufficient notice period',
            'High workload during requested period',
            'Insufficient leave balance',
            'Critical project deadline',
            'Team member already on leave',
            'Requested dates not available',
            'Insufficient documentation provided',
            'Business critical period',
            'Leave policy violation',
            'Emergency work requirement'
        ];

        return $reasons[array_rand($reasons)];
    }
}
