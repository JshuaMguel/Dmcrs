<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculty_loading_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_loading_header_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('users')->onDelete('restrict');
            $table->string('subject_code');
            $table->string('section');
            $table->string('day_of_week');
            $table->time('time_start');
            $table->time('time_end');
            $table->string('room');
            $table->decimal('units', 5, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('faculty_loading_header_id');
            $table->index('instructor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faculty_loading_details');
    }
};
