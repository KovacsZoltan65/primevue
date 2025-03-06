<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A nem jelentendő kivételek listája.
     *
     * @var array
     */
    protected $dontReport = [
        // Például: \Illuminate\Auth\AuthenticationException::class,
    ];

    public function report(Throwable $exception)
    {
        // Egyéni logika vagy külső hibakövető integráció itt
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        // Validációs hibák kezelése
        if( $exception instanceof ValidationException ) {
            // Naplózás validációs hibákra
            $this->logValidationError($exception, $request);

            // Alapértelmezett válasz visszaküldése
            return parent::render($request, $exception);
        }

        return parent::render($request, $exception);

    }

    protected function logValidationError(ValidationException $exception, Request $request)
    {
        \App\Http\Controllers\ActivityController::logServerValidationError($exception, $request);
    }
}
