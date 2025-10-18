<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('make_up_class_requests', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('faculty_id'); // Foreign Key
            $table->string('subject', 100);
             $table->string('room', 100);
            $table->text('reason');
            $table->date('preferred_date');
            $table->time('preferred_time');
            $table->enum('status', ['pending', 'CHAIR_APPROVED', 'CHAIR_REJECTED', 'HEAD_REJECTED', 'APPROVED'])->default('pending');
            $table->string('attachment')->nullable();
            $table->string('tracking_number')->unique();
            $table->timestamps(); // created_at & updated_at

            // Foreign key reference to users (faculty)
            $table->foreign('faculty_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('make_up_class_requests');
    }
};
