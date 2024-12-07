<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
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
        \App\Http\Controllers\ErrorController::logServerValidationError($exception, $request);
    }
}