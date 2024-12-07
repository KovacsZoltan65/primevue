<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetSubdomainRequest;
use App\Http\Requests\StoreSubdomainRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Resources\SubdomainResource;
use App\Models\Subdomain;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Services\CacheService;

class SubdomainController extends Controller
{
    protected string $tag = 'companies';
    
    public function __construct() {
        $this->middleware('can:subdomains list', ['only' => ['index', 'applySearch', 'getSubdomains', 'getSubdomain', 'getSubdomainByName']]);
        $this->middleware('can:subdomains create', ['only' => ['createSubdomain']]);
        $this->middleware('can:subdomains edit', ['only' => ['updateSubdomain']]);
        $this->middleware('can:subdomains delete', ['only' => ['deleteSubdomain', 'deleteSubdomains']]);
        $this->middleware('can:subdomains restore', ['only' => ['restoreSubdomain']]);
    }
    
    public function index(Request $request): InertiaResponse
    {
        return Inertia::render('Subdomain/Index', [
            'search' => $request->get('search')
        ]);
    }
    
    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function($query, string $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }
    
    public function getSubdomains(Request $request, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "subdomain_" . md5(json_encode($request->all()));
            $subdomains = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $subdomainQuery = Subdomain::search($request);
                return SubdomainResource::collection($subdomainQuery->get());
            });
            
            return response()->json($subdomains, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_SUBDOMAIN',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomains general error',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getSubdomain(GetSubdomainRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "subdomain_{$request->id}";

            $subdomain = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                return Subdomain::findOrFail($request->id);
            });
            
            return response()->json($subdomain, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomain error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Subdomain not found',
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_SUBDOMAIN',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomain general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getSubdomainByName(string $name, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "subdomain_name_" . md5($name);

            $subdomain = $cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return Subdomain::where('name', '=', $name)->first();
            });

            if (!$subdomain) {
                // Ha a cég nem található, 404-es hibát adunk vissza
                return response()->json([
                    'success' => APP_FALSE,
                    'error' => 'Subdomain not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json($subdomain, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_SUBDOMAIN_BY_NAME',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomainByName general error',
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
    
    public function createSubdomain(StoreSubdomainRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $subdomain = Subdomain::create($request->all());
            $cacheService->forgetAll($this->tag);
            
            return response()->json($subdomain, Response::HTTP_CREATED);
            
        } catch(QueryException $ex) {
            // Naplózza a cég létrehozása során észlelt adatbázis-hibát
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_SUBDOMAIN_DATABASE_ERROR', // A hiba háttere
                'route' => request()->path(), // Útvonal, ahol a hiba történt
            ]);

            // Adatbázis hiba esetén a hiba részletes leírását is visszaküldi a kliensnek.
            // Ebben az esetben a HTTP-kód 422 lesz.
            return response()->json([
                'success' => APP_FALSE,
                'error' => __('command_subdomain_create_database_error'), // A hiba részletes leírása
                'details' => $ex->getMessage(), // A hiba részletes leírása
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            // A cég létrehozása során fellépő általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'createCompany general error', // Adja meg a hiba kontextusát
                'route' => request()->path(), // Adja meg az útvonalat, ahol a hiba történt
            ]);

            // Ha egyéb hiba történt, akkor a szerveroldali hiba részletes leírását
            // is visszaküldi a kliensnek.
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function updateSubdomain(UpdateCityRequest $request, int $id, CacheService $cacheService)
    {
        try {
            // Keresse meg a frissítendő céget az azonosítója alapján
            $subdomain = Company::findOrFail($id);

            // Frissítse a vállalatot a HTTP-kérés adataival
            $subdomain->update($request->all());
            // Frissítjük a modelt
            $subdomain->refresh();

            $cacheService->forgetAll($this->tag);
            
            // A frissített vállalatot JSON-válaszként küldje vissza sikeres állapotkóddal
            return response()->json([
                'success' => APP_TRUE,
                'message' => 'SUBDOMAIN_UPDATED_SUCCESSFULLY',
                'data' => $subdomain,
            ], Response::HTTP_OK);
            
        } catch(ModelNotFoundException $ex) {
            // Ha a cég nem található
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_UPDATE_SUBDOMAIN', // updateCompany not found error
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'SUBDOMAIN_NOT_FOUND', // The specified company was not found
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_SUBDOMAIN', // updateCompany database error
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'DB_ERROR_COMPANY', // Database error occurred while updating the company
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateSubdomain general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteSubdomain(GetSubdomainRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $subdomain = Subdomain::findOrFail($request->id);
            $subdomain->delete();
            
            $cacheService->forgetAll($this->tag);
            
            return response()->json([
                'success' => APP_TRUE,
                'message' => 'Subdomain deleted successfully.',
                'data' => $subdomain,
            ], Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomain error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Subdomain not found',
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomain database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error occurred while deleting the subdomain.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomain general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        
        /*
        $subdomain = Subdomain::find($id);
        $success = $subdomain->delete();
        
        return response()->json(['success' => $success], Response::HTTP_OK);
        */
    }
    
    public function deleteSubdomains(Request $request, CacheService $cacheService): JsonResponse
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
            $deletedCount = Subdomain::whereIn('id', $ids)->delete();

            $cacheService->forgetAll($this->tag);
            
            // Válasz visszaküldése
            return response()->json([
                'success' => true,
                'message' => 'Selected subdomains deleted successfully.',
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
        } catch(QueryException $ex) {
            // Adatbázis hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomains database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error occurred while deleting the selected subdomain.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            // Általános hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteCompanies general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function restoreSubdomain(GetSubdomainRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $state = SubdomainState::withTrashed()->findOrFail($request->id);
            $state->restore();
            
            $cacheService->forgetAll($this->tag);
            
            return response()->json($state, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_RESTORE_SUBDOMAIN_STATE', // updateCompany not found error
                'route' => request()->path(),
            ]);

            // Ha a rekord nem található
            return response()->json([
                'success' => false,
                'message' => 'Role not found in trashed records',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_SUBDOMAIN_STATE',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'DB_ERROR_SUBDOMAIN_STATE',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'restoreSubdomainState general error',
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
