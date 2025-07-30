<?php

namespace App\Exports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PayrollReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $payrolls;

    public function __construct($payrolls)
    {
        $this->payrolls = $payrolls;
    }

    public function collection()
    {
        return $this->payrolls;
    }

    public function headings(): array
    {
        return [
            'Payroll ID',
            'Employee ID',
            'Employee Name',
            'Department',
            'Month',
            'Year',
            'Basic Salary',
            'Allowances',
            'Bonus',
            'Overtime Pay',
            'Gross Salary',
            'Tax Deduction',
            'Insurance Deduction',
            'Other Deductions',
            'Net Salary',
            'Payment Status',
            'Payment Date',
            'Processed By',
            'Processed Date'
        ];
    }

    public function map($payroll): array
    {
        return [
            $payroll->payroll_id,
            $payroll->employee->employee_id ?? 'N/A',
            $payroll->employee->first_name . ' ' . $payroll->employee->last_name,
            $payroll->employee->department->name ?? 'N/A',
            $payroll->month,
            $payroll->year,
            number_format($payroll->basic_salary, 2),
            number_format($payroll->allowances, 2),
            number_format($payroll->bonus, 2),
            number_format($payroll->overtime_pay, 2),
            number_format($payroll->gross_salary, 2),
            number_format($payroll->tax_deduction, 2),
            number_format($payroll->insurance_deduction, 2),
            number_format($payroll->other_deductions, 2),
            number_format($payroll->net_salary, 2),
            ucfirst($payroll->payment_status),
            $payroll->payment_date ? $payroll->payment_date->format('Y-m-d') : 'N/A',
            $payroll->processedBy->name ?? 'N/A',
            $payroll->processed_at ? $payroll->processed_at->format('Y-m-d H:i:s') : 'N/A'
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
            'B' => 15,
            'C' => 25,
            'D' => 20,
            'E' => 12,
            'F' => 10,
            'G' => 15,
            'H' => 15,
            'I' => 12,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 18,
            'N' => 18,
            'O' => 15,
            'P' => 15,
            'Q' => 15,
            'R' => 20,
            'S' => 20,
        ];
    }
}
