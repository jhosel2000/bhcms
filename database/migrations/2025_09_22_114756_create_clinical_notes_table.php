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
        Schema::create('clinical_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->string('note_type')->default('progress'); // progress, consultation, discharge, etc.
            $table->timestamp('visit_date');
            $table->text('subjective')->nullable(); // patient's reported symptoms
            $table->text('objective')->nullable(); // physical exam findings, vital signs
            $table->text('assessment')->nullable(); // doctor's diagnosis/assessment
            $table->text('plan')->nullable(); // treatment plan, follow-up
            $table->text('additional_notes')->nullable();
            $table->string('status')->default('final'); // draft, final, amended
            $table->json('vital_signs')->nullable(); // blood pressure, temperature, etc.
            $table->text('physical_exam')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinical_notes');
    }
};
