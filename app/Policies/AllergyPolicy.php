<?php

namespace App\Policies;

use App\Models\Allergy;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AllergyPolicy
{
    /**
     * Determine whether the user can view any allergies.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('doctor');
    }

    /**
     * Determine whether the user can view the allergy.
     */
    public function view(User $user, Allergy $allergy): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $allergy->patient->medicalRecords()->first()->doctor_id;
    }

    /**
     * Determine whether the user can create allergies.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('doctor');
    }

    /**
     * Determine whether the user can update the allergy.
     */
    public function update(User $user, Allergy $allergy): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $allergy->patient->medicalRecords()->first()->doctor_id;
    }

    /**
     * Determine whether the user can delete the allergy.
     */
    public function delete(User $user, Allergy $allergy): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $allergy->patient->medicalRecords()->first()->doctor_id;
    }

    /**
     * Determine whether the user can restore the allergy.
     */
    public function restore(User $user, Allergy $allergy): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $allergy->patient->medicalRecords()->first()->doctor_id;
    }

    /**
     * Determine whether the user can permanently delete the allergy.
     */
    public function forceDelete(User $user, Allergy $allergy): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $allergy->patient->medicalRecords()->first()->doctor_id;
    }

    /**
     * Determine whether the user can view critical allergies.
     */
    public function viewCritical(User $user, Allergy $allergy): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $allergy->patient->medicalRecords()->first()->doctor_id &&
               in_array($allergy->severity, ['severe', 'life_threatening']);
    }

    /**
     * Determine whether the user can manage allergies for a specific patient.
     */
    public function managePatientAllergies(User $user, Allergy $allergy): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $allergy->patient->medicalRecords()->first()->doctor_id;
    }
}
