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
use App\Traits\Functions;

/**
 * Class CityRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CityRepository extends BaseRepository implements CityRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'cities';

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getActiveCities()
    {
        $model = $this->model();
        $cities = $model::query()
            ->select('id', 'name')
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

    public function getCity(int $id)
    {
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
        try{
            $city = City::create($request->all());
            return $city;
        } catch(Exception $ex) {
            $this->logError($ex, 'createCity error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function updateCity(Request $request, int $id)
    {
        try{
            $city = City::findOrFail($id)->lockForUpdate();
            $city->update($request->all());
            $city->refresh();

            $this->cacheService->forgetAll($this->tag);

            return $city;
        } catch(Exception $ex) {
            $this->logError($ex, 'updateCity error', ['id' => $id, 'request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteCities(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:roles,id', // Az id-k egész számok és létező cégek legyenek
            ]);
            $ids = $validated['ids'];
            $deletedCount = City::whereIn('id', $ids)->delete();

            $this->cacheService->forgetAll($this->tag);

            return $deletedCount;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteCities error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    /**
     * Boot up the repository, pushing criteria
     */
    #[\Override]
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    #[\Override]
    public function model()
    {
        return City::class;
    }
}
