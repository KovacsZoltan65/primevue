<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetEntityShiftRequest;
use App\Http\Requests\GetShiftTypeRequest;
use App\Http\Requests\StoreShiftTypeRequest;
use App\Http\Requests\UpdateShiftTypeRequest;
use App\Http\Resources\ShiftTypeResource;
use Illuminate\Routing\Controller;
use App\Models\ShiftType;
use App\Repositories\ShiftTypeRepository;
use App\Traits\Functions;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Database\Eloquent\Builder;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class ShiftTypeController extends Controller
{
    use AuthorizesRequests, 
        Functions;
    
    protected $shiftTypeRepository;
    
    protected string $tag = '';
    
    public function __construct(ShiftTypeRepository $shiftTypeRepository) {
        $this->shiftTypeRepository = $shiftTypeRepository;
        
        $this->tag = ShiftType::getTag();
        
        $this->middleware("can:{$this->tag} list", ['only' => ['index', 'applySearch', 'getShiftTypes', 'getShiftType', 'getShiftTypeByName']]);
        $this->middleware("can:{$this->tag} create", ['only' => ['createShiftType']]);
        $this->middleware("can:{$this->tag} edit", ['only' => ['updateShiftType']]);
        $this->middleware("can:{$this->tag} delete", ['only' => ['deleteShiftType', 'deleteShiftTypes']]);
        $this->middleware("can:{$this->tag} restore", ['only' => ['restoreShiftType']]);
    }
    
    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles($this->tag);
        
        return Inertia::render('ShiftType/Index', [
            'searct' => $request->input('search'),
            'can' => $roles,
        ]);
    }
    
    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function ($query, string $search) {
            $query->where('name', 'like', "%{$search}%");
        });
    }
    
    public function getActiveShiftTypes(): JsonResponse
    {
        try {
            $shifTypes = $this->shiftTypeRepository->getActiveShiftTypes();
            
            return response()->json($shifTypes, Response::HTTP_OK);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'getActiveShiftTypes query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'getActiveShiftTypes general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getShiftTypes(Request $request): JsonResponse
    {
        try {
            $_shiftTypes = $this->shiftTypeRepository->getShiftTypes($request);
            $shiftTypes = ShiftTypeResource::collection($_shiftTypes);

            return response()->json($shiftTypes, Response::HTTP_OK);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'getShiftTypes query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'getShiftTypes general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getShiftType(GetShiftTypeRequest $request): JsonResponse
    {
        try {
            $_shiftType = $this->shiftTypeRepository->getShiftType($request->id);
            $shiftType = new ShiftTypeResource($_shiftType);

            return response()->json($shiftType, Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getShiftType model not found error', Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'getShiftType query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'getShiftType general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getShiftTypeByName(string $name): JsonResponse
    {
        try {
            $shiftType = $this->shiftTypeRepository->getShiftTypeByName($name);

            return response()->json($shiftType, Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getShiftTypeByName model not found error', Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'getShiftTypeByName query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'getShiftTypeByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function createShiftType(StoreShiftTypeRequest $request): JsonResponse
    {
        try {
            $shiftType = $this->shiftTypeRepository->createShiftType($request);

            return response()->json($shiftType, Response::HTTP_CREATED);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'createShiftType query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'createShiftType general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function updateShiftType(UpdateShiftTypeRequest $request, int $id): JsonResponse
    {
        try {
            $shiftType = $this->shiftTypeRepository->updateShiftType($request, $id);

            return response()->json($shiftType, Response::HTTP_CREATED);
        } catch( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'updateShiftType model not found error', Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'updateShiftType query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'updateShiftType general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteShiftTypes(Request $request): JsonResponse
    {
        try {
            $deletedCount = $this->shiftTypeRepository->deleteShiftTypes($request);

            return response()->json($deletedCount, Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'deleteShiftTypes validation error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'deleteShiftTypes query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'deleteShiftTypes general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteShiftType(GetEntityShiftRequest $request): JsonResponse
    {
        try {
            $shiftType = $this->shiftTypeRepository->deleteShiftType($request);

            return response()->json($shiftType, Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'deleteShiftType model not found error', Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'deleteShiftType database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'deleteShiftType general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function restoreShiftType(Request $request): JsonResponse
    {
        try {
            $shiftType = $this->shiftTypeRepository->restoreShiftType($request);

            return response()->json($shiftType, Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'restoreShiftType model not found error', Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'restoreShiftType database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'restoreShiftType general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function realDeleteShiftType(GetShiftTypeRequest $request): JsonResponse
    {
        try {
            $shiftType = $this->shiftTypeRepository->realDeleteShiftType($request->id);

            return response()->json($shiftType, Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'realDeleteShiftType model not found exception', Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'realDeleteShiftType query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'realDeleteShiftType general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
