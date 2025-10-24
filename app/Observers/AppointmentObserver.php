<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Services\AppointmentEmailService;
use App\Services\EhrService;

class AppointmentObserver
{
    protected EhrService $ehrService;
    protected AppointmentEmailService $appointmentEmailService;

    public function __construct(
        EhrService $ehrService,
        AppointmentEmailService $appointmentEmailService
    ) {
        $this->ehrService = $ehrService;
        $this->appointmentEmailService = $appointmentEmailService;
    }

    /**
     * Handle the Appointment "created" event.
     *
     * When a new appointment is created, send emails to all relevant parties.
     */
    public function created(Appointment $appointment): void
    {
        // Send booking confirmation and notification emails
        $this->appointmentEmailService->sendAppointmentEmailsToAll($appointment, 'booking');
    }

    /**
     * Handle the Appointment "updated" event.
     */
    public function updated(Appointment $appointment): void
    {
        // Check if appointment was just completed
        if ($appointment->status === 'completed' && $appointment->wasChanged('status')) {
            // Auto-create EHR record for completed appointment
            $this->ehrService->syncAppointmentToEhr($appointment);
        }
    }

    /**
     * Handle the Appointment "deleted" event.
     */
    public function deleted(Appointment $appointment): void
    {
        // Nothing to do on deletion
    }

    /**
     * Handle the Appointment "restored" event.
     */
    public function restored(Appointment $appointment): void
    {
        // Nothing to do on restoration
    }

    /**
     * Handle the Appointment "force deleted" event.
     */
    public function forceDeleted(Appointment $appointment): void
    {
        // Nothing to do on force deletion
    }
}
