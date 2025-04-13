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
        Schema::create('pregister_schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('emails');
            $table->string('schools');
            $table->string('photo');
            $table->string('SKL');
            $table->string('KTP');
            $table->string('AKTA_KELAHIRAN');
            $table->string('RAPORT');
            $table->string('NISN');
            $table->string('NPSN');
            $table->string('major');
            $table->string('PRESTASI')->nullable();
            $table->foreignUuid('users_id')->references('id')->on('users')->cascadeOnDelete();
            $table->enum('status',['PREDAFTAR', 'SISWA', 'LULUS']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pregister_schools');
    }
};
