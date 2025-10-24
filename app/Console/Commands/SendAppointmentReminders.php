<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Services\AppointmentEmailService;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-appointment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for upcoming appointments';

    /**
     * Execute the console command.
     */
    public function handle(AppointmentEmailService $emailService)
    {
        $this->info('Sending appointment reminders...');

        $appointments = Appointment::where('status', 'approved')
            ->where('appointment_date', '=', Carbon::tomorrow()->toDateString())
            ->get();

        foreach ($appointments as $appointment) {
            $emailService->sendAppointmentEmailsToAll($appointment, 'reminder');
        }

        $this->info('Done.');
    }
}