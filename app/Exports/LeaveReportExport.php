<?php

namespace App\Exports;

use App\Models\Leave;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LeaveReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $leaves;

    public function __construct($leaves)
    {
        $this->leaves = $leaves;
    }

    public function collection()
    {
        return $this->leaves;
    }

    public function headings(): array
    {
        return [
            'Employee ID',
            'Employee Name',
            'Department',
            'Leave Type',
            'Start Date',
            'End Date',
            'Total Days',
            'Reason',
            'Status',
            'Applied By',
            'Approved By',
            'Applied Date',
            'Approved Date'
        ];
    }

    public function map($leave): array
    {
        return [
            $leave->employee->employee_id ?? 'N/A',
            $leave->employee->first_name . ' ' . $leave->employee->last_name,
            $leave->employee->department->name ?? 'N/A',
            $leave->leave_type_label,
            $leave->start_date->format('Y-m-d'),
            $leave->end_date->format('Y-m-d'),
            $leave->total_days,
            $leave->reason,
            $leave->status_label,
            $leave->appliedBy->name ?? 'N/A',
            $leave->approvedBy->name ?? 'N/A',
            $leave->created_at->format('Y-m-d H:i:s'),
            $leave->approved_at ? $leave->approved_at->format('Y-m-d H:i:s') : 'N/A'
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
            'B' => 25,
            'C' => 20,
            'D' => 15,
            'E' => 12,
            'F' => 12,
            'G' => 12,
            'H' => 40,
            'I' => 15,
            'J' => 20,
            'K' => 20,
            'L' => 20,
            'M' => 20,
        ];
    }
}
