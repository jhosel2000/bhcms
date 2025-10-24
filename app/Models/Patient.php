<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $full_name
 * @property \Carbon\Carbon $date_of_birth
 * @property string $gender
 * @property string $full_address
 * @property string $contact_number
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_number
 * @property string|null $civil_status
 * @property string|null $occupation
 * @property string|null $religion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'date_of_birth',
        'gender',
        'full_address',
        'barangay',
        'contact_number',
        'emergency_contact_name',
        'emergency_contact_number',
        'civil_status',
        'occupation',
        'religion',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function allergies()
    {
        return $this->hasMany(Allergy::class);
    }

    public function medications()
    {
        return $this->hasMany(Medication::class);
    }

    public function labResults()
    {
        return $this->hasMany(LabResult::class);
    }

    public function clinicalNotes()
    {
        // Removed clinical notes relation as ClinicalNote model is deleted
        // return $this->hasMany(ClinicalNote::class);
    }

    public function risks()
    {
        return $this->hasMany(PatientRisk::class);
    }


    public function ehrRecords()
    {
        return $this->hasMany(EhrRecord::class);
    }

    public function medicalAttachments()
    {
        return $this->hasMany(MedicalAttachment::class);
    }

    public function maternalCareRecords()
    {
        return $this->hasMany(MaternalCareRecord::class);
    }

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    // Helper methods for easy access to current medical data
    public function activeAllergies()
    {
        return $this->allergies()->active();
    }

    public function criticalAllergies()
    {
        return $this->allergies()->active()->critical();
    }

    public function currentMedications()
    {
        return $this->medications()->current();
    }

    public function activeRisks()
    {
        return $this->risks()->active()->requiresAlert();
    }

    public function recentLabResults()
    {
        return $this->labResults()->recent()->orderBy('test_date', 'desc');
    }

    public function criticalLabResults()
    {
        return $this->labResults()->critical()->orderBy('test_date', 'desc');
    }
}
