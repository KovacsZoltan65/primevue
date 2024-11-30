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
use function request;
use function response;

class SubdomainStateController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request): InertiaResponse
    {
        return Inertia::render('SubdomainState/Index', [
            'search' => $request->get('search'),
        ]);
    }

    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function($query, string $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }

    public function getSubdomainStates(Request $request): JsonResponse
    {
        try {
            // A cégek listájának lekérése a request paraméterei alapján
            $subdomainStateQuery = Company::search($request);

            // JSON válaszként adja vissza a cégeket
            $subdomainStates = SubdomainStateResource::collection($subdomainStateQuery->get());

            return response()->json($subdomainStates, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_SUBDOMAIN_STATE',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomainStates general error',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSubdomainState(GetSubdomainStateRequest $request): JsonResponse
    {
        try {
            $subdomainState = SubdomainState::findOrFail($request->id);

            return response()->json($subdomainState, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomainState error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSubdomainState not found'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_SUBDOMAIN_STATE',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSubdomainState general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSubdomainStateByName(string $name): JsonResponse
    {
        try {
            $subdomainState = SubdomainState::where('name', '=', $name)->first();
            if(!$subdomainState)
            {
                return response()->json([
                    'success' => APP_FALSE,
                    'error' => 'SubdomainState not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json($subdomainState, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANY_BY_NAME',
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
                'context' => 'getCompanyByName general error',
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

    public function createSubdomainState(StoreSubdomainStateRequest $request): JsonResponse
    {
        try {
            $subdomainState = SubdomainState::create($request->all());

            return response()->json([
                'success' => APP_TRUE,
                'message' => __('command_subdomain_state_created', ['id' => $request->id]),
                'data' => $subdomainState
            ], Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            // Naplózza a cég létrehozása során észlelt adatbázis-hibát
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_SUBDOMAIN_STATE_DATABASE_ERROR', // A hiba háttere
                'route' => request()->path(), // Útvonal, ahol a hiba történt
            ]);

            // Adatbázis hiba esetén a hiba részletes leírását is visszaküldi a kliensnek.
            // Ebben az esetben a HTTP-kód 422 lesz.
            return response()->json([
                'success' => APP_FALSE,
                'error' => __('command_company_create_database_error'), // A hiba részletes leírása
                'details' => $ex->getMessage(), // A hiba részletes leírása
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            // A cég létrehozása során fellépő általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'createSubdomainState general error',
                'route' => request()->path(),
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

    public function updateSubdomainState(UpdateSubdomainStateRequest $request, int $id): JsonResponse
    {
        try {
            $subdomainState = SubdomainState::findOrFail($id);
            $subdomainState->update($request->all());
            $subdomainState->refresh();

            return response()->json([
                'success' => APP_TRUE,
                'message' => 'SUBDOMAIN_STATE_UPDATED_SUCCESSFULLY',
                'data' => $subdomainState,
            ], Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_UPDATE_SUBDOMAIN_STATE',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'SUBDOMAIN_STATE_NOT_FOUND',
                'details' => $ex->getMessage(),
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
                'context' => 'updateSubdomainState general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteSubdomainState(GetSubdomainStateRequest $request): JsonResponse
    {
        try {
            $subdomainState = SubdomainState::findOrFail($request->id);
            $subdomainState->delete();

            return response()->json([
                'success' => APP_TRUE,
                'message' => 'Subdomain State deleted successfully.',
                'data' => $subdomainState,
            ], Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomainState error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Subdomain State not found',
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomainState database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error occurred while deleting the company.',
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

    public function deleteSubdomainStates(Request $request): JsonResponse
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
            $deletedCount = SubdomainState::whereIn('id', $ids)->delete();

            // Válasz visszaküldése
            return response()->json([
                'success' => true,
                'message' => 'Selected subdomsin state deleted successfully.',
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
                'context' => 'deleteSubdomainStates database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error occurred while deleting the selected companies.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            // Általános hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteSubdomainStates general error',
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
