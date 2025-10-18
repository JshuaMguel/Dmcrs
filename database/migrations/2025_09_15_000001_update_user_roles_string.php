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
        Schema::table('users', function (Blueprint $table) {
            // First, remove the enum constraint
            $table->string('role', 20)->change();
        });

    // Update existing roles
    // DB::table('users')->where('role', 'admin')->update(['role' => 'academic_head']);
    // DB::table('users')->where('role', 'department_head')->update(['role' => 'department_chair']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    // Update roles back
    // DB::table('users')->where('role', 'academic_head')->update(['role' => 'admin']);
    // DB::table('users')->where('role', 'department_chair')->update(['role' => 'department_head']);
    }
};
