<?php

namespace App\Containers\AppSection\User\Models;

use App\Ship\Parents\Models\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $code
 * @property string $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 */
class UserSetting extends Model
{
    protected $table = 'user_settings';

    protected $guarded = [];

    protected $casts = [
        'value' => 'json',
    ];

    public $timestamps = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}