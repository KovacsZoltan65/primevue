<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Illuminate\Routing\Controller;
use App\Http\Controllers\ErrorController;
use App\Http\Requests\GetRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\Functions;

class RoleController extends Controller
{
    use Functions;

    public function __construct() {
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
            $roles = Role::with('permissions')->get();
            $permissions = Permission::all();

            return response()->json([
                'roles' => $roles,
                'premissions' => $permissions,
            ], Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            $modelName = $ex->getModel(); // A kiváltó modell neve
            $ids = $ex->getIds();         // Az érintett ID vagy ID-k tömbje
            
            ErrorController::logServerError($ex, [
                'context' => 'MODEL_NOT_FOUND',
                'model' => $modelName,
                'ids' => $ids,
                'route' => request()->path(),
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => "The requested resource ({$modelName}) was not found.",
            ], Response::HTTP_NOT_FOUND);
            
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_ROLE_AND_PERMISSIONS',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'listRolesAndPermissions general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getRoles(Request $request): JsonResponse
    {
        try {
            $roleQuery = Role::search($request);

            $roles = RoleResource::collection($roleQuery->get());

            return response()->json($roles, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_ROLES',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getRoles general error',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getRole(GetRoleRequest $request): JsonResponse
    {
        try {
            $role = Role::findOrFail($request->id);

            return response()->json($role, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getRole error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Role not found'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_ROLE',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getRole general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getRoleByName(string $name): JsonResponse
    {
        try {
            // Cég lekérdezése név alapján
            $role = Role::where('name', '=', $name)->first();

            if (!$role) {
                // Ha a cég nem található, 404-es hibát adunk vissza
                return response()->json([
                    'success' => APP_FALSE,
                    'error' => 'Role not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json($role, Response::HTTP_OK);

        } catch (QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_ROLE_BY_NAME',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getRoleByName general error',
                'route' => request()->path(),
            ]);

            // JSON-választ küld vissza, jelezve, hogy váratlan hiba történt
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createRoles(Request $request): JsonResponse
    {
        try {
            \Log::info($request->all());
            $validated = $request->validate([
                'name' => 'required'
            ]);
            \Log::info($validated);
            //$role = Role::create($request->all());
            $role = Role::create($validated);

            if( isset($request->permissions) ){
                $role->syncPermissions($request->permissions);
            }

            return response()->json([
                'success' => APP_TRUE,
                'message' => __('command_role_created', ['id' => $request->id]),
                'data' => $role
            ], Response::HTTP_CREATED);
        } catch(ValidationException $ex) {
            ErrorController::logServerValidationError($ex, $request);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Validation error occurred',
                'details' => $ex->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            // Naplózza a cég létrehozása során észlelt adatbázis-hibát
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_ROLE_DATABASE_ERROR', // A hiba háttere
                'route' => request()->path(), // Útvonal, ahol a hiba történt
            ]);

            // Adatbázis hiba esetén a hiba részletes leírását is visszaküldi a kliensnek.
            // Ebben az esetben a HTTP-kód 422 lesz.
            return response()->json([
                'success' => APP_FALSE,
                'error' => __('command_role_create_database_error'), // A hiba részletes leírása
                'details' => $ex->getMessage(), // A hiba részletes leírása
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'createRole general error',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateRole(Request $request, int $id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);
            $role->update($request->all());
            $role->refresh();

            return response()->json([
                'success' => APP_TRUE,
                'message' => 'ROLE_UPDATED_SUCCESSFULLY',
                'data' => $role,
            ], Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_UPDATE_ROLE', // updateCompany not found error
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'ROLE_NOT_FOUND', // The specified company was not found
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_ROLE',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'DB_ERROR_COMPANY', // Database error occurred while updating the company
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateRole general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }


        /*

        $success = $old_role->update($request->all());

        return response()->json(['success' => $success], Response::HTTP_OK);
        */
    }

    public function updateRolePermissions(Request $request, Role $role): JsonResponse
    {
        $validated = $request->validate([
            'permissions' => 'array'
        ]);
        
        $role->syncPermissions($validated['permissions']);
        
        return response()->json(['role' => $role], Response::HTTP_OK);
    }
    
    public function deleteRole(GetRoleRequest $request): JsonResponse
    {
        try {
            $role = Role::findOrFail($request->id);
            $role->delete();

            return response()->json([
                'success' => APP_TRUE,
                'message' => 'Role deleted successfully.',
                'data' => $role,
            ], Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteRole error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Role not found',
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteRole database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error occurred while deleting the role.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteRole general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteRoles(Request $request): JsonResponse
    {
        try {
            // Az azonosítók tömbjének validálása
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:companies,id', // Az id-k egész számok és létező cégek legyenek
            ]);

            // Az azonosítók kigyűjtése
            $ids = $validated['ids'];

            // A cégek törlése
            $deletedCount = Role::whereIn('id', $ids)->delete();

            // Válasz visszaküldése
            return response()->json([
                'success' => true,
                'message' => 'Selected roles deleted successfully.',
                'deleted_count' => $deletedCount,
            ], Response::HTTP_OK);
        } catch( ValidationException $ex ){
            // Validációs hiba logolása
            //ErrorController::logClientValidationError($request);
            ErrorController::logServerValidationError($ex, $request);

            // Kliens válasz
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Validation error occurred',
                'details' => $ex->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( QueryException $ex ) {
            // Adatbázis hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteRoles database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error occurred while deleting the selected roles.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            // Általános hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteRoles general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreRole(GetRoleRequest $request): JsonResponse
    {
        try {
            $role = Role::withTrashed()->findOrFail($request->id);
            $role->restore();

            return response()->json([
                'success' => true,
                'message' => 'Role restored successfully',
                'data' => $role, // Az aktuális adatokat is visszaadjuk
            ], Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_RESTORE_ROLE', // updateCompany not found error
                'route' => request()->path(),
            ]);

            // Ha a rekord nem található
            return response()->json([
                'success' => false,
                'message' => 'Role not found in trashed records',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_ROLE',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'DB_ERROR_ROLE',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'restoreRole general error',
                'route' => request()->path(),
            ]);

            // Általános hibakezelés
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while restoring the role',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
