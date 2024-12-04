<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ErrorController;
use App\Http\Requests\GetPermissionRequest;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\Auth\PermissionResource;
use App\Models\Auth\Permission;
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

class PermissionController extends Controller
{
    use Functions;
    
    public function __construct() {
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
            $permissionQuery = Permission::search($request);

            $permissions = PermissionResource::collection($permissionQuery->get());

            return response()->json($permissions, Response::HTTP_OK);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_PERMISSIONS',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getPermissions general error',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getPermission(GetPermissionRequest $request): JsonResponse
    {
        try {
            $permission = Permission::findOrFail($request->id);

            return response()->json($permission, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getPermission error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Permission not found'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_PERMISSION',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getPermission general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getPermissionByName(string $name): JsonResponse
    {
        try {
            $permission = Permission::where('name', '=', $name)->first();

            return response()->json($permission, Response::HTTP_OK);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_PERMISSION',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getPermissionByName general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function createPermission(StorePermissionRequest $request): JsonResponse
    {
        try {
            $permission = Permission::create($request->all());
            
            return response()->json($permission, Response::HTTP_CREATED);
        } catch(ValidationException $ex) {
            ErrorController::logServerValidationError($ex, $request);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Validation error occurred',
                'details' => $ex->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_ROLE_DATABASE_ERROR',
                'route' => request()->path(),
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => __('command_permission_create_database_error'),
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'createPermission general error',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function updatePermission(UpdatePermissionRequest $request): JsonResponse
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->update($request->all());
            $permission->refresh();
            
            return response()->json($permission, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_UPDATE_PERMISSION',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'PERMISSION_NOT_FOUND',
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_PERMISSIONS',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'DB_ERROR_PERMISSIONS',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updatePermission general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deletePermission(Request $request): JsonResponse
    {
        try {
            $permission = Permission::findOrFail($request->id);
            $permission->delete();

            return response()->json([
                'success' => APP_TRUE,
                'message' => 'Permissione deleted successfully.',
                'data' => $permission,
            ], Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deletePermissions error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deletePermissions State not found',
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deletePermissions database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error occurred while deleting the permission.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomainState general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deletePermissions(Request $request): JsonResponse
    {
        try {
            // Az azonosítók tömbjének validálása
            $validated = $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'integer|exists:permissions,id',
            ]);

            // Az azonosítók kigyűjtése
            $ids = $validated['ids'];

            // A cégek törlése
            $deletedCount = Permission::whereIn('id', $ids)->delete();

            // Válasz visszaküldése
            return response()->json([
                'success' => true,
                'message' => 'Selected permissions deleted successfully.',
                'deleted_count' => $deletedCount,
            ], Response::HTTP_OK);

        } catch(ValidationException $ex) {
            // Validációs hiba logolása
            //ErrorController::logClientValidationError($request);
            ErrorController::logServerValidationError($ex, $request);

            // Kliens válasz
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Validation error occurred',
                'details' => $ex->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            // Adatbázis hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deletePermissions database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error occurred while deleting the selected permissions.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            // Általános hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deletePermissions general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function restorePermission(GetPermissionRequest $request): JsonResponse
    {
        try {
            $permission = Permission::withTrashed()->findOrFail($request->id);
            $permission->restore();
            
            return response()->json($permission, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_RESTORE_PERMISSION', // updateCompany not found error
                'route' => request()->path(),
            ]);

            // Ha a rekord nem található
            return response()->json([
                'success' => false,
                'message' => 'Permission not found in trashed records',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_PERMISSION',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'DB_ERROR_PERMISSION',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'restorePermission general error',
                'route' => request()->path(),
            ]);

            // Általános hibakezelés
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while restoring the subdomain state',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
