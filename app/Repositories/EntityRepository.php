<?php

namespace App\Repositories;

use App\Services\CacheService;
use App\Traits\Functions;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\EntityRepositoryInterface;
use App\Models\Entity;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

/**
 * Class EntityRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class EntityRepository extends BaseRepository implements EntityRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'entities';

    public function __construct(CacheService $cacheService)
    {
        $this->tag = Entity::getTag();

        $this->cacheService = $cacheService;
    }

    public function getActiveCompanies()
    {
        $model = $this->model();
        $companies = $model::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->where('active','=',1)
            ->get()->toArray();

        return $companies;
    }

    public function getEntities(Request $request)
    {
        try {

            $entityQuery = Entity::search($request);
            $aa = $entityQuery->get();
            \Log::info('$aa: ' . print_r($aa, true));
            return $aa;
            /*
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $entityQuery = Entity::search($request);
                return $entityQuery->get();
            });
            */
        } catch(Exception $ex) {
            \Log::info('EntityRepository@getEntities Exception $ex: ' . print_r($ex->getMessage(), true));
            $this->logError($ex, 'getEntities error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function getEntity(int $id)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return Entity::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getEntity error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getEntityByName(string $name)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $name);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return Entity::where('name', '=', $name)->firstOrFail();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getEntityByName error', ['name' => $name]);
            throw $ex;
        }
    }

    public function createEntity(Request $request)
    {
        try {
            $company = Entity::create($request->all());
            return $company;
        } catch(Exception $ex) {
            $this->logError($ex, 'createEntity error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function updateEntity(Request $request, int $id)
    {
        try {
            $entity = null;
            DB::transaction(function() use($request, $id, &$entity) {
                $entity = Entity::findOrFail($id)->lockForUpdate();
                $entity->update($request->all());
                $entity->refresh();

                $this->cacheService->forgetAll($this->tag);
            });

            return $entity;
        } catch(Exception $ex) {
            $this->logError($ex, 'updateEntity error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteEntities(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:roles,id', // Az id-k egész számok és létező cégek legyenek
            ]);
            $ids = $validated['ids'];
            $deletedCount = Entity::whereIn('id', $ids)->delete();

            $this->cacheService->forgetAll($this->tag);

            return $deletedCount;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteEntities error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteEntity(Request $request)
    {
        try {
            $entity = Entity::findOrFail($request->id);
            $entity->delete();

            $this->cacheService->forgetAll($this->tag);

            return $entity;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteEntity error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function restoreEntity(Request $request)
    {
        try {
            $entity = Entity::withTrashed()->findOrFail($request->id);
            $entity->restore();

            $this->cacheService->forgetAll($this->tag);

            return $entity;
        } catch(Exception $ex) {
            $this->logError($ex, 'restoreEntity error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Entity::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
