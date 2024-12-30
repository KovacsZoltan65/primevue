<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ErrorController;
use App\Http\Requests\GetRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Resources\Auth\RoleResource;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Traits\Functions;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected RoleRepository $roleRepository;

    public function __construct(RoleRepository $repository)
    {
        $this->roleRepository = $repository;

        $this->middleware('can:roles list', ['only' => ['index', 'applySearch', 'listRolesAndPermissions', 'getRoles', 'getRole', 'getRoleByName']]);
        $this->middleware('can:roles create', ['only' => ['createRole']]);
        $this->middleware('can:roles edit', ['only' => ['updateRole', 'updateRolePermissions']]);
        $this->middleware('can:roles delete', ['only' => ['deleteRole', 'deleteRoles']]);
        $this->middleware('can:roles restore', ['only' => ['restoreRole']]);
    }

    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles('roles');

        $users = User::orderBy('name')->get()->toArray();
        $permissions = Permission::orderBy('name')->get()->toArray();

        return Inertia::render('Auth/Role/Index', [
            'users' => $users,
            'permissions' => $permissions,
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

    public function listRolesAndPermissions(): JsonResponse
    {
        try {
            $array = $this->roleRepository->listRolesAndPermissions();

            return response()->json($array, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'listRolesAndPermissions model not found', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'listRolesAndPermissions query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'listRolesAndPermissions general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getRoles(Request $request): JsonResponse
    {
        try {
            $_roles = $this->roleRepository->getRoles($request);

            $roles = RoleResource::collection($_roles);

            return response()->json($roles, Response::HTTP_OK);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getRoles query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getRoles general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getRole(GetRoleRequest $request): JsonResponse
    {
        try {
            $role = $this->roleRepository->getRole($request->id);

            return response()->json($role, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getRole model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getRole query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getRole general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getRoleByName(string $name): JsonResponse
    {
        try {
            $role = $this->roleRepository->getRoleByName($name);

            return response()->json($role, Response::HTTP_OK);
        } catch (QueryException $ex) {
            return $this->handleException($ex, 'getRoleByName query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'getRoleByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createRole(StoreRoleRequest $request): JsonResponse
    {
        try {
            $role = $this->roleRepository->createRole($request);

            if( isset($request->permissions) ){
                $role->syncPermissions($request->permissions);
            }

            return response()->json($role, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'createRole query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createRole general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateRole(Request $request, int $id): JsonResponse
    {
        try {
            $role = $this->roleRepository->updateRole($request, $id);

            return response()->json($role, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updateRole model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updateRole query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updateRole general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateRolePermissions(Request $request, Role $role): JsonResponse
    {
        $validated = $request->validate([
            'permissions' => 'array'
        ]);

        $role->syncPermissions($validated['permissions']);

        return response()->json(['role' => $role], Response::HTTP_OK);
    }

    public function deleteRoles(Request $request): JsonResponse
    {
        try {
            $deletedCount = $this->roleRepository->deleteRoles($request);

            // Válasz visszaküldése
            return response()->json($deletedCount, Response::HTTP_OK);
        } catch( ValidationException $ex ){
            return $this->handleException($ex, 'deleteRoles validation error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'deleteRoles query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'deleteRoles general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteRole(GetRoleRequest $request): JsonResponse
    {
        try {
            $deletedCount = $this->roleRepository->deleteRole($request);

            return response()->json($deletedCount, Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'deleteRole model not found error', Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'deleteRole query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'deleteRole general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreRole(GetRoleRequest $request): JsonResponse
    {
        try {
            $role = $this->roleRepository->restoreRole($request);

            return response()->json($role, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restoreRole model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restoreRole query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restoreRole general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function realDeleteRole(GetRoleRequest $request): JsonResponse
    {
        try {
            $deletedCount = $this->roleRepository->realDeleteRole($request->id);

            return response()->json($deletedCount, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'realDeleteRole model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'realDeleteRole query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'realDeleteRole general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
