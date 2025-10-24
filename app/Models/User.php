<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function midwife()
    {
        return $this->hasOne(Midwife::class);
    }

    public function bhw()
    {
        return $this->hasOne(BHW::class);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    /**
     * Get the related role model instance (doctor, midwife, bhw, patient).
     */
    public function getRoleModel()
    {
        switch ($this->role) {
            case 'doctor':
                return $this->doctor;
            case 'midwife':
                return $this->midwife;
            case 'bhw':
                return $this->bhw;
            case 'patient':
                return $this->patient;
            default:
                return null;
        }
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has a specific role (alternative method)
     */
    public function checkRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user has all of the given roles
     */
    public function hasAllRoles(array $roles): bool
    {
        return count(array_intersect([$this->role], $roles)) === count($roles);
    }

    /**
     * Get the user's role display name
     */
    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            'doctor' => 'Doctor',
            'midwife' => 'Midwife',
            'bhw' => 'Barangay Health Worker',
            'patient' => 'Patient',
            default => 'Unknown'
        };
    }
}
