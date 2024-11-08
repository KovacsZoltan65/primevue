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
        $entity = Entity::create($request->all());

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
}
