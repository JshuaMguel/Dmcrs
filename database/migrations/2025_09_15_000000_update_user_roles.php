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
    // First, update existing roles to prevent any constraint violations
    // DB::table('users')->where('role', 'admin')->update(['role' => 'academic_head']);
    // DB::table('users')->where('role', 'department_head')->update(['role' => 'department_chair']);

    // Now modify the enum values
    // DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('academic_head', 'department_chair', 'faculty') NOT NULL DEFAULT 'faculty'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    // First, update back to old roles
    // DB::table('users')->where('role', 'academic_head')->update(['role' => 'admin']);
    // DB::table('users')->where('role', 'department_chair')->update(['role' => 'department_head']);

    // Now modify the enum values back
    // DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'department_head', 'faculty') NOT NULL DEFAULT 'faculty'");
    }
};
