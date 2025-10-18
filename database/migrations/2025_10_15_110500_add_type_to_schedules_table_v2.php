<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('schedules', 'type')) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->string('type', 20)->default('REGULAR')->after('status');
            });

            // Backfill: mark likely make-up classes based on text hints
            DB::table('schedules')
                ->where(function($q) {
                    $q->whereRaw("LOWER(subject_title) LIKE '%make-up%'")
                      ->orWhereRaw("LOWER(subject_title) LIKE '%make up%'")
                      ->orWhereRaw("LOWER(`section`) LIKE '%make-up%'")
                      ->orWhereRaw("LOWER(`section`) LIKE '%make up%'");
                })
                ->update(['type' => 'MAKEUP']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('schedules', 'type')) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
