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
        Schema::create('gtn_game_data_components', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(true);
            $table->morphs('componentable');
            $table->integer('number_to_guess')->nullable();
            $table->integer('times_played')->default(0);
            $table->integer('score')->default(0);
            $table->integer('max_attempts');
            $table->integer('min_number');
            $table->integer('max_number');
            $table->integer('attempts')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gtn_game_data_components');
    }
};
