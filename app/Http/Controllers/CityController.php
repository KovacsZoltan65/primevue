<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse as JsonResponse2;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
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

    /**
     * Keresse meg a városokat a keresési paraméter alapján.
     *
     * Ez a módszer városok erőforrásgyűjteményét adja vissza
     * amelyek megfelelnek a keresési feltételeknek.
     *
     * @param  Request  $request
     * @return AnonymousResourceCollection
     */
    public function getCities(Request $request): AnonymousResourceCollection
    {
        // Szerezze be a városokat az adatbázisból
        // Ha keresési paramétert ad meg, szűrje az eredményeket
        $cityQuery = City::search($request);

        // Alakítsa át a városokat erőforrásgyűjteménybe
        $cities = CityResource::collection($cityQuery->get());

        // Az erőforrásgyűjtemény visszaadása
        return $cities;
    }

    /**
     * Szerezzen várost azonosítóval.
     *
     * @param int $id A város id.
     * @return JsonResponse2 A város adatai JSON formátumban.
     */
    public function getCity(int $id)
    {
        // Szerezd meg a várost az adatbázisból
        $city = City::find($id);

        // Adja vissza a város adatait JSON formátumban
        return response()->json($city, Response::HTTP_OK);
    }

    /**
     * Szerezzen várost a név alapján.
     *
     * @param string $name A város neve.
     * @return JsonResponse2 A keresett város adatait tartalmazó JSON-válasz.
     */
    public function getCityByName(string $name)
    {
        // Szerezze be a várost a megadott név alapján
        $city = City::where('name', $name)->first();

        // A keresett város adatait tartalmazó JSON-válasz visszaadása
        return response()->json($city, Response::HTTP_OK);
    }

    /**
     * Hozzon létre új várost az adatbázisban.
     *
     * A létrehozott város adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  Request  $request  A HTTP kérés objektum, amely tartalmazza a város új adatait.
     * @return JsonResponse2  A létrehozott város adatait tartalmazó JSON-válasz.
     */
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

    /**
     * Frissít egy várost az adatbázisban.
     *
     * A frissített város adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  Request  $request  A HTTP kérés objektum, amely tartalmazza a város új adatait.
     * @param  int  $id  A frissítendő város azonosítója.
     * @return JsonResponse2  A frissített város adatait tartalmazó JSON-válasz.
     */
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
    public function deleteCity(int $id)
    {
        try {
            // Keresse meg a törölni kívánt céget az azonosítója alapján
            $city = City::findOrFail($id);
            
            //
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
