<?php

/**
 * @apiGroup           User
 * @apiName            setUserSettings
 * @api                {post} /v1/userSettings Save authorized user settings
 *
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 *
 * @apiUse             GeneralSuccessMultipleResponse
 */

use App\Containers\AppSection\User\UI\API\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::post('userSettings', [Controller::class, 'setUserSettings'])
    ->name('api_user_set_user_settings')
    ->middleware(['auth:api']);
