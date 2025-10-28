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
            $table->boolean('attended')->default(false)->after('reason');
            $table->timestamp('confirmation_date')->nullable()->after('attended');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_up_class_confirmations', function (Blueprint $table) {
            $table->dropColumn(['attended', 'confirmation_date']);
        });
    }
};
