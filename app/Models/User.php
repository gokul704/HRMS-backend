<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole($roles): bool
    {
        return in_array($this->role, (array) $roles);
    }

    /**
     * Check if user is HR
     */
    public function isHr(): bool
    {
        return $this->hasRole('hr');
    }

    /**
     * Check if user is Manager
     */
    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    /**
     * Check if user is Employee
     */
    public function isEmployee(): bool
    {
        return $this->hasRole('employee');
    }

    /**
     * Get the employee record associated with the user
     */
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Get offer letters created by this user
     */
    public function createdOfferLetters()
    {
        return $this->hasMany(OfferLetter::class, 'created_by');
    }

    /**
     * Get offer letters approved by this user
     */
    public function approvedOfferLetters()
    {
        return $this->hasMany(OfferLetter::class, 'approved_by');
    }

    /**
     * Get payrolls processed by this user
     */
    public function processedPayrolls()
    {
        return $this->hasMany(Payroll::class, 'processed_by');
    }
}
