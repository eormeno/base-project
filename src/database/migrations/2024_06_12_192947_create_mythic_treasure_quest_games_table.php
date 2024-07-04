<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('mythic_treasure_quest_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('state')->nullable()->default(null);
            $table->integer('level')->default(1);
            $table->integer('health')->default(100);
            $table->json('map')->nullable();
            $table->json('inventory')->nullable();
            $table->boolean('is_finished')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mythic_treasure_quest_games');
    }
};
