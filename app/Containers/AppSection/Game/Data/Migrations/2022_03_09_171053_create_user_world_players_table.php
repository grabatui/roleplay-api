<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserWorldPlayersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_world_players', function (Blueprint $table) {
            $table->unsignedBigInteger('user_world_id');
            $table->unsignedBigInteger('user_id');

            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->foreign('user_world_id')->references('id')->on('user_worlds');
            $table->foreign('user_id')->references('id')->on('users');

            $table->primary(['user_world_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_world_players');
    }
}
