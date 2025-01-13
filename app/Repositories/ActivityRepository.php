<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Interfaces\ErrorRepositoryInterface;
use Spatie\Activitylog\Models\Activity;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Services\CacheService;
use App\Traits\Functions;
//use Override;
use Exception;

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
        //
    }
    
    public function getActivity(int $id)
    {
        //
    }
    
    public static function logServerError(\Throwable $error, array $additionalData = [])
    {
        //
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