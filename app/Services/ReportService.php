<?php

namespace App\Services;

use App\Models\Leave;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportService
{
    /**
     * Generate PDF leave report
     */
    public function generateLeaveReport($leaves, $filters = [])
    {
        $dompdf = new Dompdf();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf->setOptions($options);

        $html = $this->generateLeaveReportHtml($leaves, $filters);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Generate PDF payroll report
     */
    public function generatePayrollReport($payrolls, $filters = [])
    {
        $dompdf = new Dompdf();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf->setOptions($options);

        $html = $this->generatePayrollReportHtml($payrolls, $filters);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Generate PDF employee report
     */
    public function generateEmployeeReport($employees, $filters = [])
    {
        $dompdf = new Dompdf();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf->setOptions($options);

        $html = $this->generateEmployeeReportHtml($employees, $filters);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Generate HTML for leave report
     */
    private function generateLeaveReportHtml($leaves, $filters)
    {
        $totalLeaves = $leaves->count();
        $pendingLeaves = $leaves->where('status', 'pending')->count();
        $approvedLeaves = $leaves->whereIn('status', ['manager_approved', 'hr_approved'])->count();
        $rejectedLeaves = $leaves->whereIn('status', ['manager_rejected', 'hr_rejected'])->count();

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Leave Report</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    color: #333;
                }
                .header {
                    text-align: center;
                    border-bottom: 2px solid #333;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                .company-name {
                    font-size: 24px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                .report-title {
                    font-size: 18px;
                    color: #666;
                }
                .summary {
                    margin-bottom: 30px;
                }
                .summary-grid {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }
                .summary-item {
                    text-align: center;
                    flex: 1;
                    margin: 0 10px;
                    padding: 15px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
                .summary-number {
                    font-size: 24px;
                    font-weight: bold;
                    color: #2c5aa0;
                }
                .summary-label {
                    font-size: 12px;
                    color: #666;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                    font-size: 10px;
                }
                th {
                    background-color: #f5f5f5;
                    font-weight: bold;
                }
                .status-pending { color: #f39c12; }
                .status-approved { color: #27ae60; }
                .status-rejected { color: #e74c3c; }
                .footer {
                    margin-top: 40px;
                    text-align: center;
                    font-size: 10px;
                    color: #666;
                    border-top: 1px solid #ddd;
                    padding-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="company-name">HRMS Company</div>
                <div class="report-title">LEAVE REPORT</div>
                <div>Generated on: ' . now()->format('d/m/Y H:i:s') . '</div>
            </div>

            <div class="summary">
                <h3>Summary</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-number">' . $totalLeaves . '</div>
                        <div class="summary-label">Total Leaves</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">' . $pendingLeaves . '</div>
                        <div class="summary-label">Pending</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">' . $approvedLeaves . '</div>
                        <div class="summary-label">Approved</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">' . $rejectedLeaves . '</div>
                        <div class="summary-label">Rejected</div>
                    </div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Applied Date</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($leaves as $leave) {
            $statusClass = match($leave->status) {
                'pending' => 'status-pending',
                'manager_approved', 'hr_approved' => 'status-approved',
                'manager_rejected', 'hr_rejected' => 'status-rejected',
                default => ''
            };

            $html .= '
                <tr>
                    <td>' . $leave->employee->first_name . ' ' . $leave->employee->last_name . '</td>
                    <td>' . ($leave->employee->department->name ?? 'N/A') . '</td>
                    <td>' . $leave->leave_type_label . '</td>
                    <td>' . $leave->start_date->format('d/m/Y') . '</td>
                    <td>' . $leave->end_date->format('d/m/Y') . '</td>
                    <td>' . $leave->total_days . '</td>
                    <td class="' . $statusClass . '">' . $leave->status_label . '</td>
                    <td>' . $leave->created_at->format('d/m/Y') . '</td>
                </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <div class="footer">
                <p>This is a computer generated report. No signature is required.</p>
                <p>Generated on: ' . now()->format('d/m/Y H:i:s') . '</p>
            </div>
        </body>
        </html>';

        return $html;
    }

    /**
     * Generate HTML for payroll report
     */
    private function generatePayrollReportHtml($payrolls, $filters)
    {
        $totalPayrolls = $payrolls->count();
        $paidPayrolls = $payrolls->where('payment_status', 'paid')->count();
        $pendingPayrolls = $payrolls->where('payment_status', 'pending')->count();
        $totalAmount = $payrolls->sum('net_salary');

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Payroll Report</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    color: #333;
                }
                .header {
                    text-align: center;
                    border-bottom: 2px solid #333;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                .company-name {
                    font-size: 24px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                .report-title {
                    font-size: 18px;
                    color: #666;
                }
                .summary {
                    margin-bottom: 30px;
                }
                .summary-grid {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }
                .summary-item {
                    text-align: center;
                    flex: 1;
                    margin: 0 10px;
                    padding: 15px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
                .summary-number {
                    font-size: 24px;
                    font-weight: bold;
                    color: #2c5aa0;
                }
                .summary-label {
                    font-size: 12px;
                    color: #666;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                    font-size: 9px;
                }
                th {
                    background-color: #f5f5f5;
                    font-weight: bold;
                }
                .status-paid { color: #27ae60; }
                .status-pending { color: #f39c12; }
                .status-failed { color: #e74c3c; }
                .footer {
                    margin-top: 40px;
                    text-align: center;
                    font-size: 10px;
                    color: #666;
                    border-top: 1px solid #ddd;
                    padding-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="company-name">HRMS Company</div>
                <div class="report-title">PAYROLL REPORT</div>
                <div>Generated on: ' . now()->format('d/m/Y H:i:s') . '</div>
            </div>

            <div class="summary">
                <h3>Summary</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-number">' . $totalPayrolls . '</div>
                        <div class="summary-label">Total Payrolls</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">' . $paidPayrolls . '</div>
                        <div class="summary-label">Paid</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">' . $pendingPayrolls . '</div>
                        <div class="summary-label">Pending</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">$' . number_format($totalAmount, 2) . '</div>
                        <div class="summary-label">Total Amount</div>
                    </div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Payroll ID</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Period</th>
                        <th>Basic Salary</th>
                        <th>Allowances</th>
                        <th>Bonus</th>
                        <th>Gross Salary</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($payrolls as $payroll) {
            $statusClass = match($payroll->payment_status) {
                'paid' => 'status-paid',
                'pending' => 'status-pending',
                'failed' => 'status-failed',
                default => ''
            };

            $html .= '
                <tr>
                    <td>' . $payroll->payroll_id . '</td>
                    <td>' . $payroll->employee->first_name . ' ' . $payroll->employee->last_name . '</td>
                    <td>' . ($payroll->employee->department->name ?? 'N/A') . '</td>
                    <td>' . ucfirst($payroll->month) . ' ' . $payroll->year . '</td>
                    <td>$' . number_format($payroll->basic_salary, 2) . '</td>
                    <td>$' . number_format($payroll->allowances, 2) . '</td>
                    <td>$' . number_format($payroll->bonus, 2) . '</td>
                    <td>$' . number_format($payroll->gross_salary, 2) . '</td>
                    <td>$' . number_format($payroll->total_deductions, 2) . '</td>
                    <td>$' . number_format($payroll->net_salary, 2) . '</td>
                    <td class="' . $statusClass . '">' . ucfirst($payroll->payment_status) . '</td>
                </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <div class="footer">
                <p>This is a computer generated report. No signature is required.</p>
                <p>Generated on: ' . now()->format('d/m/Y H:i:s') . '</p>
            </div>
        </body>
        </html>';

        return $html;
    }

    /**
     * Generate HTML for employee report
     */
    private function generateEmployeeReportHtml($employees, $filters)
    {
        $totalEmployees = $employees->count();
        $activeEmployees = $employees->where('employment_status', 'active')->count();
        $inactiveEmployees = $employees->where('employment_status', 'inactive')->count();

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Employee Report</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    color: #333;
                }
                .header {
                    text-align: center;
                    border-bottom: 2px solid #333;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                .company-name {
                    font-size: 24px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                .report-title {
                    font-size: 18px;
                    color: #666;
                }
                .summary {
                    margin-bottom: 30px;
                }
                .summary-grid {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }
                .summary-item {
                    text-align: center;
                    flex: 1;
                    margin: 0 10px;
                    padding: 15px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
                .summary-number {
                    font-size: 24px;
                    font-weight: bold;
                    color: #2c5aa0;
                }
                .summary-label {
                    font-size: 12px;
                    color: #666;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                    font-size: 9px;
                }
                th {
                    background-color: #f5f5f5;
                    font-weight: bold;
                }
                .status-active { color: #27ae60; }
                .status-inactive { color: #e74c3c; }
                .footer {
                    margin-top: 40px;
                    text-align: center;
                    font-size: 10px;
                    color: #666;
                    border-top: 1px solid #ddd;
                    padding-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="company-name">HRMS Company</div>
                <div class="report-title">EMPLOYEE REPORT</div>
                <div>Generated on: ' . now()->format('d/m/Y H:i:s') . '</div>
            </div>

            <div class="summary">
                <h3>Summary</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-number">' . $totalEmployees . '</div>
                        <div class="summary-label">Total Employees</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">' . $activeEmployees . '</div>
                        <div class="summary-label">Active</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">' . $inactiveEmployees . '</div>
                        <div class="summary-label">Inactive</div>
                    </div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Hire Date</th>
                        <th>Salary</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($employees as $employee) {
            $statusClass = $employee->employment_status === 'active' ? 'status-active' : 'status-inactive';

            $html .= '
                <tr>
                    <td>' . $employee->employee_id . '</td>
                    <td>' . $employee->first_name . ' ' . $employee->last_name . '</td>
                    <td>' . ($employee->user->email ?? 'N/A') . '</td>
                    <td>' . ($employee->department->name ?? 'N/A') . '</td>
                    <td>' . $employee->position . '</td>
                    <td class="' . $statusClass . '">' . ucfirst($employee->employment_status) . '</td>
                    <td>' . ($employee->hire_date ? $employee->hire_date->format('d/m/Y') : 'N/A') . '</td>
                    <td>$' . number_format($employee->salary, 2) . '</td>
                </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <div class="footer">
                <p>This is a computer generated report. No signature is required.</p>
                <p>Generated on: ' . now()->format('d/m/Y H:i:s') . '</p>
            </div>
        </body>
        </html>';

        return $html;
    }
}
