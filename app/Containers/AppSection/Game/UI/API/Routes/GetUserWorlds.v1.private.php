<?php

/**
 * @apiGroup           Game
 * @apiName            getUserWorlds
 * @api                {get} /v1/user/worlds Get authorized user worlds
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiUse             GeneralSuccessMultipleResponse
 */

use App\Containers\AppSection\Game\UI\API\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::get('user/worlds', [Controller::class, 'getUserWorlds'])
    ->name('api_user_get_user_worlds')
    ->middleware(['auth:api']);
