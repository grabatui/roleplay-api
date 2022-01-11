<?php

use App\Containers\AppSection\User\Enum\UserSettingCode;
use App\Containers\AppSection\User\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_settings', static function (Blueprint $table): void {
            $table->id();

            $table->foreignIdFor(User::class);
            $table->enum('code', UserSettingCode::ALL);
            $table->json('value');
            $table->timestamps();

            $table->unique(['user_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
}
