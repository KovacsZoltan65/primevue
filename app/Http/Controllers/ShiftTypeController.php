<?php

namespace App\Http\Controllers;

use App\Models\ShiftType;
use App\Repositories\ShiftTypeRepository;
use App\Traits\Functions;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
            //
        } catch( ModelNotFoundException $ex ) {
            //
        } catch( QueryException $ex ) {
            //
        } catch( Exception $ex ) {
            //
        }
    }
    
    public function getShiftType(int $id): JsonResponse
    {
        try {
            //
        } catch( ModelNotFoundException $ex ) {
            //
        } catch( QueryException $ex ) {
            //
        } catch( Exception $ex ) {
            //
        }
    }
    
    public function getShiftTypeByName(string $name): JsonResponse
    {
        try {
            //
        } catch( ModelNotFoundException $ex ) {
            //
        } catch( QueryException $ex ) {
            //
        } catch( Exception $ex ) {
            //
        }
    }
    
    public function createShiftType(Request $request): JsonResponse
    {
        try {
            //
        } catch( ModelNotFoundException $ex ) {
            //
        } catch( QueryException $ex ) {
            //
        } catch( Exception $ex ) {
            //
        }
    }
    
    public function updateShiftType($request, int $id): JsonResponse
    {
        try {
            //
        } catch( ModelNotFoundException $ex ) {
            //
        } catch( QueryException $ex ) {
            //
        } catch( Exception $ex ) {
            //
        }
    }
    
    public function deleteShiftTypes(Request $request): JsonResponse
    {
        try {
            //
        } catch( ModelNotFoundException $ex ) {
            //
        } catch( QueryException $ex ) {
            //
        } catch( Exception $ex ) {
            //
        }
    }
    
    public function deleteShiftType(Request $request): JsonResponse
    {
        try {
            //
        } catch( ModelNotFoundException $ex ) {
            //
        } catch( QueryException $ex ) {
            //
        } catch( Exception $ex ) {
            //
        }
    }
    
    public function restoreShiftType(Request $request): ShiftType
    {
        try {
            //
        } catch( ModelNotFoundException $ex ) {
            //
        } catch( QueryException $ex ) {
            //
        } catch( Exception $ex ) {
            //
        }
    }
    
    public function realDeleteShiftType(int $id): ShiftType
    {
        try {
            //
        } catch( ModelNotFoundException $ex ) {
            //
        } catch( QueryException $ex ) {
            //
        } catch( Exception $ex ) {
            //
        }
    }
}
