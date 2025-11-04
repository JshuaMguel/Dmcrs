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
            $table->string('section', 200)->nullable()->change(); // Increase and allow nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_up_class_requests', function (Blueprint $table) {
            $table->string('section', 100)->nullable()->change(); // Revert back to 100
        });
    }
};
