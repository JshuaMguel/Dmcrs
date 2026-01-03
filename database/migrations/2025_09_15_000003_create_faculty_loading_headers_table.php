<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculty_loading_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('restrict');
            $table->enum('semester', ['1st', '2nd', 'summer']);
            $table->string('school_year');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('restrict');
            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['department_id', 'semester', 'school_year'], 'flh_dept_sem_sy_unique');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faculty_loading_headers');
    }
};
