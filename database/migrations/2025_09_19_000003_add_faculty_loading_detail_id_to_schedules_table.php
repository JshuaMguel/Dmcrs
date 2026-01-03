<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->foreignId('faculty_loading_detail_id')->nullable()->after('status')->constrained()->onDelete('set null');
            $table->index('faculty_loading_detail_id');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['faculty_loading_detail_id']);
            $table->dropIndex(['faculty_loading_detail_id']);
            $table->dropColumn('faculty_loading_detail_id');
        });
    }
};
