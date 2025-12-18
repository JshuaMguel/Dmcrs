<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id_number')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email')->unique();
            $table->foreignId('department_id')->constrained()->onDelete('restrict');
            $table->integer('year_level');
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('restrict');
            $table->enum('status', ['active', 'inactive', 'graduated', 'dropped'])->default('active');
            $table->string('contact_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('student_id_number');
            $table->index('department_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
