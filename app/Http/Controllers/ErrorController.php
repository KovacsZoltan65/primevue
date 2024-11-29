<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ErrorController extends Controller
{
    /**
     * ==========================================
     * 1. Hibák listázása dátumszűrővel:
     *      GET /error-logs?date_from=2024-11-01&date_to=2024-11-20
     * 2. Egy adott hiba megtekintése:
     *      GET /error-logs/123
     * 3. Hiba törlése:
     *      DELETE /error-logs/123
     * ==========================================
     */

    public static function logServerError(Throwable $error, array $additionalData = []): JsonResponse
    {
        // Hiba alapinformációinak kinyerése
        $errorData = [
            'message' => $error->getMessage(),
            'stack' => $error->getTraceAsString(),
            'file' => $error->getFile(),
            'line' => $error->getLine(),
            'time' => now()->toISOString(),
            'uniqueErrorId' => $additionalData['uniqueErrorId'] ?? Str::uuid()->toString(),
        ];

        // Extra adatok hozzáadása (ha van)
        $errorData = array_merge($errorData, $additionalData);

        $errorId = md5($errorData['message'] . $errorData['file'] . $errorData['line']);
        $existingError = Activity::where('properties->errorId', $errorId)->first();

        $return_array = [];
        
        if( $existingError )
        {
            // Ha létezik, növeljük az előfordulások számát
            $existingError->increment('occurrence_count');
            
            $return_array = ['success' => true, 'message' => 'Error occurrence updated.'];
        }
        else
        {
            $errorData = array_merge($errorData, ['errorId' => $errorId]);
            $batch_uuid = Str::uuid()->toString();
            
            // Új hiba létrehozása
            activity()
                ->tap(function ($activity) use($batch_uuid) {
                    $activity->batch_uuid = $batch_uuid;
                })
                ->causedBy(auth()->user())
                ->withProperties( $errorData )
                ->log('Server-side error reported.');
            
            $return_array = ['success' => true, 'message' => 'Error logged.'];
        }

        return response()->json($return_array, Response::HTTP_OK);
    }

    public function logClientError(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'stack' => 'nullable|string',
            'component' => 'nullable|string',
            'info' => 'nullable|string',
            'time' => 'nullable|date',
            'route' => 'nullable|string',
            'url' => 'nullable|string',
            'userAgent' => 'nullable|string',
            'uniqueErrorId' => 'nullable|string',
        ]);

        $return_array = [];

        $errorId = md5($validated['message'] . $validated['component'] . $validated['route']);
        $existingError = Activity::where('properties->errorId', $errorId)->first();

        if( $existingError ) {
            $existingError->increment('occurrence_count');
            $return_array = ['success' => true, 'message' => 'Error occurrence updated.'];
        } else {
            $validated = array_merge($validated, ['errorId' => $errorId]);
            
            $batch_uuid = Str::uuid()->toString();
            
            activity()
                ->tap(function($activity) use($batch_uuid) {
                    $activity->batch_uuid = $batch_uuid;
                })
                ->causedBy(auth()->user())
                ->withProperties( $validated )
                ->log('Client-side error reported.');

            $return_array = ['success' => true, 'message' => 'Error logged.'];
        }

        return response()->json($return_array, Response::HTTP_OK);
    }

    public static function logClientValidationError(Request $request): JsonResponse
    {
        $data = [
            'componentName' => $request->input('componentName', 'UnknownComponent'),
            'priority' => $request->input('priority', 'medium'),
            'additionalInfo' => $request->input('additionalInfo', ''),
            'validationErrors' => $request->input('validationErrors', []),
        ];

        // Validációs hibák azonosítója (összesített kulcs a hibák alapján)
        $errorId = md5(json_encode($data['validationErrors']) . $data['componentName']);
        $existingError = Activity::where('properties->errorId', $errorId)->first();

        if ($existingError) {
            $existingError->increment('occurrence_count');
            
            return response()->json([
                'success' => true,
                'message' => 'Error occurrence updated.',
                'errorId' => $errorId,
            ], Response::HTTP_OK);
        } 

        $data = array_merge($data, [
            'errorId' => $errorId,
            'timestamp' => now()->toDateTimeString(),
        ]);
        $batch_uuid = Str::uuid()->toString();

        activity()
            ->tap(function ($activity) use ($batch_uuid) {
                $activity->batch_uuid = $batch_uuid;
            })
            ->causedBy(auth()->user())
            ->withProperties($data)
            ->log('Validation error reported.');

        return response()->json([
            'success' => true,
            'message' => 'Validation error logged.',
            'errorId' => $errorId,
        ], Response::HTTP_OK);
    }

    public static function logServerValidationError(ValidationException $ex, Request $request): JsonResponse
    {
        $errors = $ex->errors();
        $data = [
            'componentName' => $request->route()->getName() ?? 'UnknownRoute',,
            'priority' => 'medium',
            'additionalInfo' => 'Server-side validation failed',
            'validationErrors' => $errors,
        ];
        $errorId = md5(json_encode($errors) . $data['componentName']);
        $existingError = \Spatie\Activitylog\Models\Activity::where('properties->errorId', $errorId)->first();
        
        if($existingError) {
            $existingError->increment('occurrence_count');
        } else {
            activity()
                ->causedBy(auth()->user() ?? null)
                ->withProperties(array_merge($data, ['errorId' => $errorId]))
                ->log('Server-side validation error.');
        }
    }
    
    public function index()
    {
        $errors = Activity::select('id', 'description', 'properties', 'occurrence_count', 'created_at')
            ->orderBy('occurrence_count', 'desc')
            ->paginate(20);

        return Inertia::render('ErrorLogs/Index', [
            'errors' => $errors,
        ]);
    }
    
    /*
    public function index_01(Request $request): InertiaResponse
    {
        // Alapértelmezett szűrési paraméterek
        $perPage = $request->get('per_page', 10); // Oldalméret
        $dateFrom = $request->get('date_from'); // Szűrés kezdő dátum
        $dateTo = $request->get('date_to'); // Szűrés végső dátum

        // Alapkérdés az activity_log táblára
        $query = Activity::query()->where('log_name', 'error'); // Csak hibák

        // Dátumtartomány szűrés: ha a date_from paraméter meg van adva, akkor
        // az activity_log táblában a created_at oszlopban azonosított
        // dátumtartományon belül fogja keresni az elemeket.
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        // Dátumtartomány szűrés: ha a date_to paraméter is meg van adva, akkor
        // az activity_log táblában a created_at oszlopban azonosított
        // dátumtartományon belül fogja keresni az elemeket.
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Lekérjük a hibákat az activity_log táblából a fenti szűrési paraméterekkel.
        // A ->paginate() metódussal oldalszámozott lista lesz generálva.
        // Az oldalméretet a $perPage változóban megadott érték határozza meg.
        $logs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return inertia('ErrorLogs/Index_01', [
            'logs' => $logs,
        ]);
    }
    */

    /**
     * Egy adott hiba megtekintése
     *
     * A show metódus fogja visszaadni a hibát, amelyet az adatbázisból
     * lekérünk a findOrFail() metódussal. Az inertia() függvénnyel egy új oldalt
     * renderelünk a hibával.
     *
     * @param int $id A hiba azonosítója
     * @return \Inertia\Response
     */
    public function show(int $id): InertiaResponse
    {
        // Lekérjük a hibát az adatbázisból
        // A findOrFail() metódus egy kivételt dob, ha a hiba nem található
        $log = Activity::findOrFail($id);

        // Egy hiba megtekintéséhez a show metódus fogja visszaadni a hibát
        // Az inertia() függvénnyel egy új oldalt renderelünk a hibával
        // A hibát egy reaktív hivatkozásban kapjuk meg, amelyet az adatbázisból
        // lekérünk a findOrFail() metódussal.
        return inertia('ErrorLogs/Show', [
            'log' => $log,
        ]);
    }

    /**
     * Törli a megadott hibát az adatbázisból.
     *
     * A destroy metódus fogja visszaadni a hibát, amelyet az adatbázisból
     * lekérünk a findOrFail() metódussal. A hiba törlését a delete() metódus
     * végzi, ami egy "hard delete"-t jelent. A hiba törlését követően a
     * rendszer átirányít a hibalista oldalra, ahol a felhasználó
     * értesülhet a hiba törléséről.
     *
     * @param int $id A hiba azonosítója
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $log = Activity::findOrFail($id);

        // Példa: a törlés helyett egy "deaktiválás" lehetőséget biztosítunk
        $log->delete(); // Ezt "soft delete"-ként is lehetne implementálni.

        return redirect()->route('error-logs.index')->with('success', 'Log entry deleted.');
    }
    
    public function getErrorById(string $errorId): JsonResponse
    {
        $success = true;
        $message = '';
        $data = [];
        $response = Response::HTTP_OK;
        
        $error = Activity::where('properties->errorId', $errorId)->first();
        
        if( !$error ) {
            $success = false;
            $message = __('error_not_found');
            $response = Response::HTTP_NOT_FOUND;
        } else {
            $data = [
                'message' => $error->description,
                'properties' => $error->properties,
                'occurrence_count' => $error->occurrence_count,
                'created_at' => $error->created_at,
                'updated_at' => $error->updated_at,
            ];
        }
        
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $response);
    }
    
    public function getErrorByUniqueId(string $uniqueErrorId): JsonResponse
    {
        $success = true;
        $message = '';
        $data = [];
        $response = Response::HTTP_OK;
        
        $error = Activity::where('properties->uniqueErrorId', $uniqueErrorId)->first();
        
        if( !$error ) {
            $success = false;
            $message = __('error_not_found');
            $response = Response::HTTP_NOT_FOUND;
        } else {
            $data = [
                'message' => $error->description,
                'properties' => $error->properties,
                'occurrence_count' => $error->occurrence_count,
                'created_at' => $error->created_at,
                'updated_at' => $error->updated_at,
            ];
        }
        
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $response);
    }
}
