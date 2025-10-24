<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EhrRecord extends Model
{
    use HasFactory;

    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_FLAGGED = 'flagged';

    protected $fillable = [
        'patient_id',
        'created_by',
        'created_by_role',
        'record_type',
        'title',
        'description',
        'notes',
        'attachments',
        'appointment_id',
        'status',
        'reviewed_by',
        'review_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the patient that owns the EHR record.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the user who created the EHR record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who reviewed the EHR record.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the appointment associated with the EHR record.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Scope a query to only include records of a specific type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('record_type', $type);
    }

    /**
     * Scope a query to only include records created by a specific role.
     */
    public function scopeCreatedByRole($query, string $role)
    {
        return $query->where('created_by_role', $role);
    }

    /**
     * Scope a query to only include records created by a specific user.
     */
    public function scopeCreatedBy($query, int $userId)
    {
        return $query->where('created_by', $userId);
    }

    /**
     * Scope a query to only include records with a specific status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include recent records.
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get the record type display name.
     */
    public function getRecordTypeDisplayAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->record_type));
    }

    /**
     * Get the created by role display name.
     */
    public function getCreatedByRoleDisplayAttribute(): string
    {
        return ucfirst($this->created_by_role);
    }

    /**
     * Get the creator's full name.
     */
    public function getCreatorNameAttribute(): string
    {
        return $this->creator->full_name ?? 'System';
    }

    /**
     * Get the status display label.
     */
    public function getStatusDisplayAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING_REVIEW => 'Pending Review',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_FLAGGED => 'Flagged',
            default => ucfirst(str_replace('_', ' ', (string) $this->status)),
        };
    }
}
