<?php

namespace App\Containers\AppSection\Game\Models;

use App\Ship\Parents\Models\Model;
use Carbon\Carbon;

/**
 * @property string $code
 * @property array $form_settings
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class World extends Model
{
    protected $primaryKey = 'code';

    protected $guarded = [];

    protected $casts = [
        'code' => 'string',
        'form_settings' => 'json',
    ];
}