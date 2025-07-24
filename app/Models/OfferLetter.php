<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'candidate_name',
        'candidate_email',
        'candidate_phone',
        'position',
        'department_id',
        'offered_salary',
        'salary_currency',
        'offer_date',
        'joining_date',
        'job_description',
        'benefits',
        'terms_and_conditions',
        'status',
        'response_date',
        'response_notes',
        'created_by',
        'approved_by',
        'approved_at',
        'offer_letter_file',
    ];

    protected $casts = [
        'offer_date' => 'date',
        'joining_date' => 'date',
        'response_date' => 'date',
        'offered_salary' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the department for this offer
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user who created this offer
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved this offer
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope to get only draft offers
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope to get only sent offers
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope to get only accepted offers
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope to get only rejected offers
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Check if offer is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if offer is sent
     */
    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    /**
     * Check if offer is accepted
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if offer is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if offer is expired
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    /**
     * Approve the offer letter
     */
    public function approve($approvedBy)
    {
        $this->update([
            'approved_by' => $approvedBy,
            'approved_at' => now(),
        ]);
    }
}
