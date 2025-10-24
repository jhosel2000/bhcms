<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update existing status values to match new enum
        DB::statement("UPDATE appointments SET status = 'pending' WHERE status = 'scheduled'");
        DB::statement("UPDATE appointments SET status = 'completed' WHERE status = 'confirmed'");
        DB::statement("UPDATE appointments SET status = 'declined' WHERE status = 'cancelled'");

        // For PostgreSQL, we need to use raw SQL to change the enum
        DB::statement("ALTER TABLE appointments ALTER COLUMN status TYPE VARCHAR(255)");
        DB::statement("ALTER TABLE appointments ADD CONSTRAINT status_check CHECK (status IN ('pending', 'approved', 'declined', 'completed', 'expired'))");
        DB::statement("ALTER TABLE appointments ALTER COLUMN status SET NOT NULL");
        DB::statement("ALTER TABLE appointments ALTER COLUMN status SET DEFAULT 'pending'");
        DB::statement("ALTER TABLE appointments ALTER COLUMN status DROP IDENTITY IF EXISTS");

        Schema::table('appointments', function (Blueprint $table) {
            // Add new columns
            $table->json('uploaded_files')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('declined_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Revert status enum
            $table->enum('status', ['scheduled', 'confirmed', 'completed', 'cancelled'])->default('scheduled')->change();

            // Drop new columns
            $table->dropColumn(['uploaded_files', 'approved_at', 'completed_at', 'declined_reason']);
        });
    }
};
