<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EmployeeReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $employees;

    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    public function collection()
    {
        return $this->employees;
    }

    public function headings(): array
    {
        return [
            'Employee ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Department',
            'Position',
            'Employment Status',
            'Hire Date',
            'Salary',
            'Address',
            'City',
            'State',
            'Postal Code',
            'Country',
            'Emergency Contact',
            'Emergency Phone',
            'Created Date'
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->employee_id,
            $employee->first_name,
            $employee->last_name,
            $employee->user->email ?? 'N/A',
            $employee->phone ?? 'N/A',
            $employee->department->name ?? 'N/A',
            $employee->position,
            ucfirst($employee->employment_status),
            $employee->hire_date ? $employee->hire_date->format('Y-m-d') : 'N/A',
            number_format($employee->salary, 2),
            $employee->address ?? 'N/A',
            $employee->city ?? 'N/A',
            $employee->state ?? 'N/A',
            $employee->postal_code ?? 'N/A',
            $employee->country ?? 'N/A',
            $employee->emergency_contact ?? 'N/A',
            $employee->emergency_phone ?? 'N/A',
            $employee->created_at->format('Y-m-d H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F81BD']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 20,
            'C' => 20,
            'D' => 30,
            'E' => 15,
            'F' => 20,
            'G' => 25,
            'H' => 18,
            'I' => 15,
            'J' => 15,
            'K' => 40,
            'L' => 15,
            'M' => 15,
            'N' => 15,
            'O' => 15,
            'P' => 25,
            'Q' => 20,
            'R' => 20,
        ];
    }
}
