<?php

namespace App\Repositories;
//use Override;


use App\Interfaces\ErrorRepositoryInterface;
use App\Models\Activity;
use App\Traits\Functions;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Str;
use Symfony\Component\HttpFoundation\Response;

class ActivityRepository extends BaseRepository implements ErrorRepositoryInterface
{
    use Functions;

    public function __construct(){}
    
    public function getActivities(Request $request)
    {
        try {
            $companyQuery = Activity::search($request);
            return $companyQuery->get();
        } catch( Exception $ex ) {
            $this->logError($ex, 'getActivities error', ['request' => $request->all()]);
            throw $ex;
        }
    }
    
    public function getActivity(int $id)
    {
        try {
            return Activity::findOrFail($id);
        } catch(Exception $ex) {
           $this->logError($ex, 'getActivity error', ['id' => $id]);
           throw $ex;
        }
    }
    
    public static function logServerError(\Throwable $error, array $additionalData = [])
    {
        // Hiba alapinformációinak kinyerése
        $errorData = [
            'message' => $error->getMessage(),
            'stack' => $error->getTraceAsString(),
            'file' => $error->getFile(),
            'line' => $error->getLine(),
            'time' => Carbon::now()->toISOString(),
            'uniqueErrorId' => $additionalData['uniqueErrorId'] ?? Str::uuid()->toString(),
            'type' => $additionalData['type'] ?? 'GeneralError',
            'severity' => $additionalData['severity'] ?? 'error',
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
        };
        
        return $return_array;
    }
    
    public static function logClientError(Request $request)
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
        
        return $return_array;
    }
    
    public static function logClientValidationError(Request $request)
    {
        $data = [
            'componentName' => $request->input('component', 'UnknownComponent'),
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
            'timestamp' => Carbon::now()->toDateTimeString(),
        ]);
        $batch_uuid = Str::uuid()->toString();

        activity()
            ->tap(function ($activity) use ($batch_uuid) {
                $activity->batch_uuid = $batch_uuid;
            })
            ->causedBy(auth()->user())
            ->withProperties($data)
            ->log('Client-side Validation error reported.');
            
        return $errorId;
    }
    
    public static function logServerValidationError(ValidationException $ex, Request $request)
    {
        $errors = $ex->errors();
        $data = [
            'componentName' => $request->route()->getName() ?? 'UnknownRoute',
            'priority' => 'medium',
            'additionalInfo' => 'Server-side validation failed',
            'validationErrors' => $errors,
        ];
        $errorId = md5(json_encode($errors) . $data['componentName']);
        $existingError = Activity::where('properties->errorId', $errorId)->first();

        if($existingError) {
            $existingError->increment('occurrence_count');

            return ['success' => true, 'message' => 'Error occurrence updated'];
        } else {
            activity()
                ->causedBy(auth()->user() ?? null)
                ->withProperties(array_merge($data, ['errorId' => $errorId]))
                ->log('Server-side validation error.');

            return ['success' => true, 'message' => 'Error logged'];
        }
    }
    
    public static function getErrorById(string $errorId)
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

        return [
            'array' => [
                'success' => $success,
                'message' => $message,
                'data' => $data,
            ],
            'response' => $response
        ];
    }
    
    public function getErrorByUniqueId(string $uniqueErrorId)
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

        $result = [
            'array' => [
                'success' => $success,
                'message' => $message,
                'data' => $data,
            ],
            'response' => $response,
        ];
        
        return $result;
    }
    
    public function model()
    {
        return Activity::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}