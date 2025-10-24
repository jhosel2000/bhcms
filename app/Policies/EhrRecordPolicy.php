<?php

namespace App\Policies;

use App\Models\EhrRecord;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EhrRecordPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['doctor', 'midwife', 'bhw', 'patient']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EhrRecord $ehrRecord): bool
    {
        switch ($user->role) {
            case 'doctor':
                return $ehrRecord->doctor_id === $user->doctor->id;
            case 'midwife':
                return $ehrRecord->midwife_id === $user->midwife->id;
            case 'bhw':
                return $ehrRecord->bhw_id === $user->bhw->id;
            case 'patient':
                return $ehrRecord->patient_id === $user->patient->id;
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['doctor', 'midwife', 'bhw', 'patient']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EhrRecord $ehrRecord): bool
    {
        switch ($user->role) {
            case 'doctor':
                return $ehrRecord->doctor_id === $user->doctor->id;
            case 'midwife':
                return $ehrRecord->midwife_id === $user->midwife->id;
            case 'bhw':
                return $ehrRecord->bhw_id === $user->bhw->id;
            case 'patient':
                return $ehrRecord->patient_id === $user->patient->id;
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EhrRecord $ehrRecord): bool
    {
        switch ($user->role) {
            case 'doctor':
                return $ehrRecord->doctor_id === $user->doctor->id;
            case 'midwife':
                return $ehrRecord->midwife_id === $user->midwife->id;
            case 'bhw':
                return $ehrRecord->bhw_id === $user->bhw->id;
            case 'patient':
                return $ehrRecord->patient_id === $user->patient->id;
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EhrRecord $ehrRecord): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EhrRecord $ehrRecord): bool
    {
        return false;
    }
}
