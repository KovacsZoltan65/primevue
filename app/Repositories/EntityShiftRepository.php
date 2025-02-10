<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Interfaces\EntityShiftRepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\EntityShift;
use App\Services\CacheService;
use App\Traits\Functions;
use Exception;

/**
 * Class ShiftRepository.
 *
 * @package namespace App\Repositories;
 */
class EntityShiftRepository extends BaseRepository implements EntityShiftRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'entities_shifts';

    public function __construct(CacheService $cacheService)
    {
        $this->tag = EntityShift::getTag();

        $this->cacheService = $cacheService;
    }

    public function getActiveEntityShifts()
    {
        try {
            $model = $this->model();
            $entity_shifts = $model::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->where('active', '=', 1)
                ->get()->toArray();

            return $entity_shifts;
        } catch( Exception $ex ) {
            $this->logError($ex, 'getActiveEntityShifts error', []);
            throw $ex;
        }
    }

    public function getEntitiesShifts(Request $request)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));
            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $entityShiftQuery = EntityShift::search($request);
                //return $companyQuery->get();
                return $entityShiftQuery->paginate(10);
            });
        } catch( Exception $ex ) {
            $this->logError($ex, 'getActiveEntityShifts error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function getEntityShift(int $id)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return EntityShift::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getEntityShift error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getEntityShiftByName(string $name)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $name);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return EntityShift::where('name', '=', $name)->firstOrFail();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getEntityShiftByName error', ['name' => $name]);
            throw $ex;
        }
    }

    public function createEntityShift(Request $request)
    {
        try{
            $company = null;

            DB::transaction(function() use($request, &$company) {
                // 1. Cég létrehozása
                $company = EntityShift::create($request->all());

                // 2. Kapcsolódó rekordok létrehozása (pl. alapértelmezett beállítások)
                $this->createDefaultSettings($company);

                // 3. Cache törlése, ha releváns
                $this->cacheService->forgetAll($this->tag);
            });
            return $company;
        } catch(Exception $ex) {
            $this->logError($ex, 'createEntityShift error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function updateEntityShift($request, int $id)
    {
        try {
            $company = null;
            DB::transaction(function() use($request, $id, &$company) {
                $company = EntityShift::lockForUpdate()->findOrFail($id);
                $company->update($request->all());
                $company->refresh();

                $this->cacheService->forgetAll($this->tag);
            });

            return $company;
        } catch(Exception $ex) {
            $this->logError($ex, 'updateEntityShift error', ['id' => $id, 'request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteEntitiesShifts(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:roles,id', // Az id-k egész számok és létező cégek legyenek
            ]);

            $ids = $validated['ids'];
            $deletedCount = 0;

            DB::transaction(function () use ($ids, &$deletedCount) {
                $entitiesShifts = EntityShift::whereIn('id', $ids)->lockForUpdate()->get();

                $deletedCount = $entitiesShifts->each(function ($entityShift) {
                    $entityShift->delete();
                })->count();

                // Cache törlése, ha szükséges
                $this->cacheService->forgetAll($this->tag);
            });

            return $deletedCount;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteEntitiesShifts error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteEntityShift(Request $request)
    {
        try {
            $entityshift = null;
            DB::transaction(function() use($request, &$entityshift) {
                $entityshift = EntityShift::lockForUpdate()->findOrFail($request->id);
                $entityshift->delete();

                $this->cacheService->forgetAll($this->tag);
            });

            return $entityshift;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteEntityShift error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function restoreEntityShift(Request $request): EntityShift
    {
        try {
            $entityShift = new EntityShift();
            DB::transaction(function() use($request, &$entityShift) {
                $entityShift = EntityShift::withTrashed()->lockForUpdate()->findOrFail($request->id);
                $entityShift->restore();

                $this->cacheService->forgetAll($this->tag);
            });

            return $entityShift;
        } catch(Exception $ex) {
            $this->logError($ex, 'restoreEntityShift error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function realDeleteEntityShift(int $id): EntityShift
    {
        try {
            $entityShift = null;
            DB::transaction(function() use($id, &$entityShift) {
                $entityShift = EntityShift::withTrashed()->lockForUpdate()->findOrFail($id);
                $entityShift->forceDelete();

                $this->cacheService->forgetAll($this->tag);
            });


            return $entityShift;
        } catch(Exception $ex) {
            $this->logError($ex, 'realDeleteEntityShift error', ['id' => $id]);
            throw $ex;
        }
    }

    private function createDefaultSettings(EntityShift $entityShift): void
    {
        //
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return EntityShift::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
