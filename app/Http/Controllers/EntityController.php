<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Http\Resources\EntityResource;
use App\Models\Entity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class EntityController extends Controller
{
    public function __construct() {
        //
    }

    public function index(Request $request){
        Inertia::render('Entity/Index',[
            'search' => request('search')
        ]);
    }

    public function applySearch(Builder $query, string $search)
    {
        return $query->when($search, function($query, string $search) {
            // Adjon hozzá egy where záradékot a lekérdezéshez azzal a feltétellel, hogy a
            // az ország nevének tartalmaznia kell a keresési lekérdezést
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }

    public function getEntities(Request $request)
    {
        $entityQuery = Entity::Search($request);
        $entities = EntityResource::collection($entityQuery->get());

        return $entities;
    }

    public function getEntity(int $id)
    {
        $entity = Entity::find($id);

        return response()->json($entity, Response::HTTP_OK);
    }

    public function getEntityByName(string $name)
    {
        $entity = Entity::where('name', $name)->first();

        return response()->json($entity, Response::HTTP_OK);
    }

    public function createEntity(StoreEntityRequest $request)
    {
        try{
            $entity = Entity::create($request->all());
            
            // Sikeres válasz
            return response()->json([
                'success' => true,
                'message' => __('command_entity_created', ['id' => $request->id]),
                'data' > $entity
            ], Response::HTTP_CREATED);
        }catch( QueryException $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_ENTITY_DATABASE_ERROR',
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'CREATE_ENTITY_DATABASE_ERROR',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch( Exception $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'createEntity general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateEntity(UpdateEntityRequest $request, int $id)
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
                'context' => 'DB_ERROR_UPDATE_ENTITY', // updateEntity not found error
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'COUNTRY_NOT_FOUND', // The specified entity was not found
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }catch( QueryException $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_ENTITY', // updateEntity database error
                'route' => request()->path(),
            ]);
            
            return response()->json([
                'error' => 'DB_ERROR_ENTITY', // Database error occurred while updating the entity
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch( Exception $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'updateEntity general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteEntity(int $id)
    {
        try{
            // Keresse meg a törölni kívánt céget az azonosítója alapján
            $entity = Entity::findOrFail($id);
            
            //
            $entity->delete();
            
            return request()->json([
                'success' => APP_TRUE,
                'message' => 'DELETE_ENTITY_SUCCESSFULLY', // City deleted successfully
                'data' => $entity,
            ], Request::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            // Ha a cég nem található
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_UPDATE_ENTITY', // updateEntity not found error
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'ENTITY_NOT_FOUND', // The specified entity was not found
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }catch( QueryException $ex ){
            //
            ErrorController::logServerError($ex, [
                'context' => 'deleteEntity database error',
                'route' => request()->path(),
            ]);
            //
            return response()->json([
                'error' => 'Database error occurred while deleting the entity.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch( Exception $ex ){
            //
            ErrorController::logServerError($ex, [
                'context' => 'deleteEntity general error',
                'route' => request()->path(),
            ]);
            
            //
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        
        
        
        $entity = Entity::find($id);
        $success = $entity->delete();

        return response()->json(['success' => $success], Response::HTTP_OK);
    }
    
    public function deleteEntities()
    {
        //
    }
}
