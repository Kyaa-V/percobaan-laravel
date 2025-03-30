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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->uuid('authors_id');
            $table->text('content');
            $table->uuid('users_id');
            $table->integer('parent_id')->nullable();
            $table->timestamps();
            $table->foreign('authors_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('users_id')->references('id')->on('users')->cascadeOnDelete();
     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
