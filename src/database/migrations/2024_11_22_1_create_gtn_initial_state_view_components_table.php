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
        Schema::create('gtn_initial_state_view_components', function (Blueprint $table) {
            $table->id();
            $table->foreign('id')->references('id')->on('components')->onDelete('cascade');
            $table->json('messages')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gtn_initial_state_view_components');
    }
};
