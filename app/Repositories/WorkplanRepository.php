<?php

namespace App\Repositories;

use App\Http\Requests\GetWorkplanRequest;
use App\Interfaces\WorkplanRepositoryInterface;
use App\Services\CacheService;
use App\Traits\Functions;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Workplan;
use App\Validators\WorkplanValidator;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class WorkplanRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class WorkplanRepository extends BaseRepository implements WorkplanRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'workplans';

    public function __construct(CacheService $cacheService)
    {
        $this->tag = Workplan::getTag();
        $this->cacheService = $cacheService;
    }

    public function getActiveWorkplans()
    {
        try {
            $model = $this->model();
            $workplans = $model::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->where('active', '=', 1)
                ->get()->toArray();

            return $workplans;
        } catch( Exception $ex ) {
            $this->logError($ex, 'getActiveWorkplans error', []);
            throw $ex;
        }
    }

    public function getWorkplans(Request $request)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function() use($request) {
                $workplanQuery = Workplan::search($request);

                return $workplanQuery->get();
            });
        } catch( Exception $ex ) {
            $this->logError($ex, 'getWorkplans error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function getWorkplan(int $id): Workplan
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return Workplan::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getWorkplan error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getWorkplanByName(string $name): Workplan
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $name);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return Workplan::where('name', '=', $name)->firstOrFail();
            });
        } catch( Exception $ex ) {
            $this->logError($ex, 'getWorkplanByName error', ['name' => $name]);
            throw $ex;
        }
    }

    public function createWorkplan(Request $request): ?Workplan
    {
        try {
            $workplan = null;

            DB::transaction(function() use($request, &$workplan) {
                // 1. Munkarend létrehozása
                $workplan = Workplan::create($request->all());

                // 2. Kapcsolódó rekordok létrehozása (pl. alapértelmezett beállítások)
                $this->createDefaultSettings($workplan);

                // 3. Cache törlése, ha releváns
                $this->cacheService->forgetAll($this->tag);
            });

            return $workplan;
        } catch( Exception $ex ) {
            $this->logError($ex, 'createWorkplan error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function updateWorkplan($request, int $id): ?Workplan
    {
        try {
            $workplan = null;

            DB::transaction(function() use($request, $id, &$workplan) {
                $workplan = Workplan::lockForUpdate()->findOrFail($id);
                $workplan->update($request->all());
                $workplan->refresh();
            });

            return $workplan;
        } catch( Exception $ex ) {
            $this->logError($ex, 'updateWorkplan error', ['id' => $id, 'request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteWorkplans(Request $request): int
    {
        try {
            $validated = $request->validate([
                'ids' => ['required', 'array', 'min:1'],
                'ids.*' => ['integer', 'exists:workplans,id'],
            ]);
            $ids = $validated['ids'];
            $deletedCount = 0;

            DB::transaction(function() use($ids, &$deletedCount) {
                $workplans = Workplan::whereIn('id', $ids)->lockForUpdate()->get();

                $deletedCount = $workplans->each(function ($workplan): void {
                    $workplan->delete();
                })->count();

                $this->cacheService->forgetAll($this->tag);
            });

            return $deletedCount;
        } catch( Exception $ex ) {
            $this->logError($ex, 'deleteWorkplans error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteWorkplan(Request $request): ?Workplan
    {
        $workplan = null;
        try {
            DB::transaction(function() use($request, &$workplan) {
                $workplan = Workplan::lockForUpdate()->findOrFail($request->id);
                $workplan->delete();

                $this->cacheService->forgetAll($this->tag);
            });

            return $workplan;
        } catch( Exception $ex ) {
            $this->logError($ex, 'deleteWorkplan error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function restoreWorkplan(Request $request): ?Workplan
    {
        try {
            $workplan = null;
            DB::transaction(function() use($request, &$workplan) {
                $workplan = Workplan::withTrashed()->lockForUpdate()->findOrFail($request->id);
                $workplan->restore();

                $this->cacheService->forgetAll($this->tag);
            });

            return $workplan;
        } catch( Exception $ex ) {
            $this->logError($ex, 'restoreWorkplan error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function realDeleteWorkplan(int $id): Workplan
    {
        try {
            $workplan = null;
            DB::transaction(function() use($id, &$workplan): void {
                $workplan = Workplan::withTrashed()->lockForUpdate()->findOrFail($id);
                $workplan->forceDelete();

                $this->cacheService->forgetAll($this->tag);
            });

            return $workplan;
        } catch( Exception $ex ) {
            $this->logError($ex, 'realDeleteWorkplan error', ['id' => $id]);
            throw $ex;
        }
    }

    private function createDefaultSettings(Workplan $company): void{}

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Workplan::class;
    }

    public function validator(){
        return WorkplanValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
