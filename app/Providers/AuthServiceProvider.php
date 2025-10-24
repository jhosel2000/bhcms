<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\MaternalCareRecord' => 'App\Policies\MaternalCareRecordPolicy',
        'App\Models\Appointment' => 'App\Policies\AppointmentPolicy',
        'App\Models\MedicalRecord' => 'App\Policies\MedicalRecordPolicy',
        'App\Models\Allergy' => 'App\Policies\AllergyPolicy',
        'App\Models\Medication' => 'App\Policies\MedicationPolicy',
        'App\Models\LabResult' => 'App\Policies\LabResultPolicy',
        'App\Models\PatientRisk' => 'App\Policies\PatientRiskPolicy',
        'App\Models\Prescription' => 'App\Policies\PrescriptionPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
