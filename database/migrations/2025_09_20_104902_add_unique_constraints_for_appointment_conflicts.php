<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, clean up any existing overlapping appointments before adding constraints
        $this->cleanUpOverlappingAppointments();

        Schema::table('appointments', function (Blueprint $table) {
            // Add unique constraints to prevent double-booking for each provider type
            // Note: These constraints will only work if the provider_id is not null

            // For doctors - prevent same doctor having overlapping appointments
            $table->unique(['doctor_id', 'appointment_date', 'appointment_time'], 'unique_doctor_appointment_time');

            // For midwives - prevent same midwife having overlapping appointments
            $table->unique(['midwife_id', 'appointment_date', 'appointment_time'], 'unique_midwife_appointment_time');

            // For BHWs - prevent same BHW having overlapping appointments
            $table->unique(['bhw_id', 'appointment_date', 'appointment_time'], 'unique_bhw_appointment_time');

            // Prevent same patient from having overlapping appointments (optional - can be removed if not needed)
            // $table->unique(['patient_id', 'appointment_date', 'appointment_time'], 'unique_patient_appointment_time');
        });
    }

    /**
     * Clean up overlapping appointments before adding constraints
     */
    private function cleanUpOverlappingAppointments(): void
    {
        // Get all appointments with overlapping times for the same provider
        $overlappingAppointments = DB::table('appointments')
            ->select('a1.id as appointment1_id', 'a2.id as appointment2_id', 'a1.appointment_date', 'a1.appointment_time')
            ->from('appointments as a1')
            ->join('appointments as a2', function($join) {
                $join->on('a1.appointment_date', '=', 'a2.appointment_date')
                     ->where(function($q) {
                         $q->where(function($q2) {
                             $q2->whereNotNull('a1.doctor_id')
                                ->whereNotNull('a2.doctor_id')
                                ->whereColumn('a1.doctor_id', 'a2.doctor_id');
                         })
                         ->orWhere(function($q2) {
                             $q2->whereNotNull('a1.midwife_id')
                                ->whereNotNull('a2.midwife_id')
                                ->whereColumn('a1.midwife_id', 'a2.midwife_id');
                         })
                         ->orWhere(function($q2) {
                             $q2->whereNotNull('a1.bhw_id')
                                ->whereNotNull('a2.bhw_id')
                                ->whereColumn('a1.bhw_id', 'a2.bhw_id');
                         });
                     });
            })
            ->where('a1.id', '<', 'a2.id')
            ->whereIn('a1.status', ['scheduled', 'confirmed'])
            ->whereIn('a2.status', ['scheduled', 'confirmed'])
            ->get();

        // Cancel overlapping appointments (keep the first one, cancel the rest)
        foreach ($overlappingAppointments as $conflict) {
            DB::table('appointments')
                ->where('id', $conflict->appointment2_id)
                ->update([
                    'status' => 'cancelled',
                    'conflict_notes' => 'Automatically cancelled due to scheduling conflict',
                    'updated_at' => now()
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropUnique('unique_doctor_appointment_time');
            $table->dropUnique('unique_midwife_appointment_time');
            $table->dropUnique('unique_bhw_appointment_time');
            // $table->dropUnique('unique_patient_appointment_time');
        });
    }
};
