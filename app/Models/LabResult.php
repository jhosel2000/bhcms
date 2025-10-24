<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'test_name',
        'test_code',
        'category',
        'result_value',
        'unit',
        'reference_range',
        'status',
        'interpretation',
        'test_date',
        'result_date',
        'performed_by',
        'ordered_by',
        'notes',
        'file_path',
        'file_name',
    ];

    protected $casts = [
        'test_date' => 'datetime',
        'result_date' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Scope for recent results (last 30 days)
    public function scopeRecent($query)
    {
        return $query->where('test_date', '>=', now()->subDays(30));
    }

    // Scope for abnormal results
    public function scopeAbnormal($query)
    {
        return $query->whereIn('status', ['abnormal', 'critical']);
    }

    // Scope for critical results
    public function scopeCritical($query)
    {
        return $query->where('status', 'critical');
    }

    // Scope by category
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
