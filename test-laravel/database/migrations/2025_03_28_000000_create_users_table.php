<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('providers_id')->nullable();
            $table->string('providers')->nullable();
            $table->string('providers_tokens')->nullable();
            $table->string('providers_refresh_tokens')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('banned_until')->nullable();
            $table->timestamps();
            $table->foreignId('role_id')->default(1)->constrained('roles')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });

        Schema::dropIfExists('users');
    }
};
