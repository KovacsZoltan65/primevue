<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetPersonRequest;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use App\Traits\Functions;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PersonController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected string $tag = 'persons';
    
    public function __construct() {
        //
    }
    
    public function index(Request $request): InertiaResponse
    {
        Inertia::render('Person/Index');
    }
    
    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function($query, string $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }
    
    public function getPersons(Request $request, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5(json_encode($request->all()));
            
            $persons = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $personQuery = Person::Search($request);
                return PersonResource::collection($personQuery->get());
            });
            
            return response()->json($persons, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getPersons query exception',
                'params' => ['request' => $request->all()],
                'route' => $request->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getPersons query exception'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getPersons general error',
                'params' => ['request' => $request->all()],
                'route' => $request->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getPersons general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getPerson(GetPersonRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5($request->id);
            
            $person = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                return Person::findOrFail($request->id);
            });
            
            return response()->json($person, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getPerson model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getPerson model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getPerson query error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getPerson query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getPerson general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getPerson general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getPersonByName(string $name, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5($name);
            
            $person = $cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return Person::where('name', '=', $name)->firstOrFail();
            });
            
            return response()->json($person, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'getPersonByName model not found error',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getPersonByName model not found error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getPersonByName query error',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getPersonByName general error',
                'route' => request()->path(),
            ]);

            // JSON-választ küld vissza, jelezve, hogy váratlan hiba történt
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getPersonByName general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function createPerson(StorePersonRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $person = Person::create($request->all());
            
            $cacheService->forgetAll($this->tag);
            
            return response()->json($person, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'createPerson query error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'createPerson query error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'createPerson general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'createPerson general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function updatePerson(UpdatePersonRequest $request, int $id, CacheService $cacheService): JsonResponse
    {
        try{
            $person = null;
            
            \DB::transaction(function() use($request, $id, $cacheService, &$person) {
                $person = Person::findOrFail($id)->lockForUpdate();
            
                $person->update($request->all());
                $person->refresh();
                
                $cacheService->forgetAll($this->tag);
            });
            
            return response()->json($person, Response::HTTP_OK);
            
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updatePerson model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updatePerson model not found error',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updatePerson query error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updatePerson query error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updatePerson general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updatePerson general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deletePerson(GetPersonRequest $request, CacheService $cacheService): JsonResponse
    {
        try{
            $person = Person::findOrFail($request->id);
            $person->delete();
            
            $cacheService->forgetAll($this->tag);
            
            return response()->json($person, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deletePerson model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deletePerson model not found error',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deletePerson database error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deletePerson database error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deletePerson general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deletePerson general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deletePersons(Request $request, CacheService $cacheService): JsonResponse
    {
        try{
            // Az azonosítók tömbjének validálása
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:persons,id', // Az id-k egész számok és létező cégek legyenek
            ]);
            
            // Az azonosítók kigyűjtése
            $ids = $validated['ids'];
            
            // A cégek törlése
            $deletedCount = Person::whereIn('id', $ids)->delete();
            
            $cacheService->forgetAll($this->tag);
            
            return response()->json($deletedCount, Response::HTTP_OK);
        } catch(ValidationException $ex) {
            ErrorController::logServerValidationError($ex, $request);

            // Kliens válasz
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deletePersons validation error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            // Adatbázis hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deletePersons database error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deletePersons database error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            // Általános hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deletePersons general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deletePersons general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
