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
use Inertia\Inertia;
use Nette\Schema\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CityController extends Controller
{
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

    public function getCities(Request $request): JsonResponse
    {
        try {
            $cityQuery = City::search($request);
            $cities = CityResource::collection($cityQuery->get());
            
            return response()->json($cities, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANIES',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getCities general error',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Szerezzen várost azonosítóval.
     *
     * @param int $id A város id.
     * @return JsonResponse2 A város adatai JSON formátumban.
     */
    public function getCity(GetCityRequest $request)
    {
        try {
            $city = City::findOrFail($request->id);
            
            return response()->json($city, Request::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCity error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'City not found'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_CITY',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCity general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCityByName(string $name): JsonResponse
    {
        try {
            $city = Company::where('name', '=', $name)->first();
            if ( !$city ) {
                return response()->json([
                    'success' => APP_FALSE,
                    'error' => 'City not found'
                ], Response::HTTP_NOT_FOUND);
            }
            
            return response()->json($city, Response::HTTP_OK);
        } catch( QueryException $ex ) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_CITY_BY_NAME',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch( Exception $ex ) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getCityByName general error',
                'route' => request()->path(),
            ]);

            // JSON-választ küld vissza, jelezve, hogy váratlan hiba történt
            return response()->json([
                'success' => APP_FALSE, // A művelet nem volt sikeres
                'error' => 'An unexpected error occurred' // Hibaüzenet a visszatéréshez
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // HTTP állapotkód belső szerverhiba miatt
        }
    }

    public function createCity(StoreCityRequest $request): JsonResponse
    {
        try{
            // Hozzon létre új várost az adatbázisban
            $city = City::create($request->all());
            
            return response()->json([
                'success' => APP_TRUE,
                'message' => 'CITY CREATED',
                'data' => $city,
            ], Response::HTTP_CREATED);
        }catch(QueryException $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_CITY_DATABASE_ERROR',
                'route' => request()->path(),
            ]);
            
            return response()->json([
                'error' => 'CREATE_CITY_DATABASE_ERROR',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch(Exception $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_CITY_GENERAL_ERROR',
                'route' => request()->path(),
            ]);
            
            return response()->json([
                'error' => 'UNEXPECTED_ERROR_OCCURRED',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCity(UpdateCityRequest $request, int $id)
    {
        try {
            // Keresse meg a frissítendő céget az azonosítója alapján
            $city = City::findOrFail($id);
            
            // Frissítse a vállalatot a HTTP-kérés adataival
            $city->update($request->all());
            $city->update();
            
            // A frissített vállalatot JSON-válaszként küldje vissza sikeres állapotkóddal
            return response()->json([
                'success' => APP_TRUE,
                'message' => 'CITY_UPDATED_SUCCESSFULLY',
                'data' => $city
            ], Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            //
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_UPDATE_CITY', // updateCity not found error
                'route' => request()->path(),
            ]);
            
            return response()->json([
                'error' => 'CITY_NOT_FOUND',  // The specified city was not found
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            //
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_CITY', // updateCity database error
                'route' => request()->path(),
            ]);
            
            return response()->json([
                'error' => 'DB_ERROR_CITY', // Database error occurred while updating the city
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            //
            ErrorController::logServerError($ex, [
                'context' => 'GENERAL_ERROR_UPDATE_CITY', // updateCity general error
                'route' => request()->path(),
            ]);
            
            return response()->json([
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Töröljön egy várost az adatbázisból.
     *
     * A törölt város adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param int $id A törölni kívánt város azonosítója.
     * @return JsonResponse2 A törölt város adatait tartalmazó JSON-válasz.
     */
    public function deleteCity(GetCityRequest $request)
    {
        try {
            // Keresse meg a törölni kívánt céget az azonosítója alapján
            $city = City::findOrFail($id);
            
            $city->delete();
            
            return request()->json([
                'success' => APP_TRUE,
                'message' => 'DELETE_CITY_SUCCESSFULLY', // City deleted successfully
                'data' => $city,
            ], Request::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            // Ha a cég nem található
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_UPDATE_CITY', // updateEntity not found error
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'COUNTRY_NOT_FOUND', // The specified entity was not found
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            //
            ErrorController::logServerError($ex, [
                'context' => 'deleteCity database error',
                'route' => request()->path(),
            ]);
            //
            return response()->json([
                'error' => 'Database error occurred while deleting the city.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            //
            ErrorController::logServerError($ex, [
                'context' => 'deleteCity general error',
                'route' => request()->path(),
            ]);
            
            //
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteCities(Request $request)
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
            // Válasz visszaküldése
            return response()->json([
                'success' => APP_TRUE,
                'message' => 'Selected cities deleted successfully.',
                'deleted_count' => $deletedCount,
            ], Response::HTTP_OK);
        }catch( ValidationException $ex ){
            // Validációs hiba logolása
            ErrorController::logClientValidationError($request);

            // Kliens válasz
            return response()->json([
                'error' => 'Validation error occurred',
                'details' => $ex->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch( QueryException $ex ){
            // Adatbázis hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteCities database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'Database error occurred while deleting the selected cities.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch( Exception $ex ){
            // Általános hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteCities general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
