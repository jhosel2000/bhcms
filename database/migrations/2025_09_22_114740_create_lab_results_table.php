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
        Schema::create('lab_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('test_name'); // e.g., "Complete Blood Count", "Blood Glucose"
            $table->string('test_code')->nullable(); // e.g., "CBC", "GLU"
            $table->string('category'); // e.g., "hematology", "chemistry", "microbiology"
            $table->text('result_value'); // actual result value
            $table->string('unit')->nullable(); // e.g., "mg/dL", "cells/μL"
            $table->string('reference_range')->nullable(); // normal range
            $table->string('status')->default('normal'); // normal, abnormal, critical
            $table->text('interpretation')->nullable(); // doctor's interpretation
            $table->timestamp('test_date');
            $table->timestamp('result_date')->nullable();
            $table->string('performed_by')->nullable(); // lab technician
            $table->string('ordered_by')->nullable(); // doctor who ordered
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_results');
    }
};
