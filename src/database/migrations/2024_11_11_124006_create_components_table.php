<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_object_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->boolean('active')->default(true);
            $table->boolean('awoke')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('components');
    }
};
