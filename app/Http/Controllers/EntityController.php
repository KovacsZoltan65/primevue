<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetEntityRequest;
use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Http\Resources\EntityResource;
use App\Models\Entity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class EntityController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        Inertia::render('Entity/Index',[
            'search' => request('search')
        ]);
    }

    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function($query, string $search) {
            // Adjon hozzá egy where záradékot a lekérdezéshez azzal a feltétellel, hogy a
            // az ország nevének tartalmaznia kell a keresési lekérdezést
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }

    public function getEntities(Request $request): JsonResponse
    {
        try {
            $entityQuery = Entity::Search($request);
            $entities = EntityResource::collection($entityQuery->get());
            
            return response()->json($entities, Response::HTTP_OK);
            
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_ENTITIES',
                'route' => $request->path(),
            ]);

            return response()->json([
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getEntities general error',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getEntity(GetEntityRequest $request): JsonResponse
    {
        try {
            $entity = Entity::findOrFail($id);
            
            return response()->json($entity, Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'getEntity error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Entity not found',
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_ENTITY',
                'route' => request()->path(),
            ]);

            return response()->json(['error' => 'Database error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch( Exception $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'getEntity general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getEntityByName(string $name): JsonResponse
    {
        try {
            // Entitás lekérdezése név alapján
            $entity = Entity::where('name', $name)->first();
            
            if( !$entity ) {   
                return response()->json(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
            }
            
            return response()->json($entity, Response::HTTP_OK);
        } catch( QueryException $ex ) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_ENTITY_BY_NAME',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch( Exception $ex ) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getEntityByName general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createEntity(StoreEntityRequest $request): JsonResponse
    {
        try{
            $entity = Entity::create($request->all());
            
            // Sikeres válasz
            return response()->json([
                'success' => true,
                'message' => __('command_entity_created', ['id' => $request->id]),
                'data' => $entity
            ], Response::HTTP_CREATED);
        }catch( QueryException $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_ENTITY_DATABASE_ERROR',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'CREATE_ENTITY_DATABASE_ERROR',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch( Exception $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'createEntity general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateEntity(UpdateEntityRequest $request, int $id): JsonResponse
    {
        try{
            // Keresse meg a frissítendő céget az azonosítója alapján
            $entity = Entity::findOrFail($id);
            // Frissítse a vállalatot a HTTP-kérés adataival
            $entity->update($request->all());
            // Frissítjük a modelt
            $entity->refresh();
            
            return reqponse()->json([
                'success' => APP_TRUE,
                'message' => 'ENTITY_UPDATED_SUCCESSFULLY',
                'data' => $entity,
            ], Response::HTTP_OK);
        }catch( ModelNotFoundException $ex ){
            // Ha a cég nem található
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_UPDATE_ENTITY', // updateEntity nem található hiba
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'ENTITY_NOT_FOUND', // A megadott entitás nem található
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }catch( QueryException $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_ENTITY', // updateEntity adatbázishiba
                'route' => request()->path(),
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'DB_ERROR_ENTITY', // Adatbázishiba történt az entitás frissítése közben
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch( Exception $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'updateEntity general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteEntity(GetEntityRequest $request): JsonResponse
    {
        try {
            // Keresse meg a törölni kívánt céget az azonosítója alapján
            $entity = Entity::findOrFail($id);
            
            $entity->delete();
            
            return request()->json([
                'success' => APP_TRUE,
                'message' => 'DELETE_ENTITY_SUCCESSFULLY', // A város sikeresen törölve
                'data' => $entity,
            ], Request::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompany error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Company not found',
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANY',
                'route' => request()->path(),
            ]);

            return response()->json(['error' => 'Database error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch( Exception $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompany general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteEntities(Request $request): JsonResponse
    {
        try {
            // Az azonosítók tömbjének validálása
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:entities,id', // Az id-k egész számok és létező cégek legyenek
            ]);
            
            // Az azonosítók kigyűjtése
            $ids = $validated['ids'];
            
            // A cégek törlése
            $deletedCount = Entity::whereIn('id', $ids)->delete();
            
            // Válasz visszaküldése
            return response()->json([
                'success' => true,
                'message' => 'Selected entities deleted successfully.',
                'deleted_count' => $deletedCount,
            ], Response::HTTP_OK);
        } catch( ValidationException $ex ) {
            // Validációs hiba logolása
            //ErrorController::logClientValidationError($request);
            ErrorController::logServerValidationError($ex, $request);

            // Kliens válasz
            return response()->json([
                'error' => 'Validation error occurred',
                'details' => $ex->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( QueryException $ex ) {
            // Adatbázis hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteEntities database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'Database error occurred while deleting the selected entities.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            // Általános hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteEntities general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
