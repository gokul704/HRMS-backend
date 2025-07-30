<?php

namespace App\Services;

use App\Models\Payroll;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;

class PayslipService
{
    /**
     * Generate PDF payslip for a payroll
     */
    public function generatePayslip(Payroll $payroll): string
    {
        $dompdf = new Dompdf();

        // Configure DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf->setOptions($options);

        // Generate HTML content
        $html = $this->generatePayslipHtml($payroll);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Generate filename
        $filename = 'payslip_' . $payroll->payroll_id . '_' . $payroll->month . '_' . $payroll->year . '.pdf';

        // Save to storage
        $path = 'payslips/' . $filename;
        Storage::put('public/' . $path, $dompdf->output());

        // Update payroll record with file path
        $payroll->update(['payslip_file' => $path]);

        return $path;
    }

    /**
     * Generate HTML content for payslip
     */
    private function generatePayslipHtml(Payroll $payroll): string
    {
        $employee = $payroll->employee;
        $department = $employee->department;

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Payslip - ' . $payroll->payroll_id . '</title>
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
                .payslip-title {
                    font-size: 18px;
                    color: #666;
                }
                .employee-info {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 30px;
                }
                .info-section {
                    flex: 1;
                }
                .info-section h3 {
                    margin: 0 0 10px 0;
                    color: #333;
                    font-size: 14px;
                }
                .info-row {
                    margin-bottom: 5px;
                    font-size: 12px;
                }
                .label {
                    font-weight: bold;
                    color: #666;
                }
                .value {
                    color: #333;
                }
                .salary-details {
                    margin-bottom: 30px;
                }
                .salary-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                .salary-table th,
                .salary-table td {
                    border: 1px solid #ddd;
                    padding: 10px;
                    text-align: left;
                }
                .salary-table th {
                    background-color: #f5f5f5;
                    font-weight: bold;
                }
                .earnings-section,
                .deductions-section {
                    margin-bottom: 20px;
                }
                .section-title {
                    font-size: 16px;
                    font-weight: bold;
                    margin-bottom: 10px;
                    color: #333;
                }
                .total-row {
                    font-weight: bold;
                    background-color: #f9f9f9;
                }
                .net-salary {
                    font-size: 18px;
                    font-weight: bold;
                    color: #2c5aa0;
                }
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
                <div class="company-name">StaffIQ Company</div>
                <div class="payslip-title">PAYSLIP</div>
                <div>Period: ' . ucfirst($payroll->month) . ' ' . $payroll->year . '</div>
            </div>

            <div class="employee-info">
                <div class="info-section">
                    <h3>Employee Information</h3>
                    <div class="info-row">
                        <span class="label">Name:</span>
                        <span class="value">' . $employee->first_name . ' ' . $employee->last_name . '</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Employee ID:</span>
                        <span class="value">' . $employee->employee_id . '</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Department:</span>
                        <span class="value">' . ($department ? $department->name : 'N/A') . '</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Position:</span>
                        <span class="value">' . $employee->position . '</span>
                    </div>
                </div>
                <div class="info-section">
                    <h3>Payroll Information</h3>
                    <div class="info-row">
                        <span class="label">Payroll ID:</span>
                        <span class="value">' . $payroll->payroll_id . '</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Payment Status:</span>
                        <span class="value">' . ucfirst($payroll->payment_status) . '</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Processed Date:</span>
                        <span class="value">' . $payroll->processed_at->format('d/m/Y') . '</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Payment Method:</span>
                        <span class="value">' . ($payroll->payment_method ?: 'N/A') . '</span>
                    </div>
                </div>
            </div>

            <div class="salary-details">
                <div class="earnings-section">
                    <div class="section-title">Earnings</div>
                    <table class="salary-table">
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                        <tr>
                            <td>Basic Salary</td>
                            <td>$' . number_format($payroll->basic_salary, 2) . '</td>
                        </tr>
                        <tr>
                            <td>Allowances</td>
                            <td>$' . number_format($payroll->allowances, 2) . '</td>
                        </tr>
                        <tr>
                            <td>Bonus</td>
                            <td>$' . number_format($payroll->bonus, 2) . '</td>
                        </tr>
                        <tr>
                            <td>Overtime Pay</td>
                            <td>$' . number_format($payroll->overtime_pay, 2) . '</td>
                        </tr>
                        <tr class="total-row">
                            <td><strong>Total Earnings</strong></td>
                            <td><strong>$' . number_format($payroll->gross_salary, 2) . '</strong></td>
                        </tr>
                    </table>
                </div>

                <div class="deductions-section">
                    <div class="section-title">Deductions</div>
                    <table class="salary-table">
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                        <tr>
                            <td>Tax Deduction</td>
                            <td>$' . number_format($payroll->tax_deduction, 2) . '</td>
                        </tr>
                        <tr>
                            <td>Insurance Deduction</td>
                            <td>$' . number_format($payroll->insurance_deduction, 2) . '</td>
                        </tr>
                        <tr>
                            <td>Other Deductions</td>
                            <td>$' . number_format($payroll->other_deductions, 2) . '</td>
                        </tr>
                        <tr class="total-row">
                            <td><strong>Total Deductions</strong></td>
                            <td><strong>$' . number_format($payroll->total_deductions, 2) . '</strong></td>
                        </tr>
                    </table>
                </div>

                <div class="net-salary">
                    <table class="salary-table">
                        <tr>
                            <td><strong>Net Salary</strong></td>
                            <td><strong>$' . number_format($payroll->net_salary, 2) . '</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="footer">
                <p>This is a computer generated document. No signature is required.</p>
                <p>Generated on: ' . now()->format('d/m/Y H:i:s') . '</p>
            </div>
        </body>
        </html>';
    }

    /**
     * Download payslip PDF
     */
    public function downloadPayslip(Payroll $payroll)
    {
        // Generate payslip if not exists
        if (!$payroll->payslip_file || !Storage::exists('public/' . $payroll->payslip_file)) {
            $this->generatePayslip($payroll);
        }

        $path = $payroll->payslip_file;

        if (Storage::exists('public/' . $path)) {
            return Storage::download('public/' . $path);
        }

        throw new \Exception('Payslip file not found.');
    }

    /**
     * Generate payslip for multiple payrolls
     */
    public function generateBulkPayslips($payrolls)
    {
        $generatedFiles = [];

        foreach ($payrolls as $payroll) {
            try {
                $filePath = $this->generatePayslip($payroll);
                $generatedFiles[] = $filePath;
            } catch (\Exception $e) {
                \Log::error('Error generating payslip for payroll ' . $payroll->id . ': ' . $e->getMessage());
            }
        }

        return $generatedFiles;
    }
}
