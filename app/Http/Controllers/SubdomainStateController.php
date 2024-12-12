<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetSubdomainStateRequest;
use App\Http\Requests\StoreSubdomainStateRequest;
use App\Http\Requests\UpdateSubdomainStateRequest;
use App\Http\Resources\SubdomainStateResource;
use App\Models\Company;
use App\Models\SubdomainState;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use App\Traits\Functions;

class SubdomainStateController extends Controller
{
    use AuthorizesRequests,
        Functions;
    
    protected string $tag = 'subdomainstate';
    
    public function __construct()
    {
        $this->middleware('can:subdomainstate list', ['only' => ['index', 'applySearch', 'getSubdomainStates', 'getSubdomainState', 'getSubdomainStateByName']]);
        $this->middleware('can:subdomainstate create', ['only' => ['createSubdomainState']]);
        $this->middleware('can:subdomainstate edit', ['only' => ['updateSubdomainStates']]);
        $this->middleware('can:subdomainstate delete', ['only' => ['deleteSubdomainState', 'deleteSubdomainStates']]);
        $this->middleware('can:subdomainstate restore', ['only' => ['restoreSubdomainState']]);
    }

    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles('subdomainstate');
        
        return Inertia::render('SubdomainState/Index', [
            'search' => $request->get('search'),
            'can' => $roles,
        ]);
    }

    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function($query, string $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }

    public function getSubdomainStates(Request $request, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5(json_encode($request->all()));

            $subdomainStates = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $subdomainStateQuery = SubdomainState::search($request);
                return CompanyResource::collection($subdomainStateQuery->get());
            });
            
            return response()->json($subdomainStates, Response::HTTP_OK);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomainStates query error',
                'params' => ['request' => $request->all()],
                'route' => $request->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSubdomainStates query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomainStates general error',
                'params' => ['request' => $request->all()],
                'route' => $request->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSubdomainStates general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSubdomainState(GetSubdomainStateRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5($request->id);
            
            $subdomainState = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                return Subdomain::findOrFail($request->id);
            });
            
            return response()->json($subdomainState, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomainState model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSubdomainState model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomainState query error',
                'params' => ['id' => $request->id],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSubdomainState query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomainState general error',
                'params' => ['id' => $request->id],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSubdomainState general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSubdomainStateByName(string $name, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5($name);
            
            $subdomainState = $cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return SubdomainState::where('name', '=', $name)->first();
            });
            
            return response()->json($subdomainState, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomainStateByName model not found error',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => "getSubdomainStateByName model not found error"
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomainStateByName query error',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSubdomainStateByName query error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomainStateByName general error',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            // JSON-választ küld vissza, jelezve, hogy váratlan hiba történt
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSubdomainStateByName general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createSubdomainState(StoreSubdomainStateRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $subdomainState = SubdomainState::create($request->all());
            $cacheService->forgetAll($this->tag);
            
            return response()->json($subdomainState, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'createSubdomainState query error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'createSubdomainState query error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'createSubdomainState general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'createSubdomainState general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateSubdomainState(UpdateSubdomainStateRequest $request, int $id, CacheService $cacheService): JsonResponse
    {
        try {
            $subdomainState = null;
            
            \DB::transaction(function() use($request, $id, $cacheService, &$subdomainState) {
                $subdomainState = SubdomainState::findOrFail($id);
                $subdomainState->update($request->all());
                $subdomainState->refresh();

                $cacheService->forgetAll($this->tag);
            });
            
            return response()->json($subdomainState, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateSubdomainState model not found error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateSubdomainState model not found error',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateSubdomainState query error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateSubdomainState query error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateSubdomainState general error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateSubdomainState general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteSubdomainState(GetSubdomainStateRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $subdomainState = SubdomainState::findOrFail($request->id);
            $subdomainState->delete();

            $cacheService->forgetAll($this->tag);
            
            return response()->json($subdomainState, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomainState model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteSubdomainState model not found error',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomainState database error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteSubdomainState database error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomainState general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteSubdomainState general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteSubdomainStates(Request $request, CacheService $cacheService): JsonResponse
    {
        try {
            // Az azonosítók tömbjének validálása
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:subdomain_states,id', // Az id-k egész számok és létező cégek legyenek
            ]);

            // Az azonosítók kigyűjtése
            $ids = $validated['ids'];

            // A cégek törlése
            $deletedCount = SubdomainState::whereIn('id', $ids)->delete();

            $cacheService->forgetAll($this->tag);
            
            // Válasz visszaküldése
            return response()->json($deletedCount, Response::HTTP_OK);

        } catch(ValidationException $ex) {
            ErrorController::logServerValidationError($ex, $request);

            // Kliens válasz
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteSubdomainStates validation error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomainStates database error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteSubdomainStates database error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomainStates general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteSubdomainStates general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function restoreSubdomainState(GetSubdomainStateRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $state = SubdomainState::withTrashed()->findOrFail($request->id);
            $state->restore();
            
            $cacheService->forgetAll($this->tag);
            
            return response()->json($state, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'restoreSubdomainState model not found exception',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            // Ha a rekord nem található
            return response()->json([
                'success' => APP_FALSE,
                'message' => 'restoreSubdomainState model not found exception',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'restoreSubdomainState query error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'restoreSubdomainState query error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'restoreSubdomainState general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            // Általános hibakezelés
            return response()->json([
                'success' => false,
                'message' => 'restoreSubdomainState general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
