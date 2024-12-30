<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetSubdomainRequest;
use App\Http\Requests\StoreSubdomainRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Resources\SubdomainResource;
use App\Models\Subdomain;
use App\Repositories\SubdomainRepository;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Services\CacheService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\Functions;

class SubdomainController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected SubdomainRepository $subdomainRepository;
    
    public function __construct(SubdomainRepository $subdomainRepository) {
        $this->subdomainRepository = $subdomainRepository;
        
        $tag = Subdomain::getTag();
        $this->middleware("can:{$tag} list", ['only' => ['index', 'applySearch', 'getSubdomains', 'getSubdomain', 'getSubdomainByName']]);
        $this->middleware("can:{$tag} create", ['only' => ['createSubdomain']]);
        $this->middleware("can:{$tag} edit", ['only' => ['updateSubdomain']]);
        $this->middleware("can:{$tag} delete", ['only' => ['deleteSubdomain', 'deleteSubdomains']]);
        $this->middleware("can:{$tag} restore", ['only' => ['restoreSubdomain']]);
    }
    
    public function index(Request $request): InertiaResponse
    {
        return Inertia::render('Subdomain/Index', [
            'search' => $request->get('search')
        ]);
    }
    
    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function($query, string $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }
    
    public function getSubdomains(Request $request): JsonResponse
    {
        try {
            $_subdomains = $this->subdomainRepository->getSubdomains($request);
            $subdomains = SubdomainResource::collection($_subdomains);
            
            return response()->json($subdomains, Response::HTTP_OK);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getSubdomains query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getSubdomains general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getSubdomain(GetSubdomainRequest $request): JsonResponse
    {
        try {
            $subdomain = $this->subdomainRepository->getSubdomain($request->id);

            return response()->json($subdomain, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getSubdomain model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getSubdomain query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getSubdomain general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getSubdomainByName(string $name): JsonResponse
    {
        try {
            $subdomain = $this->subdomainRepository->getSubdomainByName($name);

            return response()->json($subdomain, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getSubdomainByName model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getSubdomainByName query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getSubdomainByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function createSubdomain(StoreSubdomainRequest $request): JsonResponse
    {
        try {
            $subdomain = Subdomain::create($request->all());
            
            return response()->json($subdomain, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'createSubdomain query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createSubdomain general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function updateSubdomain(UpdateCityRequest $request, int $id): JsonResponse
    {
        try {
            $subdomain = $this->subdomainRepository->updateSubdomain($request, $id);
            
            return response()->json($subdomain, Response::HTTP_OK);
            
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updateSubdomain model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updateSubdomain query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updateSubdomain general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteSubdomains(Request $request, CacheService $cacheService): JsonResponse
    {
        try {
            $deletedCount = $this->subdomainRepository->deleteSubdomains($request);
            
            return response()->json($deletedCount, Response::HTTP_OK);
        } catch( ValidationException $ex ){
            return $this->handleException($ex, 'deleteSubdomains validation error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteSubdomains database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteSubdomains general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteSubdomain(GetSubdomainRequest $request): JsonResponse
    {
        try {
            $subdomain = $this->subdomainRepository->deleteSubdomain($request);
            
            return response()->json($subdomain, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteSubdomain model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteSubdomain database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteSubdomain general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function restoreSubdomain(GetSubdomainRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $subdomain = $this->subdomainRepository->restoreSubdomain($request);
            
            return response()->json($subdomain, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restoreSubdomain model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restoreSubdomain query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restoreSubdomain general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
