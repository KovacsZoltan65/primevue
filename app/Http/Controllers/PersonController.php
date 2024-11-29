<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetPersonRequest;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PersonController extends Controller
{
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
    
    public function getPersons(Request $request): JsonResponse
    {
        try {
            $personQuery = Person::Search($request);
            $person = PersonResource::collection($personQuery->get());
            
            return response()->json($person, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_PERSONS',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getCompanies general error',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getPerson(GetPersonRequest $request): JsonResponse
    {
        try {
            $person = Person::findOrFail($request->id);
            
            return response()->json($person, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getPerson error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Person not found'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANY',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getPerson general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getPersonByName(string $name): JsonResponse
    {
        try {
            $person = Person::where('name', $name)->first();
            
            if(!$person) {
                // Ha a cég nem található, 404-es hibát adunk vissza
                return response()->json([
                    'success' => APP_FALSE,
                    'error' => 'Person not found'
                ], Response::HTTP_NOT_FOUND);
            }
            
            return response()->json($person, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANY_BY_NAME',
                'route' => request()->path(),
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
                'success' => APP_FALSE, // A művelet nem volt sikeres
                'error' => 'An unexpected error occurred' // Hibaüzenet a visszatéréshez
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // HTTP állapotkód belső szerverhiba miatt
        }
    }
    
    public function createPerson(StorePersonRequest $request): JsonResponse
    {
        try {
            $person = Person::create($request->all());
            
            // Sikeres válasz
            return response()->json([
                'success' => APP_TRUE,
                'message' => __('command_person_created', ['id' => $request->id]),
                'data' => $person
            ], Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            // Naplózza a cég létrehozása során észlelt adatbázis-hibát
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_PERSON_DATABASE_ERROR', // A hiba háttere
                'route' => request()->path(), // Útvonal, ahol a hiba történt
            ]);

            // Adatbázis hiba esetén a hiba részletes leírását is visszaküldi a kliensnek.
            // Ebben az esetben a HTTP-kód 422 lesz.
            return response()->json([
                'success' => APP_FALSE,
                'error' => __('command_person_create_database_error'), // A hiba részletes leírása
                'details' => $ex->getMessage(), // A hiba részletes leírása
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            // A cég létrehozása során fellépő általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'createPerson general error', // Adja meg a hiba kontextusát
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
    
    public function updatePerson(UpdatePersonRequest $request, int $id): JsonResponse
    {
        try{
            $person = Person::findOrFail($id);
            
            $success = $person->update($request->all());
            $person->refresh();
            
            return response()->json([
                'success' => APP_TRUE,
                'message' => 'PERSON_UPDATED_SUCCESSFULLY',
                'data' => $person,
            ], Response::HTTP_OK);
            
        } catch(ModelNotFoundException $ex) {
            // Ha a cég nem található
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_UPDATE_PERSON', // updateEntity nem található hiba
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'PERSON_NOT_FOUND', // A megadott entitás nem található
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_PERSON', // updatePerson adatbázishiba
                'route' => request()->path(),
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'DB_ERROR_PERSON', // Adatbázishiba történt a személy frissítése közben
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updatePerson general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deletePerson(GetPersonRequest $request): JsonResponse
    {
        try{
            $person = Person::findOrFail($request->id)->delete();
            
            return response()->json([
                'success' => APP_TRUE,
                'message' => 'Person deleted successfully.',
                'data' => $person,
            ], Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deletePerson error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Person not found',
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deletePerson database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error occurred while deleting the person.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deletePerson general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deletePersons(Request $request): JsonResponse
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
            
            // Válasz visszaküldése
            return response()->json([
                'success' => true,
                'message' => 'Selected persons deleted successfully.',
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
                'context' => 'deletePersons database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error occurred while deleting the selected persons.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            // Általános hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deletePersons general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
