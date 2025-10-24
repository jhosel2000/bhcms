<?php

namespace App\Policies;

use App\Models\Prescription;
use App\Models\User;

class PrescriptionPolicy
{
    /**
     * Determine if the user can view the prescription
     */
    public function view(User $user, Prescription $prescription): bool
    {
        // Doctor can view their own prescriptions
        if ($user->role === 'doctor' && $user->doctor) {
            return $prescription->doctor_id === $user->doctor->id;
        }

        // Patient can view their own prescriptions
        if ($user->role === 'patient' && $user->patient) {
            return $prescription->patient_id === $user->patient->id;
        }

        // No admin role in system; others cannot view
        return false;
    }

    /**
     * Determine if the user can create prescriptions
     */
    public function create(User $user): bool
    {
        return $user->role === 'doctor' && $user->doctor !== null;
    }

    /**
     * Determine if the user can update the prescription
     */
    public function update(User $user, Prescription $prescription): bool
    {
        // Only the prescribing doctor can update
        if ($user->role === 'doctor' && $user->doctor) {
            return $prescription->doctor_id === $user->doctor->id;
        }

        // No admin role in system
        return false;
    }

    /**
     * Determine if the user can delete the prescription
     */
    public function delete(User $user, Prescription $prescription): bool
    {
        // Only the prescribing doctor can delete
        if ($user->role === 'doctor' && $user->doctor) {
            return $prescription->doctor_id === $user->doctor->id;
        }

        // No admin role in system
        return false;
    }
}