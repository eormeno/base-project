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
        Schema::create('game_apps', function (Blueprint $table) {
            $table->id();
            $table->string('prefix', 3)->unique();
            $table->string('name');
            $table->text('description');
            $table->integer('min_age')->default(18);
            $table->string('image')->nullable();
            $table->string('prefab_name')->nullable();
            $table->string('version')->nullable();
            $table->integer('max_instances_per_user')->default(1);
            $table->integer('min_users_per_instance')->default(1);
            $table->integer('max_users_per_instance')->default(1);
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_apps');
    }
};
