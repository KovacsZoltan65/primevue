<?php

namespace App\Repositories\Auth;

use App\Http\Requests\GetPermissionRequest;
use App\Http\Requests\StorePermissionRequest;
use App\Services\CacheService;
use App\Traits\Functions;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\Auth\PermissionRepositoryInterface;
use App\Models\Auth\Permission;
use Override;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PermissionRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    use Functions;
    
    protected CacheService $cacheService;

    protected string $tag = 'permissions';

    public function __construct(CacheService $cacheService)
    {
        $this->tag = Permission::getTag();
        $this->cacheService = $cacheService;
    }

    public function getPermissions(Request $request)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $companyQuery = Permission::search($request);
                return $companyQuery->get();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getPermissions error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function getPermission(int $id)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return Permission::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getPermission error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getPermissionByName(string $name)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $name);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return Permission::where('name', '=', $name)->firstOrFail();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getPermissionByName error', ['name' => $name]);
            throw $ex;
        }
    }

    public function createPermission(StorePermissionRequest $request)
    {
        try{
            $permission = Permission::create($request->all());
            
            $this->cacheService->forgetAll($this->tag);
            
            return $permission;
        } catch(Exception $ex) {
            $this->logError($ex, 'createPermission error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function updatePermission(Request $request, int $id)
    {
        try {
            $permission = null;

            DB::transaction(function() use($request, $id, &$permission){
                $permission = Permission::findOrFail($id)->lockForUpdate();
                $permission->update($request->all());
                $permission->refresh();

                $this->cacheService->forgetAll($this->tag);
            });

            return $permission;
        } catch(Exception $ex) {
            $this->logError($ex, 'updatePermission error', ['id' => $id, 'request' => $request->all()]);
            throw $ex;
        }
    }

    public function deletePermissions(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:roles,id', // Az id-k egész számok és létező cégek legyenek
            ]);
            $ids = $validated['ids'];
            $deletedCount = Permission::whereIn('id', $ids)->delete();

            $this->cacheService->forgetAll($this->tag);

            return $deletedCount;
        } catch(Exception $ex) {
            $this->logError($ex, 'deletePermissions error', ['request' => $request->all()]);
            throw $ex;
        }
    }
    
    public function deletePermission(Request $request)
    {
        try {
            $permission = Permission::withTrashed()->findOrFail($request->id);
            $permission->restore();

            $this->cacheService->forgetAll($this->tag);

            return $permission;
        } catch(Exception $ex) {
            $this->logError($ex, 'deletePermission error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function restorePermission(GetPermissionRequest $request)
    {
        try {
            $permission = Permission::withTrashed()->findOrFail($request->id);
            $permission->restore();

            $this->cacheService->forgetAll($this->tag);

            return $permission;
        } catch(Exception $ex) {
            $this->logError($ex, 'restorePermission error', ['request' => $request->all()]);
            throw $ex;
        }
    }
    
    public function realDeletePermission(int $id)
    {
        try {
            $permission = Permission::withTrashed()->findOrFail($id);
            $deletedCount = $permission->forceDelete();
            
            return $deletedCount;
        } catch(Exception $ex) {
            $this->handleException($ex, 'realDeletePermission error', Response::HTTP_INTERNAL_SERVER_ERROR);
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
        return Permission::class;
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
