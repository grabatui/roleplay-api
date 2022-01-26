<?php

use App\Containers\AppSection\Game\Enum\WorldStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorldsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('worlds', function (Blueprint $table): void {
            $table->string('code', 255)->primary()->unique();
            $table->json('form_settings')->nullable();

            $table->timestamps();
        });

        Schema::create('user_worlds', function (Blueprint $table): void {
            $table->id();
            $table->string('world_code', 255);
            $table->unsignedBigInteger('author_id');
            $table->enum('status', WorldStatusEnum::ALL)->default(WorldStatusEnum::NEW);
            $table->json('form_settings')->nullable();

            $table->foreign('world_code')->references('code')->on('worlds');
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
        Schema::dropIfExists('worlds');
    }
}
