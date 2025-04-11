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
        Schema::create('education', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('graduation_years');
            $table->enum('schools',[
                'SMA/SMK','PERGURUAN TINGGI', 'SMP', 'SD'
            ]);
            $table->string('name_schools');
            $table->enum('type_schools',[
                'SWASTA','NEGERI'
            ]);
            $table->string('average_value');
            $table->string('diploma_date');
            $table->string('major');
            $table->foreignUuid('users_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
