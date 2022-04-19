<?php

/**
 * @apiGroup           Game
 * @apiName            updateGame
 * @api                {patch} /v1/user/games/:game Update authorized user game
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiUse             GeneralSuccessMultipleResponse
 */

use App\Containers\AppSection\Game\UI\API\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::patch('user/games/{game}', [Controller::class, 'updateGame'])
    ->name('api_user_update_game')
    ->middleware(['auth:api']);
