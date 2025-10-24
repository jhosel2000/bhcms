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
            // Remove unused conflict prevention fields
            $table->dropColumn([
                'duration_minutes',
                'buffer_minutes_before',
                'buffer_minutes_after',
                'is_confirmed',
                'confirmed_at',
                'conflict_notes'
            ]);

            // Remove redundant is_urgent boolean field, keep urgency_level enum
            $table->dropColumn('is_urgent');

            // Drop unused indexes
            $table->dropIndex('idx_doctor_availability');
            $table->dropIndex('idx_midwife_availability');
            $table->dropIndex('idx_bhw_availability');
            $table->dropIndex('idx_appointment_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Re-add removed fields for rollback
            $table->integer('duration_minutes')->default(30)->after('appointment_time');
            $table->integer('buffer_minutes_before')->default(0)->after('duration_minutes');
            $table->integer('buffer_minutes_after')->default(15)->after('buffer_minutes_before');
            $table->boolean('is_confirmed')->default(false)->after('status');
            $table->timestamp('confirmed_at')->nullable()->after('is_confirmed');
            $table->text('conflict_notes')->nullable()->after('confirmed_at');
            $table->boolean('is_urgent')->default(false)->after('created_by_role');

            // Re-add indexes
            $table->index(['doctor_id', 'appointment_date', 'appointment_time'], 'idx_doctor_availability');
            $table->index(['midwife_id', 'appointment_date', 'appointment_time'], 'idx_midwife_availability');
            $table->index(['bhw_id', 'appointment_date', 'appointment_time'], 'idx_bhw_availability');
            $table->index(['appointment_date', 'appointment_time'], 'idx_appointment_datetime');
        });
    }
};
