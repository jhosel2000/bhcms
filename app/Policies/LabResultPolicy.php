<?php

namespace App\Policies;

use App\Models\LabResult;
use App\Models\User;

class LabResultPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('doctor');
    }

    public function view(User $user, LabResult $labResult): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $labResult->patient->medicalRecords()->first()->doctor_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('doctor');
    }

    public function update(User $user, LabResult $labResult): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $labResult->patient->medicalRecords()->first()->doctor_id;
    }

    public function delete(User $user, LabResult $labResult): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $labResult->patient->medicalRecords()->first()->doctor_id;
    }

    public function restore(User $user, LabResult $labResult): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $labResult->patient->medicalRecords()->first()->doctor_id;
    }

    public function forceDelete(User $user, LabResult $labResult): bool
    {
        return $user->hasRole('doctor') &&
               $user->doctor->id === $labResult->patient->medicalRecords()->first()->doctor_id;
    }
}
