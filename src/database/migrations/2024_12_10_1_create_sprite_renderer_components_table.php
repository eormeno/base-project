<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sprite_renderer_components', function (Blueprint $table) {
            $table->id(); // PK and FK to components
            $table->foreign('id')->references('id')->on('components')->onDelete('cascade');
            $table->string('texture');
            $table->integer('layer')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sprite_renderer_components');
    }
};
