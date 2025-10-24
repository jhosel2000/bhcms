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
        Schema::create('maternal_care_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('midwife_id')->constrained('midwives')->onDelete('cascade');
            $table->enum('type', ['prenatal', 'postnatal', 'maternal_health', 'vitals', 'checkup', 'followup']);
            $table->date('visit_date');
            $table->time('visit_time')->nullable();
            $table->text('notes')->nullable();
            // Vital signs
            $table->decimal('blood_pressure_systolic', 5, 2)->nullable();
            $table->decimal('blood_pressure_diastolic', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable(); // in kg
            $table->decimal('height', 5, 2)->nullable(); // in cm
            $table->integer('heart_rate')->nullable(); // bpm
            $table->decimal('temperature', 4, 2)->nullable(); // in Celsius
            $table->text('additional_findings')->nullable();
            $table->date('next_visit_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maternal_care_records');
    }
};
