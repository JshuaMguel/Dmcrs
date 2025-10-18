<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->string('position')->nullable()->after('chair_id');
            $table->enum('status', ['approved', 'rejected', 'pending'])->default('pending')->after('decision');
            $table->boolean('is_final')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->dropColumn(['position', 'status', 'is_final']);
        });
    }
};
