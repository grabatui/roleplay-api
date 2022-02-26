<?php

use App\Containers\AppSection\Game\Enum\UserWorldStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorldsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_worlds', function (Blueprint $table): void {
            $table->id();
            $table->string('world_code', 255);
            $table->unsignedBigInteger('author_id');
            $table->enum('status', UserWorldStatusEnum::ALL)->default(UserWorldStatusEnum::NEW);
            $table->json('form_settings')->nullable();

            $table->foreign('author_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_worlds');
    }
}
