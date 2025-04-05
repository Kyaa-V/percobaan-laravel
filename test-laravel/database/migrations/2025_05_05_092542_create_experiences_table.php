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
        Schema::create('experiences', function (Blueprint $table) {
            $table->uuid('id')->primary();  
            $table->string('position');
            $table->string('company');
            $table->string('location');
            $table->enum('status',[
                'Internship','Pegawai Kontrak', 'Pegawai Tetap'
            ]);
            $table->string('your_skills');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            $table->foreignUuid('users_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
