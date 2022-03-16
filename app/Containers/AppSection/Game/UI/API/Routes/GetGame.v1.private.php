<?php

/**
 * @apiGroup           Game
 * @apiName            getGame
 * @api                {get} /v1/user/games/:game Get authorized game by id
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiUse             GeneralSuccessMultipleResponse
 */

use App\Containers\AppSection\Game\UI\API\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::get('user/games/{game}', [Controller::class, 'getGame'])
    ->name('api_user_get_game')
    ->middleware(['auth:api']);
