<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
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

    protected function logValidationError(ValidationException $exception, $request)
    {
        $errors = $exception->errors();
        $data = [
            'componentName' => $request->route()->getName() ?? 'UnknownRoute',
            'priority' => 'medium',
            'additionalInfo' => 'Server-side validation failed',
            'validationErrors' => $errors,
        ];
        $errorId = md5(json_encode($errors) . $data['componentName']);
        $existingError = \Spatie\Activitylog\Models\Activity::where('properties->errorId', $errorId)->first();

        if ($existingError) {
            $existingError->increment('occurrence_count');
        } else {
            activity()
                ->causedBy(auth()->user() ?? null)
                ->withProperties(array_merge($data, ['errorId' => $errorId]))
                ->log('Server-side validation error.');
        }
    }
}
