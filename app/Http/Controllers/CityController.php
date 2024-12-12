<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCityRequest;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Services\CacheService;

class CityController extends Controller
{
    protected string $tag = 'cities';
    
    public function __construct()
    {
        //$this->middleware('can:city list', ['only' => ['index', '']]);
    }
    /**
     * Jelenítse meg az erőforrás listáját.
     *
     * Ez a módszer felelős a városok listájának megjelenítéséért.
     * Egy opcionális keresési paramétert vesz igénybe, és visszaadja a városok listáját
     * amelyek megfelelnek a keresési feltételeknek.
     *
     * @param  Request  $request
     * @return \Inertia\Response
     */
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

    /**
     * Keresse meg a városokat a keresési paraméter alapján.
     *
     * Ez a módszer a városok lekérdezését módosítja a keresési paraméter
     * alapján. Ha a keresési paraméter nem üres, akkor a lekérdezés
     * tartalmazza a feltételt, hogy a város neve tartalmazza a keresési
     * paramétert.
     *
     * @param  Builder  $query
     * @param  string  $search
     * @return Builder
     */
    public function applySearch(Builder $query, string $search)
    {
        return $query->when($search, function($query, string $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }

    public function getCities(Request $request, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5(json_encode($request->all()));
            $cities = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $cityQuery = City::search($request);
                return CityResource::collection($cityQuery->get());
            });
            
            return response()->json($cities, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getCities query error',
                'route' => $request->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCities query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getCities general error',
                'route' => $request->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCities general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Szerezzen várost azonosítóval.
     *
     * @param int $id A város id.
     * @return JsonResponse A város adatai JSON formátumban.
     */
    public function getCity(GetCityRequest $request, CacheService $cacheService)
    {
        try {
            $cacheKey = "{$this->tag}_{$request->id}";
            
            $city = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                return City::findOrFail($request->id);
            });
            
            return response()->json($city, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCity model not folund error',
                'param' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCity model not folund error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCity query error',
                'param' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCity query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCity general error',
                'param' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCity general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCityByName(string $name, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5($name);
            
            $city = $cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return City::where('name', '=', $name)->firstOrFail();
            });
            
            return response()->json($city, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompanyByName model not found error',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => "getCityByName model not found error"
            ], Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getCityByName query exception',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCityByName query exception'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch( Exception $ex ) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getCityByName general error',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createCity(StoreCityRequest $request, CacheService $cacheService): JsonResponse
    {
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
    }

    public function updateCity(UpdateCityRequest $request, int $id, CacheService $cacheService)
    {
        try {
            $city = null;
            
            \DB::transaction(function() use($request, $id, $cacheService, &$city) {
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

    /**
     * Töröljön egy várost az adatbázisból.
     *
     * A törölt város adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param int $id A törölni kívánt város azonosítója.
     * @return JsonResponse A törölt város adatait tartalmazó JSON-válasz.
     */
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
}
