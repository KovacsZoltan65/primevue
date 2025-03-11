<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\SubdomainStateRepositoryInterface;
use App\Models\SubdomainState;
use App\Services\CacheService;
use App\Traits\Functions;
use Override;
use Exception;

/**
 * Class SubdomainStateRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class SubdomainStateRepository extends BaseRepository implements SubdomainStateRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'subdomain_state';

    public function __construct(CacheService $cacheService)
    {
        $this->tag = \App\Models\Subdomain::getTag();
        
        $this->cacheService = $cacheService;
    }

    public function getActiveStates(): Collection
    {
        try {
            $model = $this->model();
            $states = $model::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->where('active','=',1)
                ->get()->toArray();

            return $states;
        } catch(Exception $ex) {
            // Hiba logolás az ActivityController segítségével
            $this->logError($ex, 'getActiveStates error', []);
            // Hiba továbbítása
            throw $ex;
        }
    }

    public function getSubdomainStates(Request $request): Collection
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $stateQuery = SubdomainState::search($request);
                return $stateQuery->get();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getSubdomainStates error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function getSubdomainState(int $id): SubdomainState
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return SubdomainState::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getSubdomainState error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getSubdomainStateByName(string $name): SubdomainState
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $name);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return SubdomainState::where('name', '=', $name)->firstOrFail();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getSubdomainStateByName error', ['name' => $name]);
            throw $ex;
        }
    }

    public function createSubdomainState(Request $request): SubdomainState
    {
        try {
            $state = SubdomainState::create($request->all());

            $this->cacheService->forgetAll($this->tag);

            return $state;
        } catch(Exception $ex) {
            $this->logError($ex, 'createSubdomainState error', ['request' => $request]);
            throw $ex;
        }
    }

    public function updateSubdomainState(Request $request, int $id): ?SubdomainState
    {
        try {
            $state = null;

            DB::transaction(function() use($request, $id, &$state) {
                $state = SubdomainState::findOrFail($id);
                $state->update($request->all());
                $state->refresh();

                $this->cacheService->forgetAll($this->tag);
            });

            return $state;
        } catch(Exception $ex) {
            $this->logError($ex, 'updateSubdomainState error', ['id' => $id, 'request' => $request]);
            throw $ex;
        }
    }

    public function deleteSubdomainStates(Request $request): int
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:roles,id', // Az id-k egész számok és létező cégek legyenek
            ]);
            $ids = $validated['ids'];
            $deletedCount = SubdomainState::whereIn('id', $ids)->delete();

            $this->cacheService->forgetAll($this->tag);

            return $deletedCount;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteSubdomainStates error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteSubdomainState(Request $request): SubdomainState
    {
        try {
            $state = SubdomainState::findOrFail($request->id);
            $state->delete();

            $this->cacheService->forgetAll($this->tag);

            return $state;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteSubdomainState error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function restoreSubdomainState(Request $request): SubdomainState
    {
        try {
            $state = SubdomainState::withTrashed()->findOrFail($request->id);
            $state->restore();

            $this->cacheService->forgetAll($this->tag);

            return $state;
        } catch(Exception $ex) {
            $this->logError($ex, 'restoreSubdomainState error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    #[Override]
    public function model()
    {
        return SubdomainState::class;
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
