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
        Schema::create('gtn_showing_clue_state_components', function (Blueprint $table) {
            $table->id(); // PK and FK to components
            $table->foreign('id')->references('id')->on('components')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gtn_showing_clue_state_components');
    }
};
