<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCityRequest;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use App\Repositories\CityRepository;
use App\Traits\Functions;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Services\CacheService;

class CityController extends Controller
{
    use AuthorizesRequests,
        Functions;
    protected $cityRepository;
    
    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
        //$this->middleware('can:city list', ['only' => ['index', '']]);
    }
    
    public function index(Request $request)
    {
        $countries = Country::where('active', 1)->orderBy('name')->get()->toArray();
        $regions = Region::where('active', 1)->orderBy('name')->get()->toArray();

        return Inertia::render('Geo/City/Index', [
            //'cities' => $cities,
            'countries' => $countries,
            'regions' => $regions,
            'search' => $request->get('search'),
        ]);

    }

    public function applySearch(Builder $query, string $search)
    {
        return $query->when($search, function($query, string $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }

    public function getCities(Request $request): JsonResponse
    {
        try {
            $cities = $this->cityRepository->getCities($request);
            $cities = CityResource::collection($cities);

            return response()->json($cities, Response::HTTP_OK);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getCities query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getCities general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCity(GetCityRequest $request)
    {
        try {
            $city = $this->cityRepository->getCity($request->id);

            return response()->json($city, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getCity model not folund error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getCity query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getCity general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCityByName(string $name): JsonResponse
    {
        try {
            $city = $this->cityRepository->getCityByName($name);

            return response()->json($city, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getCompanyByName model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getCityByName query exception', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getCityByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createCity(StoreCityRequest $request): JsonResponse
    {
        try {
            $city = $this->cityRepository->createCity($request);
            
            return response()->json($city, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'createCity query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createCity general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCity(UpdateCityRequest $request, int $id): JsonResponse
    {
        try{
            $company = $this->updateCity($request, $id);
            
            return response()->json($company, Response::HTTP_CREATED);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updateCompany model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updateCompany query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updateCompany general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCities(Request $request): JsonResponse
    {
        try {
            $deletedCount = $this->cityRepository->deleteCompanies($request);
            return response()->json($deletedCount, Response::HTTP_OK);
            
        } catch(ValidationException $ex) {
            return $this->handleException($ex, 'deleteCities model not found error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteCities query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteCities general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteCity(GetCityRequest $request): JsonResponse
    {
        try {
            $city = $this->cityRepository->deleteCity($request);
            
            return response()->json($city, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteCity model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteCity database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteCity database error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function restoreCity(GetCityRequest $request): JsonResponse
    {
        try {
            $city = $this->cityRepository->restoreCity($request);

            return response()->json($city, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restoreCity model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restoreCity query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restoreCity general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }   
}
