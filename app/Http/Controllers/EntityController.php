<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetEntityRequest;
use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Http\Resources\EntityResource;
use App\Models\Entity;
use App\Repositories\EntityRepository;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Services\CacheService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\Functions;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class EntityController extends Controller
{
    use AuthorizesRequests,
        Functions;
    protected EntityRepository $entityRepository;
    
    protected string $tag = 'entities';

    public function __construct(EntityRepository $repository)
    {
        $this->entityRepository = $repository;

        $tag = Entity::getTag();
        
        $this->middleware("can:{$tag} list", ['only' => ['index', 'applySearch', 'getEntities', 'getEntity', 'getEntityByName']]);
        $this->middleware("can:{$tag} create", ['only' => ['createEntity']]);
        $this->middleware("can:{$tag} edit", ['only' => ['updateEntity']]);
        $this->middleware("can:{$tag} delete", ['only' => ['deleteEntity', 'deleteEntities']]);
        $this->middleware("can:{$tag} restore", ['only' => ['restoreEntity']]);
    }

    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles($this->tag);
        
        return Inertia::render('Entity/Index',[
            'search' => request('search'),
            'can' => $roles,
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
            $_entities = $this->entityRepository->getEntities($request);
            $entities = EntityResource::collection($_entities);

            return response()->json($entities, Response::HTTP_OK);

        } catch (QueryException $ex) {
            \Log::info('EntityController QueryException $ex: ' . print_r($ex, true));
            return $this->handleException($ex, 'getEntities query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            \Log::info('EntityController Exception $ex: ' . print_r($ex, true));
            return $this->handleException($ex, 'getEntities general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getEntity(GetEntityRequest $request): JsonResponse
    {
        try {
            $company = $this->entityRepository->getEntity($request->id);

            return response()->json($company, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getEntity model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getEntity query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getEntity general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getEntityByName(string $name): JsonResponse
    {
        try {
            $company = $this->entityRepository->getEntityByName($name);

            return response()->json($company, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getEntityByName model not found error', Response::HTTP_NOT_FOUND);
        } catch (QueryException $ex) {
            return $this->handleException($ex, 'getEntityByName query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'getEntityByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createEntity(StoreEntityRequest $request): JsonResponse
    {
        try {
            $company = $this->entityRepository->createEntity($request);

            return response()->json($company, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'createEntity query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createEntity general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateEntity(UpdateEntityRequest $request, int $id, CacheService $cacheService): JsonResponse
    {
        try{
            $entity = $this->entityRepository->updateEntity($request, $id);

            return response()->json($entity, Response::HTTP_CREATED);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updateEntity model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updateEntity query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updateEntity general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteEntities(Request $request): JsonResponse
    {
        try {
            $deletedCount = $this->entityRepository->deleteEntities($request);
            return response()->json($deletedCount, Response::HTTP_OK);

        } catch(ValidationException $ex) {
            return $this->handleException($ex, 'deleteEntities model not found error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteEntities query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteEntities general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteEntity(GetEntityRequest $request): JsonResponse
    {
        try {
            $entity = $this->entityRepository->deleteEntity($request);

            return response()->json($entity, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteEntity model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteEntity database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteEntity database error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreEntity(GetEntityRequest $request): JsonResponse
    {
        try {
            $company = $this->entityRepository->restoreEntity($request);

            return response()->json($company, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restoreEntity model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restoreEntity query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restoreEntity general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
