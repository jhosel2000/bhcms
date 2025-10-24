<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $recipientType;
    public $emailType;

    /**
     * Create a new message instance.
     *
     * @param Appointment $appointment
     * @param string $recipientType
     * @param string $emailType
     */
    public function __construct(Appointment $appointment, string $recipientType = 'patient', string $emailType = 'reminder')
    {
        $this->appointment = $appointment;
        $this->recipientType = $recipientType;
        $this->emailType = $emailType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Determine subject and view based on email type
        $subject = '';
        $view = '';

        switch ($this->emailType) {
            case 'booking':
                $subject = $this->recipientType === 'doctor' 
                    ? 'New Appointment Request - Action Required' 
                    : 'Appointment Request Submitted';
                $view = 'emails.appointment_booking';
                break;

            case 'approval':
                $subject = 'Appointment Approved - Confirmed';
                $view = 'emails.appointment_approved';
                break;

            case 'decline':
                $subject = 'Appointment Update - Unable to Approve';
                $view = 'emails.appointment_declined';
                break;

            case 'reminder':
                $subject = 'Appointment Reminder';
                $view = 'emails.appointment_reminder';
                break;

            default:
                $subject = 'Appointment Notification';
                $view = 'emails.appointment_reminder';
                break;
        }

        return $this->subject($subject)->view($view);
    }
}
