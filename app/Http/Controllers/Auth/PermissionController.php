<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\GetPermissionRequest;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\Auth\PermissionResource;
use App\Repositories\Auth\PermissionRepository;
use App\Traits\Functions;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Nette\Schema\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PermissionController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected PermissionRepository $permissionRepository;
    
    public function __construct(PermissionRepository $repository) {
        $this->permissionRepository = $repository;

        $this->middleware('can:permissions list', ['only' => ['index', 'applySearch', 'getPermissions', 'getPermission', 'getPermissionByName']]);
        $this->middleware('can:permissions create', ['only' => ['createPermission']]);
        $this->middleware('can:permissions edit', ['only' => ['updatePermission', 'updateRolePermissions']]);
        $this->middleware('can:permissions delete', ['only' => ['deletePermission', 'deletePermissions']]);
        $this->middleware('can:permissions restore', ['only' => ['restorePermission']]);
    }
    
    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles('roles');
        
        return Inertia::render('Auth/Permission/Index', [
            'search' => request('search'),
            'can' => $roles,
        ]);
    }
    
    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function($query, string $search) {
            // A lekérdezéshez hozzáadja a keresési feltételt
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }
    
    public function getPermissions(Request $request): JsonResponse
    {
        try {
            $_permissions = $this->permissionRepository->getPermissions($request);
            $permissions = PermissionResource::collection($_permissions);

            return response()->json($permissions, Response::HTTP_OK);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getPermissions query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getPermissions general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getPermission(GetPermissionRequest $request): JsonResponse
    {
        try {
            $permission = $this->permissionRepository->getPermission($request->id);

            return response()->json($permission, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getPermission model not founderror', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getPermission query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getPermission general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getPermissionByName(string $name): JsonResponse
    {
        try {
            $permission = $this->permissionRepository->getPermissionByName($name);

            return response()->json($permission, Response::HTTP_OK);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getPermissionByName query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getPermissionByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function createPermission(StorePermissionRequest $request): JsonResponse
    {
        try {
            $permission = $this->permissionRepository->createPermission($request);
            
            return response()->json($permission, Response::HTTP_CREATED);
        } catch(ValidationException $ex) {
            return $this->handleException($ex, 'createPermission validation error occurred', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'createPermission query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createPermission general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function updatePermission(UpdatePermissionRequest $request): JsonResponse
    {
        try {
            $permission = $this->permissionRepository->updatePermission($request, $request->id);
            
            return response()->json($permission, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updatePermission model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updatePermission query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updatePermission general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deletePermissions(Request $request): JsonResponse
    {
        try {
            $deletedCount = $this->permissionRepository->deletePermissions($request);
            
            return response()->json($deletedCount, Response::HTTP_OK);
        } catch(ValidationException $ex) {
            return $this->handleException($ex, 'Validation error occurred', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deletePermissions database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deletePermissions general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deletePermission(Request $request): JsonResponse
    {
        try {
            $permission = $this->permissionRepository->deletePermission($request);

            return response()->json($permission, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deletePermissions model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deletePermissions database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteSubdomainState general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function restorePermission(GetPermissionRequest $request): JsonResponse
    {
        try {
            $permission = $this->permissionRepository->deletePermission($request);
            
            return response()->json($permission, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restorePermission model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restorePermission query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restorePermission general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function realDeletePermission(GetPermissionRequest $request)
    {
        try {
            $deletedCount = $this->permissionRepository->realDeletePermission($request->id);
            
            return response()->json($deletedCount, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'realDeletePermission model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'realDeletePermission query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'realDeletePermission general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
