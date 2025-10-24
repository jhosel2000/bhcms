<?php

namespace App\Policies;

use App\Models\MaternalCareRecord;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MaternalCareRecordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'midwife';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MaternalCareRecord $maternalCareRecord): bool
    {
        if ($user->role === 'midwife') {
            // Allow midwife to view any maternal care record
            return true;
        } elseif ($user->role === 'patient') {
            return $user->patient && $user->patient->id === $maternalCareRecord->patient_id;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'midwife';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MaternalCareRecord $maternalCareRecord): bool
    {
        if ($user->role === 'midwife') {
            // Allow midwife to update any maternal care record
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MaternalCareRecord $maternalCareRecord): bool
    {
        if ($user->role === 'midwife') {
            // Allow midwife to delete any maternal care record
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MaternalCareRecord $maternalCareRecord): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MaternalCareRecord $maternalCareRecord): bool
    {
        return false;
    }
}
