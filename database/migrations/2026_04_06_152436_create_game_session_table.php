<?php

use App\Enums\Status;
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
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignId("user_id")->constrained()->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("game_id")->constrained()->onDelete("cascade")->onUpdate("cascade");
            $table->string("status")->default(Status::OPEN->value);
            $table->unsignedTinyInteger("attempts_left");
            $table->string("win_word");
            $table->dateTime("start");
            $table->date("end")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};
