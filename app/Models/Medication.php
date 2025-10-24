<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'medication_name',
        'dosage',
        'frequency',
        'route',
        'status',
        'start_date',
        'end_date',
        'prescribed_by',
        'indication',
        'instructions',
        'notes',
        'file_path',
        'file_name',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Scope for active medications
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for current medications (not ended)
    public function scopeCurrent($query)
    {
        return $query->where(function($q) {
            $q->where('status', 'active')
              ->orWhere(function($q2) {
                  $q2->where('status', '!=', 'discontinued')
                     ->where('end_date', '>', now());
              });
        });
    }
}
