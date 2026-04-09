<?php

use App\Enums\GameLevel;
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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string("level")->default(GameLevel::EASY->value);
            $table->integer("increment_points")->unsigned();
            $table->integer("decrement_points")->unsigned();
            $table->string("preview");
            $table->foreignId("category_id")->references("id")->on("categories");
            $table->unsignedTinyInteger("age_limit");
            $table->unsignedTinyInteger("attempts");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
