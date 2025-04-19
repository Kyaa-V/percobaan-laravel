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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_profile')->nullable();
            $table->string('roll_number');
            $table->string('no-absen'); 
            $table->string('status_at-leatest')->default('offline');
            $table->foreignId('class_id')->references('id')->on('classes')->cascadeOnDelete();
            $table->foreignId('majors_id')->references('id')->on('majors')->cascadeOnDelete();
            $table->foreignUuid('users_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignid('pregister_id')->references('id')->on('pregister_schools')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
