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
        Schema::create('patient_risks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('risk_type'); // allergy, condition, device, social, other
            $table->string('title'); // e.g., "Insulin-dependent diabetes", "Pacemaker"
            $table->string('severity')->default('moderate'); // low, moderate, high, critical
            $table->text('description');
            $table->string('status')->default('active'); // active, resolved, inactive
            $table->timestamp('identified_date');
            $table->timestamp('review_date')->nullable();
            $table->string('identified_by')->nullable(); // doctor who identified
            $table->text('management_plan')->nullable(); // how to manage this risk
            $table->text('notes')->nullable();
            $table->boolean('requires_alert')->default(true); // show prominently in alerts
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_risks');
    }
};
