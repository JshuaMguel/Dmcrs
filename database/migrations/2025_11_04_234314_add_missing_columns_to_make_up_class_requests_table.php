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
            $table->unsignedBigInteger('department_id')->nullable()->after('faculty_id');
            $table->string('section', 100)->nullable()->after('section_id');
            $table->string('semester', 50)->default('Current')->after('section');
            
            // Add foreign key constraint
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_up_class_requests', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'section', 'semester']);
        });
    }
};
