<?php

namespace App\Repositories;

use App\Http\Controllers\ErrorController;
use App\Models\City;
use App\Services\CacheService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\CityRepositoryInterface;

/**
 * Class CityRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CityRepository extends BaseRepository implements CityRepositoryInterface
{
    protected CacheService $cacheService;

    protected string $tag = 'cities';

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getActiveCities()
    {
        $cities = $this->model->select('id', 'name')
            ->orderBy('name')
            ->where('active', '=', 1)
            ->get()->toArray();
        
        return $cities;
    }
    
    public function getCities(Request $request)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $cityQuery = City::search($request);
                return $cityQuery->get();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getCities error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function getCity(int $id) {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return City::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getCity error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getCityByName(string $name)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $name);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return City::where('name', '=', $name)->firstOrFail();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getCityByName error', ['name' => $name]);
            throw $ex;
        }
    }

    public function createCity(Request $request)
    {
        try {
            $city = City::create($request->all());
            return $city;
        } catch(Exception $ex) {
            $this->logError($ex, 'createCity error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function model()
    {
        //return City::class;
        return 'City';
        //return new City();
    }

    private function logError(Exception $ex, string $context, array $params):void
    {
        ErrorController::logServerError($ex, [
            'context' => $context,
            'params' => $params,
            'route' => request()->path(),
            'type' => get_class($ex),
            'severity' => 'error',
        ]);
    }
    
    private function generateCacheKey(string $tag, string $key): string
    {
        return "{$tag}_" . md5($key);
    }
}
