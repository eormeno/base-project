<?php

// Path: src/database/migrations/2024_07_18_000002_create_mtq_maps_table.php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {

    public function up()
    {
        Schema::create('mtq_maps', function (Blueprint $table) {
            $table->id();
            $table->string('state')->nullable()->default(null);
            $table->dateTime('entered_at')->nullable()->default(null);
            $table->json('children')->nullable()->default(null);
            $table->text('view')->nullable()->default(null);
            $table->foreignId('mtq_game_id')->constrained()->onDelete('cascade');
            $table->integer('width')->default(8);
            $table->integer('height')->default(8);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mtq_maps');
    }
};

