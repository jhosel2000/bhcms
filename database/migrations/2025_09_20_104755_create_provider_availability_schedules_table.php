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
        Schema::create('provider_availability_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('midwife_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('bhw_id')->nullable()->constrained('bhws')->onDelete('cascade');
            $table->string('day_of_week'); // monday, tuesday, etc.
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('slot_duration_minutes')->default(30); // Duration of each appointment slot
            $table->integer('buffer_minutes')->default(15); // Buffer time between appointments
            $table->boolean('is_available')->default(true);
            $table->integer('max_appointments_per_day')->default(20); // Maximum appointments per day
            $table->json('break_times')->nullable(); // Store break times as JSON
            $table->text('notes')->nullable();
            $table->timestamps();

            // Note: We'll handle provider type validation in the application logic

            // Indexes for performance
            $table->index(['doctor_id', 'day_of_week'], 'idx_doctor_schedule');
            $table->index(['midwife_id', 'day_of_week'], 'idx_midwife_schedule');
            $table->index(['bhw_id', 'day_of_week'], 'idx_bhw_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_availability_schedules');
    }
};
