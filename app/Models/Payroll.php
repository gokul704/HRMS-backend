<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_id',
        'employee_id',
        'month',
        'year',
        'basic_salary',
        'allowances',
        'bonus',
        'overtime_pay',
        'gross_salary',
        'tax_deduction',
        'insurance_deduction',
        'other_deductions',
        'net_salary',
        'payment_status',
        'payment_date',
        'payment_method',
        'payment_notes',
        'processed_by',
        'processed_at',
        'payslip_file',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'bonus' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'tax_deduction' => 'decimal:2',
        'insurance_deduction' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'payment_date' => 'date',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the employee for this payroll
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the user who processed this payroll
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope to get only pending payrolls
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope to get only paid payrolls
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope to get only failed payrolls
     */
    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    /**
     * Check if payroll is pending
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if payroll is paid
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if payroll is failed
     */
    public function isFailed(): bool
    {
        return $this->payment_status === 'failed';
    }

    /**
     * Mark payroll as paid
     */
    public function markAsPaid($paymentMethod = null, $notes = null)
    {
        $this->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
            'payment_method' => $paymentMethod,
            'payment_notes' => $notes,
        ]);
    }

    /**
     * Mark payroll as failed
     */
    public function markAsFailed($notes = null)
    {
        $this->update([
            'payment_status' => 'failed',
            'payment_notes' => $notes,
        ]);
    }

    /**
     * Calculate total deductions
     */
    public function getTotalDeductionsAttribute()
    {
        return $this->tax_deduction + $this->insurance_deduction + $this->other_deductions;
    }

    /**
     * Calculate total earnings
     */
    public function getTotalEarningsAttribute()
    {
        return $this->basic_salary + $this->allowances + $this->bonus + $this->overtime_pay;
    }
}
