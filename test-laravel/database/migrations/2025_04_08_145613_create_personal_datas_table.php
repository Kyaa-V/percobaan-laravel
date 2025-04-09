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
        Schema::create('personal_datas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('birthdays');
            $table->string('your_address');
            $table->string('name_country');
            $table->string('name_province');
            $table->string('name_city');
            $table->string('numberOfPhone');
            $table->string('code');
            $table->mediumInteger('city_id')->unsigned()->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->cascadeOnDelete();
            $table->foreignUuid('users_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_datas');
    }
};
