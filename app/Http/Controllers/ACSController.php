<?php

namespace App\Http\Controllers;

use App\Models\ACS;
use App\Repositories\ACSRepository;
use App\Traits\Functions;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use App\Http\Resources\ACSResource;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Exception;

use App\Http\Requests\GetACSRequest;
use App\Http\Requests\StoreACSRequest;
use App\Http\Requests\UpdateACSRequest;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ACSController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected ACSRepository $acsRepository;
    protected string $tag = '';

    public function __construct(ACSRepository $repository)
    {
        $this->acsRepository = $repository;

        $this->tag = ACS::getTag();

        $this->middleware("can:{$this->tag} list", ['only' => ['index', 'applySearch', 'getACSs', 'getACS', 'getACSByName']]);
        $this->middleware("can:{$this->tag} create", ['only' => ['createACS']]);
        $this->middleware("can:{$this->tag} edit", ['only' => ['updateACS']]);
        $this->middleware("can:{$this->tag} delete", ['only' => ['deleteACS', 'deleteACSs']]);
        $this->middleware("can:{$this->tag} restore", ['only' => ['restoreACS']]);
    }

    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles($this->tag);

        // Adjon vissza egy Inertia választ a vállalatok és a keresési paraméterek megadásával.
        return Inertia::render("ACSs/Index", [
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

    public function getACSs(Request $request): JsonResponse
    {
        d('cont getACSs');
        try {
            $_acs = $this->acsRepository->getCompanies($request);
            $acs = ACSResource::collection($_acs);

            return response()->json($acs, Response::HTTP_OK);

        } catch (QueryException $ex) {
            return $this->handleException($ex, 'getACSs query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'getACSs general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getACS(GetACSRequest $request): JsonResponse
    {
        try {
            $acs = $this->acsRepository->getACS($request->id);

            return response()->json($acs, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getACS model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getACS query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getACS general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getACSByName(string $name): JsonResponse
    {
        try {
            $acs = $this->acsRepository->getACSByName($name);

            return response()->json($acs, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getACSByName model not found error', Response::HTTP_NOT_FOUND);
        } catch (QueryException $ex) {
            return $this->handleException($ex, 'getACSByName query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'getACSByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createACS(StoreACSRequest $request): JsonResponse
    {
        try {
            $acs = $this->acsRepository->createACS($request);

            return response()->json($acs, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'createACS query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createACS general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateACS(UpdateACSRequest $request, int $id): JsonResponse
    {
        try{
            $acs = $this->acsRepository->updateACS($request, $id);

            return response()->json($acs, Response::HTTP_CREATED);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updateACS model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updateACS query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updateACS general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteACSs(Request $request): JsonResponse
    {
        try {
            $deletedCount = $this->acsRepository->deleteACSs($request);
            return response()->json($deletedCount, Response::HTTP_OK);

        } catch(ValidationException $ex) {
            return $this->handleException($ex, 'deleteACSs validation error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteACSs query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteACSs general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteACS(GetACSRequest $request): JsonResponse
    {
        try {
            $acs = $this->acsRepository($request);

            return response()->json($acs, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteACS model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteACS database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteACS database error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreACS(GetACSRequest $request): JsonResponse
    {
        try {
            $acs = $this->acsRepository->restoreACS($request);

            return response()->json($acs, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restoreACS model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restoreACS query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restoreACS general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function realDeleteACS(GetACSRequest $request)
    {
        try {
            $deletedCount = $this->acsRepository->realDeleteACS($request->id);

            return response()->json($deletedCount, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'realDeleteCompany model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'realDeleteCompany query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'realDeleteCompany general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
