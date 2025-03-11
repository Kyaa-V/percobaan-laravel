<?php

use Illuminate\Support\Facades\DB;
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
            $table->string('password');
            $table->timestamps();
            $table->unsignedBigInteger('role_id')->default(DB::raw('1'));
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

    }
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};