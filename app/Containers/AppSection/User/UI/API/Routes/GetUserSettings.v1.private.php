<?php

/**
 * @apiGroup           User
 * @apiName            getUserSettings
 * @api                {get} /v1/user/settings Get authorized user settings
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiUse             GeneralSuccessMultipleResponse
 */

use App\Containers\AppSection\User\UI\API\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::get('user/settings', [Controller::class, 'getUserSettings'])
    ->name('api_user_get_user_settings')
    ->middleware(['auth:api']);
