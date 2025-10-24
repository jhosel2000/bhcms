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
        Schema::create('ehr_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('created_by_role'); // 'doctor', 'midwife', 'bhw', 'patient', 'system'
            $table->string('record_type'); // 'appointment', 'diagnosis', 'prescription', 'visit_note', 'vital_signs', 'lab_result', 'file_upload', 'personal_note', 'referral', 'observation', 'vaccination', 'procedure', 'other'
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->json('attachments')->nullable(); // Array of file paths with metadata
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            $table->index(['patient_id', 'created_at']);
            $table->index(['created_by_role']);
            $table->index(['record_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ehr_records');
    }
};
