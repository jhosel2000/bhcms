<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaternalCareRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'midwife_id',
        'type',
        'visit_date',
        'visit_time',
        'notes',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'blood_pressure',
        'weight',
        'height',
        'heart_rate',
        'temperature',
        'additional_findings',
        'next_visit_date',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'visit_time' => 'datetime:H:i',
        'next_visit_date' => 'date',
        'blood_pressure_systolic' => 'decimal:2',
        'blood_pressure_diastolic' => 'decimal:2',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'temperature' => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function midwife()
    {
        return $this->belongsTo(Midwife::class);
    }
}
