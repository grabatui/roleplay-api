<?php

namespace App\Ship\Exceptions\Handlers;

use Apiato\Core\Exceptions\Handlers\ExceptionsHandler as CoreExceptionsHandler;
use App\Ship\Parents\Exceptions\Exception as ParentException;

class ExceptionsHandler extends CoreExceptionsHandler
{
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (ParentException $e) {
            if (config('app.debug')) {
                $response = [
                    'message' => $e->getMessage(),
                    'errors' => $e->getErrors(),
                    'exception' => static::class,
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->gettrace()
                ];
            } else {
                $response = [
                    'message' => $e->getMessage(),
                    'errors' => $e->getErrors()
                ];
            }

            return response()->json($response, $e->getCode());
        });
    }
}
