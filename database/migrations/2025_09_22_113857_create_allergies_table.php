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
        Schema::create('allergies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('allergen_type'); // medication, food, environmental, other
            $table->string('allergen_name'); // specific name of the allergen
            $table->string('severity'); // mild, moderate, severe, life_threatening
            $table->text('reaction_description')->nullable();
            $table->string('status')->default('active'); // active, resolved, inactive
            $table->timestamp('first_occurrence')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergies');
    }
};
