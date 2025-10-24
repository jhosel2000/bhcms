<?php

namespace App\Services;

use App\Models\Appointment;
use App\Mail\AppointmentReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AppointmentEmailService
{
    /**
     * Send appointment notification email with deduplication
     */
    public function sendAppointmentEmail(
        Appointment $appointment,
        string $recipientType,
        string $emailType = 'booking'
    ): bool {
        Log::info("Attempting to send email for appointment {$appointment->id} - {$emailType} to {$recipientType}");
        try {
            // Check if this email has already been sent
            if ($appointment->hasEmailBeenSent($emailType, $recipientType)) {
                Log::info("Email already sent for appointment {$appointment->id} - {$emailType} to {$recipientType}");
                return false;
            }

            // Get recipient email based on type
            $recipientEmail = $this->getRecipientEmail($appointment, $recipientType);

            if (!$recipientEmail) {
                Log::warning("No email found for {$recipientType} in appointment {$appointment->id}");
                return false;
            }

            // Send the email
            Mail::to($recipientEmail)->send(
                new AppointmentReminderMail($appointment, $recipientType, $emailType)
            );

            // Mark email as sent
            $appointment->markEmailAsSent($emailType, $recipientType);

            Log::info("Email sent successfully for appointment {$appointment->id} - {$emailType} to {$recipientType}");
            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send email for appointment {$appointment->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send appointment emails based on the action type
     * - booking: Send to patient (confirmation) and doctor (notification)
     * - approval: Send ONLY to patient
     * - decline: Send ONLY to patient
     */
    public function sendAppointmentEmailsToAll(Appointment $appointment, string $emailType = 'booking'): array
    {
        $sentEmails = [];

        if ($emailType === 'booking') {
            // When patient books: Send to patient (confirmation) and doctor (notification)
            if ($this->sendAppointmentEmail($appointment, 'patient', $emailType)) {
                $sentEmails[] = 'patient';
            }

            // Send to assigned doctor if exists
            if ($appointment->doctor_id && $this->sendAppointmentEmail($appointment, 'doctor', $emailType)) {
                $sentEmails[] = 'doctor';
            }

            // Send to assigned midwife if exists
            if ($appointment->midwife_id && $this->sendAppointmentEmail($appointment, 'midwife', $emailType)) {
                $sentEmails[] = 'midwife';
            }

            // Send to assigned BHW if exists
            if ($appointment->bhw_id && $this->sendAppointmentEmail($appointment, 'bhw', $emailType)) {
                $sentEmails[] = 'bhw';
            }
        } elseif ($emailType === 'approval' || $emailType === 'decline') {
            // When doctor approves/declines: Send ONLY to patient
            if ($this->sendAppointmentEmail($appointment, 'patient', $emailType)) {
                $sentEmails[] = 'patient';
            }
        } else {
            // For other types (reminder, etc.), send to all parties
            if ($this->sendAppointmentEmail($appointment, 'patient', $emailType)) {
                $sentEmails[] = 'patient';
            }

            if ($appointment->doctor_id && $this->sendAppointmentEmail($appointment, 'doctor', $emailType)) {
                $sentEmails[] = 'doctor';
            }

            if ($appointment->midwife_id && $this->sendAppointmentEmail($appointment, 'midwife', $emailType)) {
                $sentEmails[] = 'midwife';
            }

            if ($appointment->bhw_id && $this->sendAppointmentEmail($appointment, 'bhw', $emailType)) {
                $sentEmails[] = 'bhw';
            }
        }

        return $sentEmails;
    }

    /**
     * Get recipient email based on type
     */
    private function getRecipientEmail(Appointment $appointment, string $recipientType): ?string
    {
        switch ($recipientType) {
            case 'patient':
                return $appointment->patient?->user?->email;

            case 'doctor':
                return $appointment->doctor?->user?->email;

            case 'midwife':
                return $appointment->midwife?->user?->email;

            case 'bhw':
                return $appointment->bhw?->user?->email;

            default:
                return null;
        }
    }

    /**
     * Resend email if needed (bypass deduplication)
     */
    public function resendAppointmentEmail(
        Appointment $appointment,
        string $recipientType,
        string $emailType = 'booking'
    ): bool {
        try {
            $recipientEmail = $this->getRecipientEmail($appointment, $recipientType);

            if (!$recipientEmail) {
                Log::warning("No email found for {$recipientType} in appointment {$appointment->id}");
                return false;
            }

            Mail::to($recipientEmail)->send(
                new AppointmentReminderMail($appointment, $recipientType, $emailType)
            );

            Log::info("Email resent for appointment {$appointment->id} - {$emailType} to {$recipientType}");
            return true;

        } catch (\Exception $e) {
            Log::error("Failed to resend email for appointment {$appointment->id}: " . $e->getMessage());
            return false;
        }
    }
}