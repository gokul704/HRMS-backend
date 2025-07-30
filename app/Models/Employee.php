<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'employee_id',
        'first_name',
        'last_name',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'position',
        'hire_date',
        'salary',
        'employment_status',
        'termination_date',
        'termination_reason',
        'documents',
        'is_onboarded',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'termination_date' => 'date',
        'salary' => 'decimal:2',
        'documents' => 'array',
        'is_onboarded' => 'boolean',
    ];

    /**
     * Get the user associated with this employee
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department this employee belongs to
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get payroll records for this employee
     */
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    /**
     * Get leave records for this employee
     */
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    /**
     * Get full name of the employee
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Scope to get only active employees
     */
    public function scopeActive($query)
    {
        return $query->where('employment_status', 'active');
    }

    /**
     * Scope to get only onboarded employees
     */
    public function scopeOnboarded($query)
    {
        return $query->where('is_onboarded', true);
    }

    /**
     * Check if employee is active
     */
    public function isActive(): bool
    {
        return $this->employment_status === 'active';
    }

    /**
     * Check if employee is onboarded
     */
    public function isOnboarded(): bool
    {
        return $this->is_onboarded;
    }
}
