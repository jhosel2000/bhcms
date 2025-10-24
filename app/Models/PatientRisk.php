<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientRisk extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'risk_type',
        'title',
        'severity',
        'description',
        'status',
        'identified_date',
        'review_date',
        'identified_by',
        'management_plan',
        'notes',
        'requires_alert',
        'file_path',
        'file_name',
    ];

    protected $casts = [
        'identified_date' => 'datetime',
        'review_date' => 'datetime',
        'requires_alert' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'identified_by');
    }

    // Scope for critical risks
    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    // Scope for high severity risks
    public function scopeHighSeverity($query)
    {
        return $query->whereIn('severity', ['high', 'critical']);
    }

    // Scope by type
    public function scopeByType($query, $type)
    {
        return $query->where('risk_type', $type);
    }

    // Scope by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope for active risks
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for risks that require alerts
    public function scopeRequiresAlert($query)
    {
        return $query->where('requires_alert', true);
    }

    // Scope for alerts (high severity risks that require alerts)
    public function scopeAlerts($query)
    {
        return $query->where('requires_alert', true)->whereIn('severity', ['high', 'critical']);
    }

    // Scope for upcoming reviews
    public function scopeUpcomingReviews($query)
    {
        return $query->where('next_review_date', '<=', now()->addDays(30));
    }
}
