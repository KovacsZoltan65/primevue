<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetWorkplanRequest;
use App\Http\Requests\StoreWorkplanRequest;
use App\Http\Requests\UpdateWorkplanRequest;
use App\Http\Resources\WorkplanResource;
use App\Models\Workplan;
use App\Services\Workplans\WorkplanService;
use App\Traits\Functions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Routing\Controller;
use Exception;
use Illuminate\Validation\ValidationException;

class WorkplanController extends Controller
{
    use AuthorizesRequests,
        Functions;

    //protected WorkplanRepository $workplanRepository;
    protected WorkplanService $workplanService;

    protected string $tag = '';

    public function __construct(WorkplanService $workplanService)
    {
        $this->tag = Workplan::getTag();

        //$this->workplanRepository = $workplanRepository;
        $this->workplanService = $workplanService;

        $this->middleware("can:{$this->tag} list", ['only' => ['index', 'applySearch', 'getWorkplans', 'getWorkplan', 'getWorkplanByName']]);
        $this->middleware("can:{$this->tag} create", ['only' => ['createWorkplan']]);
        $this->middleware("can:{$this->tag} edit", ['only' => ['updateWorkplan']]);
        $this->middleware("can:{$this->tag} delete", ['only' => ['deleteWorkplan', 'deleteWorkplans']]);
        $this->middleware("can:{$this->tag} restore", ['only' => ['restoreWorkplan']]);
    }

    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles($this->tag);

        return Inertia::render('Workplans/Index', [
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

    public function getActiveWorkplans(): JsonResponse
    {
        try{
            $workplans = $this->workplanService->getActiveWorkplans();

            return response()->json($workplans, Response::HTTP_OK);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getActiveWorkplans query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getActiveWorkplans general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getWorkplans(Request $request): JsonResponse
    {
        try {
            $_workplans = $this->workplanService->getWorkplans($request);
            $workplans = WorkplanResource::collection($_workplans);

            return response()->json($workplans, Response::HTTP_OK);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'getWorkplans query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'getWorkplans general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getWorkplan(GetWorkplanRequest $request): JsonResponse
    {
        try {
            $workplan = $this->workplanService->getWorkplan($request->id);

            return response()->json($workplan, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getWorkplan model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getWorkplan query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getWorkplan general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getWorkplanByName(string $name): JsonResponse
    {
        try {
            $workplan = $this->workplanService->getWorkplanByName($name);

            return response()->json($workplan, Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getWorkplanByName model not found error', Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'getWorkplanByName query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'getWorkplanByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createWorkplan(StoreWorkplanRequest $request): JsonResponse
    {
        try {
            $workplan = $this->workplanService->createWorkplan($request);

            return response()->json($workplan, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'createWorkplan query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createWorkplan general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateWorkplan(UpdateWorkplanRequest $request, int $id): JsonResponse
    {
        try{
            $workplan = $this->workplanService->updateWorkplan($request, $id);

            return response()->json($workplan, Response::HTTP_CREATED);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updateWorkplan model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updateWorkplan query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updateWorkplan general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteWorkplans(Request $request): JsonResponse
    {
        try {
            $deletedCount = $this->workplanService->deleteWorkplan($request);
            return response()->json($deletedCount, Response::HTTP_OK);

        } catch(ValidationException $ex) {
            return $this->handleException($ex, 'deleteWorkplans validation error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteWorkplans query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteWorkplans general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteWorkplan(GetWorkplanRequest $request): JsonResponse
    {
        try {
            $workplan = $this->workplanService($request);

            return response()->json($workplan, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteWorkplan model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteWorkplan database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteWorkplan general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreWorkplan(GetWorkplanRequest $request): JsonResponse
    {
        try {
            $workplan = $this->workplanService->restoreWorkplan($request);

            return response()->json($workplan, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restoreWorkplan model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restoreWorkplan query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restoreWorkplan general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function realDeleteWorkplan(GetWorkplanRequest $request): JsonResponse
    {
        try {
            $deletedCount = $this->workplanService->realDeleteWorkplan($request->id);

            return response()->json($deletedCount, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'realDeleteWorkplan model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'realDeleteWorkplan query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'realDeleteWorkplan general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
