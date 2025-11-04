<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Handle enum change for PostgreSQL vs MySQL
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE approvals DROP CONSTRAINT IF EXISTS approvals_decision_check");
            DB::statement("ALTER TABLE approvals ALTER COLUMN decision TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE approvals ADD CONSTRAINT approvals_decision_check CHECK (decision IN ('recommended', 'rejected', 'approved'))");
        } else {
            // MySQL version
            Schema::table('approvals', function (Blueprint $table) {
                $table->enum('decision', ['recommended', 'rejected', 'approved'])->change();
            });
        }
    }

    public function down(): void
    {
        // Handle enum rollback for PostgreSQL vs MySQL
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE approvals DROP CONSTRAINT IF EXISTS approvals_decision_check");
            DB::statement("ALTER TABLE approvals ALTER COLUMN decision TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE approvals ADD CONSTRAINT approvals_decision_check CHECK (decision IN ('recommended', 'rejected'))");
        } else {
            // MySQL version
            Schema::table('approvals', function (Blueprint $table) {
                $table->enum('decision', ['recommended', 'rejected'])->change();
            });
        }
    }
};
