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
        Schema::create('_o_auth_users', function (Blueprint $table) {
            $table->uuid();
            $table->string('providers');
            $table->int('providers_id');
            $table->string('providers_tokens');
            $table->string('providers_tokens');
            $table->string('providers_refresh_tokens');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_o_auth_users');
    }
};
