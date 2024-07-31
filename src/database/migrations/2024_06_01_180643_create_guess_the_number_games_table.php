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
        Schema::create('guess_the_number_games', function (Blueprint $table) {
            $table->id();
            $table->string('state')->nullable()->default(null);
            $table->dateTime('entered_at')->nullable()->default(null);
            $table->json('children')->nullable()->default(null);
            $table->text('view')->nullable()->default(null);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('times_played')->default(0);
            $table->integer('max_attempts');
            $table->integer('half_attempts');
            $table->integer('min_number');
            $table->integer('max_number');
            $table->integer('remaining_attempts');
            $table->integer('random_number')->nullable()->default(null);
            $table->integer('score')->default(0);
            $table->boolean('finished')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guess_the_number_games');
    }
};
