<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('make_up_class_request_id');
            $table->unsignedBigInteger('chair_id');
            $table->enum('decision', ['recommended', 'rejected']);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->foreign('make_up_class_request_id')->references('id')->on('make_up_class_requests')->onDelete('cascade');
            $table->foreign('chair_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
