<?php

/**
 * @apiGroup           Game
 * @apiName            getGames
 * @api                {get} /v1/user/games Get authorized games
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiUse             GeneralSuccessMultipleResponse
 */

use App\Containers\AppSection\Game\UI\API\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::get('user/games', [Controller::class, 'getGames'])
    ->name('api_user_get_games')
    ->middleware(['auth:api']);
