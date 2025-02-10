<?php

namespace App\Repositories;

use App\Interfaces\ShiftTypeRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\ShiftType;
use Exception;

/**
 * Class ShiftTypeRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ShiftTypeRepository extends BaseRepository implements ShiftTypeRepositoryInterface
{
    use \App\Traits\Functions;
    
    protected CacheService $cacheService;
    
    protected string $tag = 'shift_types';
    
    public function __construct(CacheService $cacheService)
    {
        $this->tag = ShiftType::getTag();
        
        $this->cacheService = $cacheService;
    }
    
    public function getActiveShiftTypes()
    {
        try {
            $model = $this->model();
            $shiftTypes = $model::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->where('active','=',1)
                ->get()->toArray();

            return $shiftTypes;
        } catch( Exception $ex ) {
            $this->logError($ex, 'getActiveShiftTypes error', []);
            throw $ex;
        }
    }
    
    public function getShiftTypes(Request $request)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $shiftTypeQuery = ShiftType::search($request);
                return $shiftTypeQuery->get();
                //return $companyQuery->paginate(10);
            });
        } catch( Exception $ex ) {
            $this->logError($ex, 'getShiftTypes error', ['request' => $request->all()]);
            throw $ex;
        }
    }
    
    public function getShiftType(int $id)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return ShiftType::findOrFail($id);
            });
        } catch( Exception $ex ) {
            $this->logError($ex, 'getShiftType error', ['id' => $id]);
            throw $ex;
        }
    }
    
    public function getShiftTypeByName(string $name)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $name);
            
            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return ShiftType::where('name', '=', $name)->firstOrFail();
            });
        } catch( Exception $ex ) {
            $this->logError($ex, 'getShiftTypeByName error', ['name' => $name]);
            throw $ex;
        }
    }
    
    public function createShiftType(Request $request)
    {
        try {
            $shiftType = null;

            DB::transaction(function() use($request, &$shiftType) {
                // 1. Cég létrehozása
                $shiftType = ShiftType::create($request->all());

                // 2. Kapcsolódó rekordok létrehozása (pl. alapértelmezett beállítások)
                $this->createDefaultSettings($shiftType);

                // 3. Cache törlése, ha releváns
                $this->cacheService->forgetAll($this->tag);
            });
            return $shiftType;
        } catch( Exception $ex ) {
            $this->logError($ex, 'createShiftType error', ['request' => $request->all()]);
            throw $ex;
        }
    }
    
    public function updateShiftType($request, int $id)
    {
        try {
            $shiftType = null;
            DB::transaction(function() use($request, $id, &$shiftType) {
                $shiftType = ShiftType::lockForUpdate()->findOrFail($id);
                $shiftType->update($request->all());
                $shiftType->refresh();

                $this->cacheService->forgetAll($this->tag);
            });

            return $shiftType;
        } catch( Exception $ex ) {
            $this->logError($ex, 'updateShiftType error', ['id' => $id, 'request' => $request->all()]);
            throw $ex;
        }
    }
    
    public function deleteShiftTypes(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:shift_types,id', // Az id-k egész számok és létező cégek legyenek
            ]);

            $ids = $validated['ids'];
            $deletedCount = 0;

            DB::transaction(function () use ($ids, &$deletedCount) {
                $shiftTypes = ShiftType::whereIn('id', $ids)->lockForUpdate()->get();

                $deletedCount = $shiftTypes->each(function ($shiftType) {
                    $shiftType->delete();
                })->count();

                // Cache törlése, ha szükséges
                $this->cacheService->forgetAll($this->tag);
            });

            return $deletedCount;
        } catch( Exception $ex ) {
            $this->logError($ex, 'deleteShiftTypes error', ['request' => $request->all()]);
            throw $ex;
        }
    }
    
    public function deleteShiftType(Request $request)
    {
        try {
            $shiftType = null;
            DB::transaction(function() use($request, &$shiftType) {
                $shiftType = ShiftType::lockForUpdate()->findOrFail($request->id);
                $shiftType->delete();

                $this->cacheService->forgetAll($this->tag);
            });

            return $company;
        } catch( Exception $ex ) {
            $this->logError($ex, 'deleteShiftType error', ['request' => $request->all()]);
            throw $ex;
        }
    }
    
    public function restoreShiftType(Request $request): ShiftType
    {
        try {
            $shiftType = new ShiftType();
            DB::transaction(function() use($request, &$shiftType) {
                $shiftType = ShiftType::withTrashed()->lockForUpdate()->findOrFail($request->id);
                $shiftType->restore();

                $this->cacheService->forgetAll($this->tag);
            });

            return $company;
        } catch( Exception $ex ) {
            $this->logError($ex, 'restoreShiftType error', ['request' => $request->all()]);
            throw $ex;
        }
    }
    
    public function realDeleteShiftType(int $id): ShiftType
    {
        try {
            $shiftType = null;
            DB::transaction(function() use($id, &$shiftType) {
                $shiftType = ShiftType::withTrashed()->lockForUpdate()->findOrFail($id);
                $shiftType->forceDelete();

                $this->cacheService->forgetAll($this->tag);
            });


            return $company;
        } catch( Exception $ex ) {
            $this->logError($ex, 'realDeleteShiftType error', ['id' => $id]);
            throw $ex;
        }
    }
    
    private function createDefaultSettings(ShiftType $shiftType): void
    {
        try {
            //
        } catch( Exception $ex ) {
            //
        }
    }
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ShiftType::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
