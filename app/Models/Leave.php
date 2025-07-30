<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'manager_id',
        'manager_approved_at',
        'manager_remarks',
        'hr_approved_at',
        'hr_remarks',
        'applied_by',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'manager_approved_at' => 'datetime',
        'hr_approved_at' => 'datetime',
        'approved_at' => 'datetime',
        'total_days' => 'decimal:1',
    ];

    // Relationships
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function appliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applied_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->whereIn('status', ['manager_approved', 'hr_approved']);
    }

    public function scopeRejected($query)
    {
        return $query->whereIn('status', ['manager_rejected', 'hr_rejected']);
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->whereHas('employee', function ($q) use ($departmentId) {
            $q->where('department_id', $departmentId);
        });
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'manager_approved', 'hr_approved' => 'success',
            'manager_rejected', 'hr_rejected' => 'danger',
            'cancelled' => 'secondary',
            default => 'secondary'
        };
    }

    public function getLeaveTypeLabelAttribute()
    {
        return match($this->leave_type) {
            'annual' => 'Annual Leave',
            'sick' => 'Sick Leave',
            'casual' => 'Casual Leave',
            'maternity' => 'Maternity Leave',
            'paternity' => 'Paternity Leave',
            'other' => 'Other Leave',
            default => ucfirst($this->leave_type)
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending',
            'manager_approved' => 'Manager Approved',
            'manager_rejected' => 'Manager Rejected',
            'hr_approved' => 'HR Approved',
            'hr_rejected' => 'HR Rejected',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status)
        };
    }
}
