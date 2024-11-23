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
            $table->id(); // PK and FK to components
            $table->foreign('id')->references('id')->on('components')->onDelete('cascade');
            $table->integer('random_number')->nullable();
            $table->integer('min_number')->default(1);
            $table->integer('max_number')->default(1024);
            $table->integer('attempts')->default(0);
            $table->integer('max_attempts')->default(10);
            $table->integer('score')->default(0);
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
