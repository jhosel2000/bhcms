<?php

namespace App\Policies;

use App\Models\PatientRisk;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class PatientRiskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('doctor');
    }

    public function view(User $user, PatientRisk $patientRisk): bool
    {
        return $user->hasRole('doctor') &&
               $patientRisk->patient;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('doctor');
    }

    public function update(User $user, PatientRisk $patientRisk): bool
    {
        return $user->hasRole('doctor') &&
               $patientRisk->patient;
    }

    public function delete(User $user, PatientRisk $patientRisk): bool
    {
        return $user->role === 'doctor';
    }

    public function restore(User $user, PatientRisk $patientRisk): bool
    {
        return $user->hasRole('doctor') &&
               $patientRisk->patient;
    }

    public function forceDelete(User $user, PatientRisk $patientRisk): bool
    {
        return $user->hasRole('doctor') &&
               $patientRisk->patient;
    }
}
