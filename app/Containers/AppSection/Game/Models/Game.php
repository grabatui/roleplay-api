<?php

namespace App\Containers\AppSection\Game\Models;

use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Models\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $world_code
 * @property int $author_id
 * @property string $status
 * @property array $form_settings
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $author
 * @property-read Collection $players
 */
class Game extends Model
{
    protected $guarded = [];

    protected $casts = [
        'form_settings' => 'json',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            (new GamePlayer())->getTable(),
            'game_id',
            'player_id'
        );
    }
}
