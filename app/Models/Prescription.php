<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'medication_name',
        'dosage',
        'frequency',
        'duration',
        'instructions',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Query Scopes
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public function scopePendingRefill(Builder $query): Builder
    {
        return $query->where('status', 'pending_refill');
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeForDoctor(Builder $query, $doctorId): Builder
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeForPatient(Builder $query, $patientId): Builder
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeSearch(Builder $query, $search): Builder
    {
        return $query->where(function($q) use ($search) {
            $q->where('medication_name', 'like', '%' . $search . '%')
              ->orWhere('dosage', 'like', '%' . $search . '%')
              ->orWhere('instructions', 'like', '%' . $search . '%')
              ->orWhereHas('patient', function($pq) use ($search) {
                  $pq->where('full_name', 'like', '%' . $search . '%');
              });
        });
    }

    /**
     * Accessors & Helpers
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800 border-green-200',
            'completed' => 'bg-gray-100 text-gray-800 border-gray-200',
            'pending_refill' => 'bg-orange-100 text-orange-800 border-orange-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'Active',
            'completed' => 'Completed',
            'pending_refill' => 'Pending Refill',
            default => ucfirst($this->status),
        };
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('M j, Y');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPendingRefill(): bool
    {
        return $this->status === 'pending_refill';
    }
}
