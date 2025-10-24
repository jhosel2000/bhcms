<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'allergen_type',
        'allergen_name',
        'severity',
        'reaction_description',
        'status',
        'first_occurrence',
        'notes',
        'file_path',
        'file_name',
    ];

    protected $casts = [
        'first_occurrence' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Scope for active allergies
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for critical allergies
    public function scopeCritical($query)
    {
        return $query->whereIn('severity', ['severe', 'life_threatening']);
    }
}
