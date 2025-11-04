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
        // Fix admin user role from 'super_admin' to 'admin'
        DB::table('users')
            ->where('email', 'admin@ustp.edu.ph')
            ->update(['role' => 'admin']);
            
        echo "Fixed admin user role to 'admin'";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert admin user role back to 'super_admin'
        DB::table('users')
            ->where('email', 'admin@ustp.edu.ph')
            ->update(['role' => 'super_admin']);
    }
};
