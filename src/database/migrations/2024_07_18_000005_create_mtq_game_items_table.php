<?php

// Path: src/database/migrations/2024_07_18_000005_create_mtq_games_items_table.php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {

    public function up()
    {
        Schema::create('mtq_game_items', function (Blueprint $table) {
            $table->id();
            $table->string('state')->nullable()->default(null);
            $table->dateTime('entered_at')->nullable()->default(null);
            $table->json('children')->nullable()->default(null);
            $table->json('attributes')->nullable()->default(null);
            $table->foreignId('mtq_inventory_id')->constrained()->onDelete('cascade');
            $table->foreignId('mtq_item_class_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mtq_game_items');
    }
};
