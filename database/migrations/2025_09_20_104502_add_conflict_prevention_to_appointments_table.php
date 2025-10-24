<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Add duration and buffer time fields
            $table->integer('duration_minutes')->default(30)->after('appointment_time'); // Default 30 minutes
            $table->integer('buffer_minutes_before')->default(0)->after('duration_minutes'); // Buffer before appointment
            $table->integer('buffer_minutes_after')->default(15)->after('buffer_minutes_before'); // Buffer after appointment

            // Add conflict tracking fields
            $table->boolean('is_confirmed')->default(false)->after('status'); // Track if appointment is confirmed
            $table->timestamp('confirmed_at')->nullable()->after('is_confirmed'); // When it was confirmed
            $table->text('conflict_notes')->nullable()->after('confirmed_at'); // Notes about any conflicts

            // Add indexes for performance
            $table->index(['doctor_id', 'appointment_date', 'appointment_time'], 'idx_doctor_availability');
            $table->index(['midwife_id', 'appointment_date', 'appointment_time'], 'idx_midwife_availability');
            $table->index(['bhw_id', 'appointment_date', 'appointment_time'], 'idx_bhw_availability');
            $table->index(['appointment_date', 'appointment_time'], 'idx_appointment_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Remove added fields
            $table->dropColumn([
                'duration_minutes',
                'buffer_minutes_before',
                'buffer_minutes_after',
                'is_confirmed',
                'confirmed_at',
                'conflict_notes'
            ]);

            // Drop indexes
            $table->dropIndex('idx_doctor_availability');
            $table->dropIndex('idx_midwife_availability');
            $table->dropIndex('idx_bhw_availability');
            $table->dropIndex('idx_appointment_datetime');
        });
    }
};
