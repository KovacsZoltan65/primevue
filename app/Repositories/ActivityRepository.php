<?php

namespace App\Repositories;
//use Override;


use App\Interfaces\ErrorRepositoryInterface;
use App\Models\Activity;
use App\Services\CacheService;
use App\Traits\Functions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Spatie\Activitylog\Models\Activity AS SpatieActivity;

class ActivityRepository extends BaseRepository implements ErrorRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;
    
    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }
    
    public function getActivities(Request $request)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $companyQuery = Activity::search($request);
                return $companyQuery->get();
            });
        } catch( Exception $ex ) {
            $this->logError($ex, 'getActivities error', ['request' => $request->all()]);
            throw $ex;
        }
    }
    
    public function getActivity(int $id)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return Activity::findOrFail($id);
            });
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
    
    public function logClientError(Request $request)
    {
        //
    }
    
    public static function logClientValidationError(Request $request)
    {
        //
    }
    
    public static function logServerValidationError(ValidationException $ex, Request $request)
    {
        //
    }
    
    public function getErrorById(string $errorId)
    {
        //
    }
    
    public function getErrorByUniqueId(string $uniqueErrorId)
    {
        try {
            //
        } catch(Exception $ex) {
            //
        }
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