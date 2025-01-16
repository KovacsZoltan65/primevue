<?php

namespace App\Repositories;

use App\Interfaces\RoleRepositoryInterface;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Services\CacheService;
use App\Traits\Functions;
use Exception;
use Illuminate\Support\Facades\DB;
use Override;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RoleRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'roles';

    public function __construct(CacheService $cacheService)
    {
        $this->tag = Role::getTag();
        
        $this->cacheService = $cacheService;
    }

    public function listRolesAndPermissions()
    {
        try {
            $roles = Role::with('permissions')->get();
            $permissions = Permission::all();

            return [
                'roles' => $roles,
                'permissions' => $permissions,
            ];
        } catch(Exception $ex) {
            $this->logError($ex, 'listRolesAndPermission error', []);
            throw $ex;
        }
    }

    public function getRoles(Request $request)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $roleQuery = Role::search($request);
                return $roleQuery->get();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getRoles error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function getRole(int $id)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return Role::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getRole error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getRoleByName(string $name)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $name);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return Role::where('name', '=', $name)->firstOrFail();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getRoleByName error', ['name' => $name]);
            throw $ex;
        }
    }

    public function createRole(Request $request)
    {
        try{
            $role = null;
            DB::transaction(function() use($request, &$role) {
                // 1. Cég létrehozása
                $role = Role::create($request->all());

                // 2. Kapcsolódó rekordok létrehozása (pl. alapértelmezett beállítások)
                $this->createDefaultSettings($role);

                // 3. Cache törlése, ha releváns
                $this->cacheService->forgetAll($this->tag);
            });

            return $role;
        } catch(Exception $ex) {
            $this->logError($ex, 'createRole error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function updateRole(Request $request, int $id): Role
    {
        try {
            $role = null;
            DB::transaction(function() use($request, $id, &$role) {
                $role = Role::lockForUpdate()->findOrFail($id);
                $role->update($request->all());
                $role->refresh();

                if( isset($request->permissions) ) {
                    $role->syncPermissions($request->permissions);
                }

                $this->cacheService->forgetAll($this->tag);
            });

            return $role;
        } catch(Exception $ex) {
            $this->logError($ex, 'updateRole error', ['id' => $id, 'request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteRoles(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:roles,id', // Az id-k egész számok és létező cégek legyenek
            ]);

            $ids = $validated['ids'];
            $deletedCount = 0;

            DB::transaction(function () use ($ids, &$deletedCount) {
                $roles = Company::whereIn('id', $ids)->lockForUpdate()->get();

                $deletedCount = $roles->each(function ($role) {
                    $role->delete();
                })->count();

                // Cache törlése, ha szükséges
                $this->cacheService->forgetAll($this->tag);
            });

            return $deletedCount;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteRoles error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteRole(Request $request)
    {
        try {
            $role = null;
            DB::transaction(function() use($request, &$role) {
                $role = Role::lockForUpdate()->findOrFail($request->id);
                $role->delete();

                $this->cacheService->forgetAll($this->tag);
            });

            return $role;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteRole error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function restoreRole(Request $request): Role
    {
        try {
            $role = null;
            DB::transaction(function() use($request, &$role) {
                $role = Role::withTrashed()->lockForUpdate()->findOrFail($request->id);
                $role->restore();

                $this->cacheService->forgetAll($this->tag);
            });

            return $role;
        } catch(Exception $ex) {
            $this->logError($ex, 'restoreRole error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function realDeleteRole(int $id): Role
    {
        try {
            $role = null;
            DB::transaction(function() use($id, &$role) {
                $role = Role::withTrashed()->lockForUpdate()->findOrFail($id);
                $role->forceDelete();

                $this->cacheService->forgetAll($this->tag);
            });

            return $role;
        } catch(Exception $ex) {
            $this->logError($ex, 'realDeleteRole error', ['id' => $id]);
            throw $ex;
        }
    }

    private function createDefaultSettings(Role $role): void
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
        return Role::class;
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
