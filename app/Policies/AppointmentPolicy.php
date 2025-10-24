<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['patient', 'doctor', 'midwife', 'bhw']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        // Patients: only their own appointments
        if ($user->hasRole('patient') && $user->patient) {
            return $appointment->patient_id === $user->patient->id;
        }

        // Doctor: appointments assigned to them
        if ($user->hasRole('doctor') && $user->doctor) {
            return $appointment->doctor_id === $user->doctor->id;
        }

        // Midwife: limited to assigned patients only
        if ($user->hasRole('midwife') && $user->midwife) {
            return $appointment->patient && $appointment->patient->barangay === $user->midwife->area_of_assignment;
        }

        // BHW: limited to assigned patients only
        if ($user->hasRole('bhw') && $user->bhw) {
            return $appointment->patient && $appointment->patient->barangay === $user->bhw->barangay_id_number;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['patient', 'doctor', 'midwife', 'bhw']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        // Patients: can update/cancel only their own pending appointments
        if ($user->hasRole('patient') && $user->patient) {
            return $appointment->patient_id === $user->patient->id && $appointment->status === 'pending';
        }

        // Doctors: can update details for appointments assigned to them
        if ($user->hasRole('doctor') && $user->doctor) {
            return $appointment->doctor_id === $user->doctor->id;
        }

        // Midwives: can schedule/reschedule if granted (pending and assigned to them)
        if ($user->hasRole('midwife') && $user->midwife) {
            return $appointment->midwife_id === $user->midwife->id && $appointment->status === 'pending';
        }

        // BHWs: can schedule/reschedule only pending and assigned to them
        if ($user->hasRole('bhw') && $user->bhw) {
            return $appointment->bhw_id === $user->bhw->id && $appointment->status === 'pending';
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        // Patients: can cancel (delete) only their own pending appointments
        if ($user->hasRole('patient') && $user->patient) {
            return $appointment->patient_id === $user->patient->id && $appointment->status === 'pending';
        }

        // Doctors: may delete own assigned appointments if needed
        if ($user->hasRole('doctor') && $user->doctor) {
            return $appointment->doctor_id === $user->doctor->id;
        }

        // Midwives/BHWs: can delete only pending appointments assigned to them
        if ($user->hasRole('midwife') && $user->midwife) {
            return $appointment->midwife_id === $user->midwife->id && $appointment->status === 'pending';
        }
        if ($user->hasRole('bhw') && $user->bhw) {
            return $appointment->bhw_id === $user->bhw->id && $appointment->status === 'pending';
        }

        return false;
    }

    /**
     * Determine whether the user can update appointment status.
     */
    public function updateStatus(User $user, Appointment $appointment): bool
    {
        // Only Doctor can approve/decline/complete (final authority)
        if ($user->hasRole('doctor') && $user->doctor) {
            return $appointment->doctor_id === $user->doctor->id;
        }

        // Others cannot change status
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return false; // Soft deletes not implemented for appointments
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return false; // Force delete not allowed
    }
}
