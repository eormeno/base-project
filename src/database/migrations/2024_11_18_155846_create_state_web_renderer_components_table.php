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
        Schema::create('state_web_renderer_components', function (Blueprint $table) {
            $table->foreignId('id')->nullable()->constrained('components')->onDelete('cascade');
            $table->string('rendered_state');
            $table->string('slot');
            $table->string('view')->default('default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('state_web_renderer_components');
    }
};
