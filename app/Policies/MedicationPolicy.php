<?php

namespace App\Policies;

use App\Models\Medication;
use App\Models\User;

class MedicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('doctor');
    }

    public function view(User $user, Medication $medication): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $medication->patient->medicalRecords()->first()->doctor_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('doctor');
    }

    public function update(User $user, Medication $medication): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $medication->patient->medicalRecords()->first()->doctor_id;
    }

    public function delete(User $user, Medication $medication): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $medication->patient->medicalRecords()->first()->doctor_id;
    }

    public function restore(User $user, Medication $medication): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $medication->patient->medicalRecords()->first()->doctor_id;
    }

    public function forceDelete(User $user, Medication $medication): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $medication->patient->medicalRecords()->first()->doctor_id;
    }

    public function viewCurrent(User $user, Medication $medication): bool
    {
        return $this->view($user, $medication) &&
               in_array($medication->status, ['active', 'discontinued']);
    }

    public function managePatientMedications(User $user, Medication $medication): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $medication->patient->medicalRecords()->first()->doctor_id;
    }
}
