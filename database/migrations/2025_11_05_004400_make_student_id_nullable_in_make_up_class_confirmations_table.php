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
            // Drop the foreign key constraint first
            $table->dropForeign(['student_id']);
            
            // Modify the column to be nullable
            $table->unsignedBigInteger('student_id')->nullable()->change();
            
            // Re-add the foreign key constraint with nullable support
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_up_class_confirmations', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['student_id']);
            
            // Make the column not nullable again
            $table->unsignedBigInteger('student_id')->nullable(false)->change();
            
            // Re-add the foreign key constraint
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
