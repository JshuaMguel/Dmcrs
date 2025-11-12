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
            // Change from string to text to support JSON array
            $table->text('proof_of_conduct')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_up_class_requests', function (Blueprint $table) {
            // Revert back to string (though this might lose data if JSON was stored)
            $table->string('proof_of_conduct')->nullable()->change();
        });
    }
};
