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
        Schema::create('game_data_components', function (Blueprint $table) {
            $table->id();
            $table->string('active_on_state')->default('allways');
            $table->morphs('componentable');
            $table->integer('score')->default(0);
            $table->integer('times_played')->default(0);
            $table->integer('max_attempts');
            $table->integer('min_number');
            $table->integer('max_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_data_components');
    }
};
