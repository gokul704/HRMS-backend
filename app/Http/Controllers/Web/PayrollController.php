<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\Employee;
use App\Http\Controllers\Web\BaseController;

class PayrollController extends BaseController
{
    /**
     * Display a listing of payrolls
     */
    public function index()
    {
        $payrolls = Payroll::with(['employee.user', 'processedBy'])
            ->when(request('search'), function($query, $search) {
                $query->whereHas('employee', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('employee_id', 'like', "%{$search}%");
                });
            })
            ->when(request('status'), function($query, $status) {
                $query->where('payment_status', $status);
            })
            ->when(request('period'), function($query, $period) {
                $query->where('payroll_period', $period);
            })
            ->latest()
            ->paginate(15);

        return $this->safeView('payrolls.index', compact('payrolls'));
    }

    /**
     * Show the form for creating a new payroll
     */
    public function create()
    {
        $employees = Employee::where('employment_status', 'active')->get();
        return $this->safeView('payrolls.create', compact('employees'));
    }

    /**
     * Store a newly created payroll
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payroll_period' => 'required|string|max:10',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,failed',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $request->merge(['processed_by' => auth()->id()]);

        Payroll::create($request->all());

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll created successfully!');
    }

    /**
     * Display the specified payroll
     */
    public function show(Payroll $payroll)
    {
        $payroll->load(['employee.user', 'processedBy']);
        return $this->safeView('payrolls.show', compact('payroll'));
    }

    /**
     * Show the form for editing the specified payroll
     */
    public function edit(Payroll $payroll)
    {
        $employees = Employee::where('employment_status', 'active')->get();
        return $this->safeView('payrolls.edit', compact('payroll', 'employees'));
    }

    /**
     * Update the specified payroll
     */
    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payroll_period' => 'required|string|max:10',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,failed',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $payroll->update($request->all());

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll updated successfully!');
    }

    /**
     * Remove the specified payroll
     */
    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll deleted successfully!');
    }

    /**
     * Mark payroll as paid
     */
    public function markAsPaid(Payroll $payroll)
    {
        $payroll->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);

        return back()->with('success', 'Payroll marked as paid successfully!');
    }

    /**
     * Mark payroll as failed
     */
    public function markAsFailed(Payroll $payroll)
    {
        $payroll->update([
            'payment_status' => 'failed',
        ]);

        return back()->with('success', 'Payroll marked as failed!');
    }

    /**
     * Show payroll statistics
     */
    public function statistics()
    {
        $data = [
            'totalPayrolls' => Payroll::count(),
            'pendingPayrolls' => Payroll::where('payment_status', 'pending')->count(),
            'paidPayrolls' => Payroll::where('payment_status', 'paid')->count(),
            'failedPayrolls' => Payroll::where('payment_status', 'failed')->count(),
            'totalPaid' => Payroll::where('payment_status', 'paid')->sum('net_salary'),
            'totalPending' => Payroll::where('payment_status', 'pending')->sum('net_salary'),
            'payrollsByPeriod' => Payroll::selectRaw('payroll_period, COUNT(*) as count, SUM(net_salary) as total')
                ->groupBy('payroll_period')
                ->orderBy('payroll_period', 'desc')
                ->get(),
            'recentPayrolls' => Payroll::with(['employee.user'])->latest()->take(10)->get(),
        ];

        return $this->safeView('payrolls.statistics', $data);
    }

    /**
     * Show payrolls by employee
     */
    public function byEmployee(Employee $employee)
    {
        $payrolls = $employee->payrolls()->latest()->paginate(15);
        return $this->safeView('payrolls.by-employee', compact('payrolls', 'employee'));
    }

    /**
     * Generate bulk payrolls
     */
    public function generateBulk(Request $request)
    {
        $request->validate([
            'payroll_period' => 'required|string|max:10',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $employees = Employee::where('employment_status', 'active')
            ->when($request->department_id, function($query, $departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->get();

        $created = 0;
        foreach ($employees as $employee) {
            // Check if payroll already exists for this period
            $existingPayroll = Payroll::where('employee_id', $employee->id)
                ->where('payroll_period', $request->payroll_period)
                ->first();

            if (!$existingPayroll) {
                Payroll::create([
                    'employee_id' => $employee->id,
                    'payroll_period' => $request->payroll_period,
                    'basic_salary' => $employee->salary,
                    'allowances' => 0,
                    'deductions' => 0,
                    'net_salary' => $employee->salary,
                    'payment_status' => 'pending',
                    'processed_by' => auth()->id(),
                ]);
                $created++;
            }
        }

        return back()->with('success', "Generated {$created} payroll records for period {$request->payroll_period}!");
    }

    /**
     * Show employee payrolls (Employee only)
     */
    public function employeePayrolls()
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee profile not found.');
        }

        $payrolls = $employee->payrolls()->latest()->paginate(15);

        return $this->safeView('payrolls.employee-payrolls', compact('payrolls', 'employee'));
    }
}
