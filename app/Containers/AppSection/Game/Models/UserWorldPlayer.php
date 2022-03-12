<?php

namespace App\Containers\AppSection\Game\Models;

use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Models\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_world_id
 * @property int $player_id
 * @property Carbon $created_at
 * @property-read UserWorld $userWorld
 * @property-read User $player
 */
class UserWorldPlayer extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function userWorld(): BelongsTo
    {
        return $this->belongsTo(UserWorld::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
