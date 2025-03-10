<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Interfaces\RegionRepositoryInterface;
use App\Models\Region;
use App\Services\CacheService;
use App\Traits\Functions;
use Override;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class RegionRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class RegionRepository extends BaseRepository implements RegionRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'regions';

    public function __construct(CacheService $cacheService)
    {
        $this->tag = Region::getTag();
        $this->cacheService = $cacheService;
    }

    public function getActiveRegions()
    {
        $model = $this->model();
        $companies = $model::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->where('active','=',1)
            ->get()->toArray();

        return $companies;
    }

    public function getRegions(Request $request)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $regionQuery = Region::search($request);
                return $regionQuery->get();
            });
        } catch(Exception $ex){
            $this->logError($ex, 'getRegions error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function getRegion(int $id)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return Region::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getRegion error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getRegionByName(string $name)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $name);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return Region::where('name', '=', $name)->firstOrFail();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getRegionByName error', ['name' => $name]);
            throw $ex;
        }
    }

    public function createRegion(Request $request)
    {
        try{
            $region = null;

            DB::transaction(function()use($request, &$region) {
                // 1. Régió létrehozása
                $region = Region::create($request->all());
                // 2. Kapcsolódó rekordok létrehozása (pl. alapértelmezett beállítások)
                $this->createDefaultSettings($region);
                // 3. Cache törlése, ha releváns
                $this->cacheService->forgetAll($this->tag);
            });

            return $region;
        } catch(Exception $ex) {
            $this->logError($ex, 'createRegion error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function updateRegion(Request $request, int $id)
    {
        try {
            $region = null;

            DB::trnasaction(function() use($request, $id, &$region) {
                $region = Region::findOrFail($id)->lockForUpdate();
                $region->update($request->all());
                $region->refresh();

                $this->cacheService->forgetAll($this->tag);
            });

            return $region;
        } catch(Exception $ex) {
            $this->logError($ex, 'createRegion error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteRegions(Request $request): int
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:regions,id', // Az id-k egész számok és létező cégek legyenek
            ]);

            $ids = $validated['ids'];
            $deleteCount = 0;

            DB::transaction(function()use($ids, &$deleteCount) {
                $regions = Region::whereIn('id', $ids)->lockForUpdate()->get();

                $deleteCount = $regions->each(function ($region) {
                    $region->delete();
                })->count();

                // Cache törlése, ha szükséges
                $this->cacheService->forgetAll($this->tag);

            });

            return $deleteCount;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteRegions error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteRegion(Request $request): ?Region
    {
        try {
            $region = null;

            DB::transaction(function()use($request, &$region) {
                $region = Region::lockForUpdate()->findOrFail($request->id);
                $region->delete();

                $this->cacheService->forgetAll($this->tag);
            });

            return $region;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteRegion error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function restoreRegion(Request $request)
    {
        try {
            $company = Region::withTrashed()->findOrFail($request->id);
            $company->restore();

            $this->cacheService->forgetAll($this->tag);

            return $company;
        } catch(Exception $ex) {
            $this->logError($ex, 'restoreRegion error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function realDeleteCompany(int $id): ?Region
    {
        try {
            $region = null;

            DB::transaction(function() use($id, &$region) {
                $region = Region::withTrashed()->lockForUpdate()->findOrFail($id);
                $region->forceDelete();

                $this->cacheService->forgetAll($this->tag);
            });


            return $region;
        } catch(Exception $ex) {
            $this->logError($ex, 'realDeleteCompany error', ['id' => $id]);
            throw $ex;
        }
    }

    private function createDefaultSettings(Region $region): void
    {
        //
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    #[Override]
    public function model()
    {
        return Region::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    #[Override]
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
