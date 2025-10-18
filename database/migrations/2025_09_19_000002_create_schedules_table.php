<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('semester');
            $table->string('day_of_week');
            $table->time('time_start');
            $table->time('time_end');
            $table->string('subject_code');
            $table->string('section');
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->string('instructor_name')->nullable();
            $table->string('room');
            $table->unsignedBigInteger('department_id');
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('instructor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
