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
use Exception;
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
            return $this->handleException($ex, 'getCities query error', Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return $this->handleException($ex, 'getCity query error', Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return $this->handleException($ex, 'getCityByName query exception', Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getCityByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createCity(StoreCityRequest $request): JsonResponse
    {
        try {
            //
        } catch(QueryException $ex) {
            //
        } catch(Exception $ex) {
            //
        }


        /*
        try{
            $city = City::create($request->all());
            
            $cacheService->forgetAll($this->tag);
            
            return response()->json($city, Response::HTTP_CREATED);
        }catch(QueryException $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'createCity exception error',
                'params' => ['request' => $request],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'createCity exception error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch(Exception $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'createCity general error',
                'params' => ['request' => $request],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'createCity general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        */
    }

    public function updateCity(UpdateCityRequest $request, int $id, CacheService $cacheService)
    {
        try {
            $city = null;
            
            DB::transaction(function() use($request, $id, $cacheService, &$city) {
                $city = City::findOrFail($id);
                $city->update($request->all())->lockForUpdate();
                $city->refresh();
                $cacheService->forgetAll($this->tag);
            });
            
            // A frissített vállalatot JSON-válaszként küldje vissza sikeres állapotkóddal
            return response()->json($city, Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_UPDATE_CITY',
                'params' => ['id' => $id, 'request' => $request],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'CITY_NOT_FOUND',
            ], Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            //
            ErrorController::logServerError($ex, [
                'context' => 'updateCity query error',
                'params' => ['id' => $id, 'request' => $request],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);
            
            return response()->json([
                'error' => 'updateCity query error',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            //
            ErrorController::logServerError($ex, [
                'context' => 'updateCity general error',
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateCity general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCity(GetCityRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            // Keresse meg a törölni kívánt céget az azonosítója alapján
            $city = City::findOrFail($request->id);
            $city->delete();
            
            $cacheService->forgetAll($this->tag);
            
            return request()->json($city, Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteCity model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'COUNTRY_NOT_FOUND',
            ], Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteCity database error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteCity database error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteCity general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteCity database error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteCities(Request $request, CacheService $cacheService): JsonResponse
    {
        try{
            // Az azonosítók tömbjének validálása
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:companies,id', // Az id-k egész számok és létező cégek legyenek
            ]);
            // Az azonosítók kigyűjtése
            $ids = $validated['ids'];
            // A cégek törlése
            $deletedCount = City::whereIn('id', $ids)->delete();
            
            $cacheService->forgetAll($this->tag);
            
            // Válasz visszaküldése
            return response()->json($deletedCount, Response::HTTP_OK);
        }catch( ValidationException $ex ){
            // Validációs hiba logolása
            ErrorController::logServerValidationError($ex, $request);

            // Kliens válasz
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteCities validation error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch( QueryException $ex ){
            // Adatbázis hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteCities database error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteCities database error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch( Exception $ex ){
            // Általános hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteCities general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteCities general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function handleException(Exception $ex, string $defaultMessage, int $statusCode)
    {
        return response()->json([
            'success' => App_FALSE,
            'error' => $defaultMessage,
        ], $statusCode);
    }
}
