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
        Schema::table('make_up_class_confirmations', function (Blueprint $table) {
            $table->string('student_id_number')->nullable()->after('student_email'); // Student ID like 2022305792
            $table->string('student_name')->nullable()->after('student_id_number'); // Full name from CSV
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_up_class_confirmations', function (Blueprint $table) {
            $table->dropColumn(['student_id_number', 'student_name']);
        });
    }
};
