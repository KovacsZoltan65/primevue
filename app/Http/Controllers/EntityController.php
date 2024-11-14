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

    public function index(Request $request)
    {
        $companies = \App\Models\Company::select('id', 'name')
            ->orderBy('name')->get()->toArray();
        
        return Inertia::render('Entity/Index', [
            'search' => $request->get('search'),
            'companies' => $companies
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
        //\Log::info('$request->all(): ', $request->all());
        $entity = Entity::create($request->all());
\Log::info('$entity' . print_r($entity, true));
        return response()->json($entity, Response::HTTP_OK);
    }

    public function updateEntity(UpdateEntityRequest $request, int $id)
    {
        $old_entity = Entity::find($id);

        $success = $old_entity->update($request->all());

        return response()->json(['success' => $success], Response::HTTP_OK);
    }

    public function deleteEntity(int $id)
    {
        $entity = Entity::find($id);
        $success = $entity->delete();

        return response()->json(['success' => $success], Response::HTTP_OK);
    }

    public function deleteEntities(Request $request)
    {
        \Log::info('deleteEntities', $request->all());

        $ids = collect($request->input('entities'))->pluck('id')->all();
        \Log::info('deleteEntities ids: ' . print_r($ids, true));

        if (empty($ids)) {
            return response()->json([
                'success' => false, 
                'message' => 'No valid IDs provided'
            ], Response::HTTP_BAD_REQUEST);
        }

        \DB::enableQueryLog();

        // A rekordok törlése a kigyűjtött ID-k alapján
        $deleted = Entity::whereIn('id', $ids)->delete();

        $queries = \DB::getQueryLog();
        \Log::info('$queries: ' . print_r($queries, true));
        \DB::disableQueryLog();

        // Ellenőrizzük, hogy sikerült-e bármilyen rekordot törölni
        $success = $deleted > 0;

        return response()->json([
            'success' => $success, 
            'deleted_count' => $deleted
        ], Response::HTTP_OK);
    }
}
