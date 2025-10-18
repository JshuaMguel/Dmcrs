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
        Schema::table('make_up_class_requests', function (Blueprint $table) {
            $table->text('chair_remarks')->nullable();
            $table->text('head_remarks')->nullable();
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
