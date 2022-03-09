<?php

/**
 * @apiGroup           Game
 * @apiName            getUserWorld
 * @api                {get} /v1/user/worlds/:userWorld Get authorized user world by id
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiUse             GeneralSuccessMultipleResponse
 */

use App\Containers\AppSection\Game\UI\API\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::get('user/worlds/{userWorld}', [Controller::class, 'getUserWorld'])
    ->name('api_user_get_user_world')
    ->middleware(['auth:api']);
