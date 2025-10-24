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
        Schema::table('ehr_records', function (Blueprint $table) {
            $table->string('status')->default('pending_review')->after('appointment_id');
            $table->foreignId('reviewed_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->text('review_notes')->nullable()->after('reviewed_by');
            $table->timestamp('reviewed_at')->nullable()->after('review_notes');

            $table->index('status');
            $table->index('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ehr_records', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['reviewed_by']);

            $table->dropForeign(['reviewed_by']);

            $table->dropColumn(['status', 'reviewed_by', 'review_notes', 'reviewed_at']);
        });
    }
};