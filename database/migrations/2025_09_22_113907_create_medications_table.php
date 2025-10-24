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
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('medication_name');
            $table->string('dosage'); // e.g., "500mg", "10ml", "1 tablet"
            $table->string('frequency'); // e.g., "twice daily", "every 6 hours", "as needed"
            $table->string('route')->default('oral'); // oral, injection, topical, etc.
            $table->string('status')->default('active'); // active, discontinued, completed
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->string('prescribed_by')->nullable(); // doctor name
            $table->text('indication')->nullable(); // reason for medication
            $table->text('instructions')->nullable(); // special instructions
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
