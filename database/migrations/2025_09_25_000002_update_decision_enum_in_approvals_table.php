<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Change enum values for decision column in approvals table
        Schema::table('approvals', function (Blueprint $table) {
            $table->enum('decision', ['recommended', 'rejected', 'approved'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->enum('decision', ['recommended', 'rejected'])->change();
        });
    }
};
