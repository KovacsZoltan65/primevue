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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\Functions;

class SubdomainController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected string $tag = 'subdomains';
    
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
            $cacheKey = "{$this->tag}_" . md5(json_encode($request->all()));
            
            $subdomains = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $subdomainQuery = Subdomain::search($request);
                return SubdomainResource::collection($subdomainQuery->get());
            });
            
            return response()->json($subdomains, Response::HTTP_OK);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomains query error',
                'params' => ['request' => $request->all()],
                'route' => $request->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSubdomains query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomains general error',
                'params' => ['request' => $request->all()],
                'route' => $request->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSubdomains general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getSubdomain(GetSubdomainRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5($request->id);

            $subdomain = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                return Subdomain::findOrFail($request->id);
            });
            
            return response()->json($subdomain, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomain model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSubdomain model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomain query error',
                'params' => ['id' => $request->id],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSubdomain query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomain general error',
                'params' => ['id' => $request->id],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSubdomain general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getSubdomainByName(string $name, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5($name);

            $subdomain = $cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return Subdomain::where('name', '=', $name)->firstOrFail();
            });

            return response()->json($subdomain, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomainByName model not found error',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => "getSubdomainByName model not found error"
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomainByName query error',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSubdomainByName query error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomainByName general error',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            // JSON-választ küld vissza, jelezve, hogy váratlan hiba történt
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSubdomainByName general error',
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
            ErrorController::logServerError($ex, [
                'context' => 'createSubdomain query error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'createSubdomain query error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'createSubdomain general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'createSubdomain general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function updateSubdomain(UpdateCityRequest $request, int $id, CacheService $cacheService): JsonResponse
    {
        try {
            $subdomain = null;
            
            \DB::transaction(function() use($request, $id, $cacheService, &$subdomain) {
                $subdomain = Company::findOrFail($id)->lockForUpdate();
                $subdomain->update($request->all());
                $subdomain->refresh();
                $cacheService->forgetAll($this->tag);
            });
            
            return response()->json($subdomain, Response::HTTP_OK);
            
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateSubdomain model not found error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateSubdomain model not found error',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateSubdomain query error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateSubdomain query error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateSubdomain general error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateSubdomain general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteSubdomain(GetSubdomainRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $subdomain = Subdomain::findOrFail($request->id);
            $subdomain->delete();
            
            $cacheService->forgetAll($this->tag);
            
            return response()->json($subdomain, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomain model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteSubdomain model not found error',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomain database error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteSubdomain database error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomain general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteSubdomain general error',
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
            return response()->json($deletedCount, Response::HTTP_OK);
        } catch( ValidationException $ex ){
            ErrorController::logServerValidationError($ex, $request);

            // Kliens válasz
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteSubdomains validation error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomains database error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteSubdomains database error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            // Általános hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomains general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteSubdomains general error',
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
                'context' => 'restoreSubdomain model not found exception',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            // Ha a rekord nem található
            return response()->json([
                'success' => APP_FALSE,
                'message' => 'restoreSubdomain model not found exception',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'restoreSubdomain query error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'restoreSubdomain query error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'restoreSubdomain general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            // Általános hibakezelés
            return response()->json([
                'success' => false,
                'message' => 'restoreSubdomain general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
