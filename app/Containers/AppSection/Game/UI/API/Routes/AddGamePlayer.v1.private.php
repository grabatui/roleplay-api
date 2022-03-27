<?php

/**
 * @apiGroup           Game
 * @apiName            enterGame
 * @api                {post} /v1/user/games/:game/addPlayer/:player Add player to the game
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiUse             GeneralSuccessMultipleResponse
 */

use App\Containers\AppSection\Game\UI\API\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::put('user/games/{game}/addPlayer/{player}', [Controller::class, 'addPlayer'])
    ->name('api_user_add_game_player')
    ->middleware(['auth:api']);
