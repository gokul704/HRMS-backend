<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PayrollController extends Controller
{
    /**
     * Display a listing of payrolls
     */
    public function index(Request $request)
    {
        $query = Payroll::with(['employee.user', 'employee.department', 'processedBy']);

        // Filter by employee
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by payment status
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by month and year
        if ($request->has('month')) {
            $query->where('month', $request->month);
        }

        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $payrolls = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $payrolls,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created payroll
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|string|max:20',
            'year' => 'required|integer|min:2000|max:2100',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'tax_deduction' => 'nullable|numeric|min:0',
            'insurance_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:100',
            'payment_notes' => 'nullable|string',
        ]);

        // Check if payroll already exists for this employee and month/year
        $existingPayroll = Payroll::where('employee_id', $request->employee_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();

        if ($existingPayroll) {
            return response()->json([
                'success' => false,
                'message' => 'Payroll already exists for this employee and month/year',
            ], 400);
        }

        // Calculate gross and net salary
        $grossSalary = $request->basic_salary +
                      ($request->allowances ?? 0) +
                      ($request->bonus ?? 0) +
                      ($request->overtime_pay ?? 0);

        $totalDeductions = ($request->tax_deduction ?? 0) +
                          ($request->insurance_deduction ?? 0) +
                          ($request->other_deductions ?? 0);

        $netSalary = $grossSalary - $totalDeductions;

        // Generate payroll ID
        $payrollId = 'PAY' . str_pad(Payroll::count() + 1, 3, '0', STR_PAD_LEFT);

        $payroll = Payroll::create([
            'payroll_id' => $payrollId,
            'employee_id' => $request->employee_id,
            'month' => $request->month,
            'year' => $request->year,
            'basic_salary' => $request->basic_salary,
            'allowances' => $request->allowances ?? 0,
            'bonus' => $request->bonus ?? 0,
            'overtime_pay' => $request->overtime_pay ?? 0,
            'gross_salary' => $grossSalary,
            'tax_deduction' => $request->tax_deduction ?? 0,
            'insurance_deduction' => $request->insurance_deduction ?? 0,
            'other_deductions' => $request->other_deductions ?? 0,
            'net_salary' => $netSalary,
            'payment_status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_notes' => $request->payment_notes,
            'processed_by' => $request->user()->id,
            'processed_at' => now(),
        ]);

        $payroll->load(['employee.user', 'employee.department', 'processedBy']);

        return response()->json([
            'success' => true,
            'message' => 'Payroll created successfully',
            'data' => $payroll,
        ], 201);
    }

    /**
     * Display the specified payroll
     */
    public function show(Payroll $payroll)
    {
        $payroll->load(['employee.user', 'employee.department', 'processedBy']);

        return response()->json([
            'success' => true,
            'data' => $payroll,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified payroll
     */
    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'basic_salary' => 'sometimes|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'tax_deduction' => 'nullable|numeric|min:0',
            'insurance_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:100',
            'payment_notes' => 'nullable|string',
        ]);

        // Only allow updates if payroll is pending
        if (!$payroll->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update payroll that is not pending',
            ], 400);
        }

        $data = $request->all();

        // Recalculate gross and net salary if salary components changed
        if ($request->has('basic_salary') || $request->has('allowances') ||
            $request->has('bonus') || $request->has('overtime_pay')) {

            $grossSalary = ($request->basic_salary ?? $payroll->basic_salary) +
                          ($request->allowances ?? $payroll->allowances) +
                          ($request->bonus ?? $payroll->bonus) +
                          ($request->overtime_pay ?? $payroll->overtime_pay);

            $data['gross_salary'] = $grossSalary;
        }

        if ($request->has('tax_deduction') || $request->has('insurance_deduction') ||
            $request->has('other_deductions') || isset($data['gross_salary'])) {

            $totalDeductions = ($request->tax_deduction ?? $payroll->tax_deduction) +
                              ($request->insurance_deduction ?? $payroll->insurance_deduction) +
                              ($request->other_deductions ?? $payroll->other_deductions);

            $data['net_salary'] = ($data['gross_salary'] ?? $payroll->gross_salary) - $totalDeductions;
        }

        $payroll->update($data);
        $payroll->load(['employee.user', 'employee.department']);

        return response()->json([
            'success' => true,
            'message' => 'Payroll updated successfully',
            'data' => $payroll,
        ]);
    }

    /**
     * Remove the specified payroll
     */
    public function destroy(Payroll $payroll)
    {
        // Only allow deletion if payroll is pending
        if (!$payroll->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete payroll that is not pending',
            ], 400);
        }

        $payroll->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payroll deleted successfully',
        ]);
    }

    /**
     * Mark payroll as paid
     */
    public function markAsPaid(Request $request, Payroll $payroll)
    {
        $request->validate([
            'payment_method' => 'nullable|string|max:100',
            'payment_notes' => 'nullable|string',
        ]);

        $payroll->markAsPaid(
            $request->payment_method,
            $request->payment_notes
        );

        return response()->json([
            'success' => true,
            'message' => 'Payroll marked as paid successfully',
            'data' => $payroll,
        ]);
    }

    /**
     * Mark payroll as failed
     */
    public function markAsFailed(Request $request, Payroll $payroll)
    {
        $request->validate([
            'payment_notes' => 'nullable|string',
        ]);

        $payroll->markAsFailed($request->payment_notes);

        return response()->json([
            'success' => true,
            'message' => 'Payroll marked as failed',
            'data' => $payroll,
        ]);
    }

    /**
     * Get payroll statistics
     */
    public function statistics()
    {
        $stats = [
            'total_payrolls' => Payroll::count(),
            'pending_payrolls' => Payroll::where('payment_status', 'pending')->count(),
            'paid_payrolls' => Payroll::where('payment_status', 'paid')->count(),
            'failed_payrolls' => Payroll::where('payment_status', 'failed')->count(),
            'total_paid_amount' => Payroll::where('payment_status', 'paid')->sum('net_salary'),
            'total_pending_amount' => Payroll::where('payment_status', 'pending')->sum('net_salary'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Get payrolls by employee
     */
    public function byEmployee(Employee $employee)
    {
        $payrolls = $employee->payrolls()
            ->with(['processedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'employee' => $employee->load(['user', 'department']),
                'payrolls' => $payrolls,
            ],
        ]);
    }

    /**
     * Generate payroll for all employees
     */
    public function generateBulk(Request $request)
    {
        $request->validate([
            'month' => 'required|string|max:20',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        $employees = Employee::where('employment_status', 'active')->get();
        $generatedCount = 0;

        foreach ($employees as $employee) {
            // Check if payroll already exists
            $existingPayroll = Payroll::where('employee_id', $employee->id)
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->first();

            if (!$existingPayroll) {
                // Generate payroll ID
                $payrollId = 'PAY' . str_pad(Payroll::count() + 1, 3, '0', STR_PAD_LEFT);

                Payroll::create([
                    'payroll_id' => $payrollId,
                    'employee_id' => $employee->id,
                    'month' => $request->month,
                    'year' => $request->year,
                    'basic_salary' => $employee->salary,
                    'allowances' => 0,
                    'bonus' => 0,
                    'overtime_pay' => 0,
                    'gross_salary' => $employee->salary,
                    'tax_deduction' => 0,
                    'insurance_deduction' => 0,
                    'other_deductions' => 0,
                    'net_salary' => $employee->salary,
                    'payment_status' => 'pending',
                    'processed_by' => $request->user()->id,
                    'processed_at' => now(),
                ]);

                $generatedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Generated {$generatedCount} payroll records",
            'data' => [
                'generated_count' => $generatedCount,
                'month' => $request->month,
                'year' => $request->year,
            ],
        ]);
    }
}
