<?php

// Path: src/database/migrations/2024_07_18_000003_create_mtq_tiles_table.php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {

    public function up()
    {
        Schema::create('mtq_tiles', function (Blueprint $table) {
            $table->id();
            $table->string('state')->nullable()->default(null);
            $table->dateTime('entered_at')->nullable()->default(null);
            $table->json('children')->nullable()->default(null);
            $table->json('attributes')->nullable()->default(null);
            $table->foreignId('mtq_map_id')->constrained()->onDelete('cascade');
            $table->integer('x');
            $table->integer('y');
            $table->boolean('has_trap')->default(false);
            $table->boolean('has_flag')->default(false);
            $table->boolean('marked_as_clue')->default(false);
            $table->integer('traps_around')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mtq_tiles');
    }
};
