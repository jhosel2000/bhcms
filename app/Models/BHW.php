<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BHW extends Model
{
    use HasFactory;

    protected $table = 'bhws';

    protected $fillable = [
        'user_id',
        'full_name',
        'date_of_birth',
        'contact_number',
        'email_address',
        'purok_zone_of_assignment',
        'barangay_id_number',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
