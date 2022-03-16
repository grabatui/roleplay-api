<?php

namespace App\Containers\AppSection\Game\Models;

use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Models\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $game_id
 * @property int $player_id
 * @property Carbon $created_at
 * @property-read Game $game
 * @property-read User $player
 */
class GamePlayer extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'player_id');
    }
}
