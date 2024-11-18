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
            $table->foreignId('game_object_id')->constrained()->onDelete('cascade');
            $table->string('component_type')->unique()->index();
            $table->morphs('componentable');
            $table->json('properties')->nullable();
            $table->boolean('active')->default(true);
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
