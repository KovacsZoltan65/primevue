<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetEntityShiftRequest;
use App\Http\Requests\StoreEntityShiftRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Http\Resources\EntityShiftResource;
use App\Models\EntityShift;
use App\Repositories\EntityShiftRepository;
use App\Traits\Functions;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class EntityShiftController extends Controller
{
    use AuthorizesRequests,
        Functions;
    
    protected EntityShiftRepository $entityShiftRepository;
    protected string $tag = '';
    
    public function __construct(EntityShiftRepository $entityShiftRepository) {
        $this->entityShiftRepository = $entityShiftRepository;
        
        $this->tag = EntityShift::getTag();
        
        $this->middleware("can:{$this->tag} list", ['only' => ['index', 'applySearch', 'getEntitiesShifts', 'getEntityShift', 'getEntityShiftByName']]);
        $this->middleware("can:{$this->tag} create", ['only' => ['createEntityShift']]);
        $this->middleware("can:{$this->tag} edit", ['only' => ['updateEntityShift']]);
        $this->middleware("can:{$this->tag} delete", ['only' => ['deleteEntityShift', 'deleteEntitiesShifts']]);
        $this->middleware("can:{$this->tag} restore", ['only' => ['restoreEntityShift']]);
    }
    
    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles($this->tag);
        
        return Inertia::render('EntityShift/Index', [
            'search' => $request->input('search'),
            'can' => $roles,
        ]);
    }
    
    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function ($query, string $search) {
            $query->where('name', 'like', "%{$search}%");
        });
    }
    
    public function getActiveEntityShifts()
    {
        try {
            $entitiesShifts = $this->entityShiftRepository->getActiveEntityShifts();
            
            return $entitiesShifts;
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'getActiveEntityShifts query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'getActiveEntityShifts general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getEntitiesShifts(Request $request): JsonResponse
    {
        try {
            $_entitiesShifts = $this->entityShiftRepository->getEntitiesShifts($request);
            $entitiesShifts = EntityShiftResource::collection($_entitiesShifts);
            
            return response()->json($entitiesShifts, Response::HTTP_OK);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getEntitiesShifts query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getEntitiesShifts general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getEntityShift(GetEntityShiftRequest $request): JsonResponse
    {
        try {
            $entityShift = $this->entityShiftRepository->getEntityShift($request->id);
            
            return response()->json($entityShift, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getEntityShift model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getEntityShift query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getEntityShift general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getEntityShiftByName(string $name): JsonResponse
    {
        try {
            $entityShift = $this->entityShiftRepository->getEntityShiftByName($name);
            
            return response()->json($entityShift, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getEntityShiftByName model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getEntityShiftByName query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getEntityShiftByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function createEntityShift(StoreEntityShiftRequest $request): JsonResponse
    {
        try {
            $entityShift = $this->entityShiftRepository->createEntityShift($request);

            return response()->json($entityShift, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'createCompany query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createCompany general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function updateEntityShift(UpdateEntityRequest $request, int $id): JsonResponse
    {
        try {
            $entityShift = $this->entityShiftRepository->updateEntityShift($request, $id);

            return response()->json($entityShift, Response::HTTP_CREATED);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updateEntityShift model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updateEntityShift query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updateEntityShift general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteEntitiesShifts(Request $request): JsonResponse
    {
        try {
            $deletedCount = $this->entityShiftRepository->deleteEntitiesShifts($request);
            return response()->json($deletedCount, Response::HTTP_OK);
        } catch(ValidationException $ex) {
            return $this->handleException($ex, 'deleteEntitiesShifts validation error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteEntitiesShifts query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteEntitiesShifts general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteEntityShift(GetEntityShiftRequest $request): JsonResponse
    {
        try {
            $company = $this->companyRepository($request);

            return response()->json($company, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteEntityShift model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteEntityShift database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteEntityShift general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
