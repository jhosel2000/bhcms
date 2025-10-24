<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Midwife extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'professional_license_number',
        'contact_number',
        'email_address',
        'area_of_assignment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
