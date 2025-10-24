<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'midwife_id',
        'bhw_id',
        'appointment_date',
        'appointment_time',
        'reason',
        'status',
        'created_by_role',
        'notes',
        'email_notifications_sent',
        'last_email_sent_at',
        'uploaded_files',
        'approved_at',
        'completed_at',
        'declined_reason',
        'urgency_level',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
        'email_notifications_sent' => 'array',
        'uploaded_files' => 'array',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function midwife()
    {
        return $this->belongsTo(Midwife::class);
    }

    public function bhw()
    {
        return $this->belongsTo(BHW::class);
    }

    public function ehrRecord()
    {
        return $this->hasOne(EhrRecord::class);
    }

    /**
     * Check if a specific email notification has been sent
     */
    public function hasEmailBeenSent(string $emailType, string $recipientType): bool
    {
        $notifications = $this->email_notifications_sent ?? [];
        return isset($notifications[$emailType][$recipientType]);
    }

    /**
     * Mark an email notification as sent
     */
    public function markEmailAsSent(string $emailType, string $recipientType): void
    {
        $notifications = $this->email_notifications_sent ?? [];
        $notifications[$emailType][$recipientType] = now()->toISOString();

        $this->email_notifications_sent = $notifications;
        $this->last_email_sent_at = now();
        $this->save();
    }

    /**
     * Get all sent email notifications
     */
    public function getSentEmails(): array
    {
        return $this->email_notifications_sent ?? [];
    }

    /**
     * Scope for pending appointments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved appointments
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for completed appointments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for declined appointments
     */
    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }

    /**
     * Scope for expired appointments
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    /**
     * Scope for urgent appointments
     */
    public function scopeUrgent($query)
    {
        return $query->where('urgency_level', 'urgent');
    }

    /**
     * Scope for maternal appointments
     */
    public function scopeMaternal($query)
    {
        return $query->where('urgency_level', 'maternal');
    }
}
