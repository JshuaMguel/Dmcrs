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
        Schema::table('make_up_class_requests', function (Blueprint $table) {
            $table->text('chair_remarks')->nullable();
            $table->text('head_remarks')->nullable();
        });
        
        // For PostgreSQL compatibility: Handle enum change separately
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE make_up_class_requests DROP CONSTRAINT IF EXISTS make_up_class_requests_status_check");
            DB::statement("ALTER TABLE make_up_class_requests ALTER COLUMN status TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE make_up_class_requests ADD CONSTRAINT make_up_class_requests_status_check CHECK (status IN ('pending', 'CHAIR_APPROVED', 'CHAIR_REJECTED', 'HEAD_REJECTED', 'APPROVED', 'REJECTED', 'declined'))");
        } else {
            // MySQL version
            Schema::table('make_up_class_requests', function (Blueprint $table) {
                $table->enum('status', [
                    'pending',
                    'CHAIR_APPROVED',
                    'CHAIR_REJECTED',
                    'HEAD_REJECTED',
                    'APPROVED',
                    'REJECTED',
                    'declined'
                ])->default('pending')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_up_class_requests', function (Blueprint $table) {
            $table->enum('status', ['pending', 'CHAIR_APPROVED', 'CHAIR_REJECTED', 'HEAD_REJECTED', 'APPROVED'])->default('pending')->change();
        });
    }
};
