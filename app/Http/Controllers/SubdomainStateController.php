<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetSubdomainStateRequest;
use App\Http\Requests\StoreSubdomainStateRequest;
use App\Http\Requests\UpdateSubdomainStateRequest;
use App\Http\Resources\SubdomainStateResource;
use Illuminate\Support\Facades\DB;
use App\Models\SubdomainState;
use App\Services\CacheService;
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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use App\Traits\Functions;
use App\Repositories\SubdomainStateRepository;

class SubdomainStateController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected string $tag = 'subdomainstate';

    protected SubdomainStateRepository $stateRepository;

    public function __construct(SubdomainStateRepository $repository)
    {
        $this->stateRepository = $repository;

        $this->middleware('can:subdomainstate list', ['only' => ['index', 'applySearch', 'getSubdomainStates', 'getSubdomainState', 'getSubdomainStateByName']]);
        $this->middleware('can:subdomainstate create', ['only' => ['createSubdomainState']]);
        $this->middleware('can:subdomainstate edit', ['only' => ['updateSubdomainStates']]);
        $this->middleware('can:subdomainstate delete', ['only' => ['deleteSubdomainState', 'deleteSubdomainStates']]);
        $this->middleware('can:subdomainstate restore', ['only' => ['restoreSubdomainState']]);
    }

    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles('subdomainstate');

        return Inertia::render('SubdomainState/Index', [
            'search' => $request->get('search'),
            'can' => $roles,
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
            $_subdomainStates = $this->stateRepository->getSubdomainStates($request);
            $subdomainStates = SubdomainState::collection($_subdomainStates);

            return response()->json($subdomainStates, Response::HTTP_OK);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getSubdomainStates query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getSubdomainStates general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSubdomainState(GetSubdomainStateRequest $request): JsonResponse
    {
        try {
            $subdomainState = $this->stateRepository->getSubdomainState($request->id);

            return response()->json($subdomainState, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getSubdomainState model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getSubdomainState query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getSubdomainState general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSubdomainStateByName(string $name): JsonResponse
    {
        try {
            $subdomainState = $this->stateRepository->getSubdomainStateByName($name);

            return response()->json($subdomainState, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getSubdomainStateByName model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getSubdomainStateByName query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getSubdomainStateByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createSubdomainState(StoreSubdomainStateRequest $request): JsonResponse
    {
        try {
            $subdomainState = $this->stateRepository->createSubdomainState($request);

            return response()->json($subdomainState, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'createSubdomainState query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createSubdomainState general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateSubdomainState(UpdateSubdomainStateRequest $request, int $id): JsonResponse
    {
        try {
            $subdomainState = $this->stateRepository->updateSubdomainState($request, $id);

            return response()->json($subdomainState, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updateSubdomainState model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updateSubdomainState query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updateSubdomainState general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteSubdomainStates(Request $request): JsonResponse
    {
        try {
            $deletedCount = $this->stateRepository->deleteSubdomainStates($request);

            return response()->json($deletedCount, Response::HTTP_OK);

        } catch(ValidationException $ex) {
            return $this->handleException($ex, 'deleteSubdomainStates validation error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteSubdomainStates database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteSubdomainStates general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteSubdomainState(GetSubdomainStateRequest $request): JsonResponse
    {
        try {
            $state = $this->stateRepository->deleteSubdomainState($request);

            return response()->json($state, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteSubdomainState model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteSubdomainState database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteSubdomainState general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreSubdomainState(GetSubdomainStateRequest $request): JsonResponse
    {
        try {
            $state = $this->stateRepository->restoreSubdomainState($request);

            return response()->json($state, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restoreSubdomainState model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restoreSubdomainState query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restoreSubdomainState general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
