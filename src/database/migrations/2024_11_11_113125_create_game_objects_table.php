<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_objects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->boolean('active')->default(true);
            $table->foreignId('game_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('game_object_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('state_component_id')->nullable()->constrained('components')->onDelete('cascade');
            $table->float('x')->default(0);
            $table->float('y')->default(0);
            $table->float('z')->default(0);
            $table->float('scale_x')->default(1);
            $table->float('scale_y')->default(1);
            $table->float('scale_z')->default(1);
            $table->float('rotation_x')->default(0);
            $table->float('rotation_y')->default(0);
            $table->float('rotation_z')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_objects');
    }
};
