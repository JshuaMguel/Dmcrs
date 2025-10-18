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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('role');
                $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            }
            if (Schema::hasColumn('users', 'department')) {
                $table->dropColumn('department');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'department_id')) {
                try {
                    $table->dropForeign(['department_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, ignore error
                }
                $table->dropColumn('department_id');
            }
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('role');
            }
        });
    }
};
