<?php

namespace App\Containers\AppSection\Game\Models;

use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Models\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $world_code
 * @property int $author_id
 * @property string $status
 * @property array $form_settings
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $author
 */
class UserWorld extends Model
{
    protected $guarded = [];

    protected $casts = [
        'form_settings' => 'json',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
