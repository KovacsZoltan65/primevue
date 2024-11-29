<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCountryRequest;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Nette\Schema\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse as JsonResponse2;
use Symfony\Component\HttpFoundation\Response;

class CountryController extends Controller
{
    /**
     * Jelenítse meg az erőforrás listáját.
     */
    public function index(Request $request)
    {
        //$cities = City::where('active', 1)->orderBy('name')->get()->toArray();
        //$regions = Region::where('active', 1)->orderBy('name')->get()->toArray();

        return Inertia::render('Geo/Country/Index', [
            //'cities' => $cities,
            //'regions' => $regions,
            'search' => request('search'),
        ]);
    }

    /**
     * Módosítja a lekérdezést az országok név szerinti kereséséhez.
     *
     * Ez a módszer a keresési lekérdezést az adatbázis-lekérdezésre alkalmazza.
     * Ha a keresési lekérdezés nem üres, akkor egy where záradékot ad a lekérdezéshez
     * azzal a feltétellel, hogy az országnévnek tartalmaznia kell a keresési lekérdezést.
     *
     * @param  Builder  $query
     * @param  string  $search
     * @return Builder
     */
    public function applySearch(Builder $query, string $search)
    {
        return $query->when($search, function($query, string $search) {
            // Adjon hozzá egy where záradékot a lekérdezéshez azzal a feltétellel, hogy a
            // az ország nevének tartalmaznia kell a keresési lekérdezést
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }
    
    public function getCountries(Request $request)
    {
        try {
            $countryQuery = Country::search($request);
            $countries = CountryResource::collection($countryQuery->get());
            return response()->json($countries, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COUNTRIES',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getCountries general error',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCountry(GetCountryRequest $request): JsonResponse2
    {
        try {
            $country = Country::findOrFail($request->id);
            
            return response()->json($country, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCountry error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Country not found',
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COUNTRY',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCountry general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Visszaadja a megadott névvel rendelkező országot.
     *
     * @param string $name Az ország neve.
     * @return JsonResponse A keresett ország adatait tartalmazó JSON-válasz.
     */
    public function getCountryByName(string $name): JsonResponse2
    {
        try {
            // Szerezze be az országot a megadott név alapján
            $country = Country::where('name', $name)->first();
            if( !$country ) {
                return response()->json(['error' => 'Country not found'], Response::HTTP_NOT_FOUND);
            }
        } catch( QueryException $ex ) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_ENTITY_BY_NAME',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch( Exception $ex ) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getCountryByName general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Hozzon létre új országot az adatbázisban.
     *
     * A létrehozott ország adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  Request  $request  A HTTP kérés objektum, amely tartalmazza a város új adatait.
     * @return JsonResponse  A létrehozott ország adatait tartalmazó JSON-válasz.
     */
    public function createCountry(StoreCountryRequest $request): JsonResponse2
    {
        try{
            $country = Country::create($request->all());
            
            // Sikeres válasz
            return response()->json([
                'success' => APP_TRUE,
                'message' => __('command_country_created', ['id' => $request->id]),
                'data' => $country
            ], Response::HTTP_CREATED);
        }catch( QueryException $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_COUNTRY_DATABASE_ERROR',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'CREATE_COUNTRY_DATABASE_ERROR',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch( Exception $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'createCountry general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCountry(UpdateCountryRequest $request, int $id): JsonResponse2
    {
        try{
            // Keresse meg a frissítendő céget az azonosítója alapján
            $country = Country::findOrFail($id);
            
            // Frissítse a vállalatot a HTTP-kérés adataival
            $country->update($request->all());
            // Frissítjük a modelt
            $country->refresh();
            
            return reqponse()->json([
                'success' => APP_TRUE,
                'message' => 'COUNTRY_UPDATED_SUCCESSFULLY',
                'data' => $country,
            ], Response::HTTP_OK);
        }catch( ModelNotFoundException $ex ){
            // Ha a cég nem található
            ErrorController::logServerError($ex, [
                'context' => 'ERROR_UPDATE_COMPANY', // updateCountry not found error
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'COUNTRY_NOT_FOUND', // The specified country was not found
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }catch( QueryException $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COUNTRY', // updateCountry database error
                'route' => request()->path(),
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'DB_ERROR_COUNTRY', // Database error occurred while updating the country
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch( Exception $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'updateCountry general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Töröljön egy országot az adatbázisból.
     *
     * A törölt ország adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  int  $id  A törölni kívánt ország azonosítója.
     * @return JsonResponse  A törölt ország adatait tartalmazó JSON-válasz.
     */
    public function deleteCountry(GetCountryRequest $request)
    {    
        try{
            // Keresse meg a törölni kívánt céget az azonosítója alapján
            $country = Country::findOrFail($request->id);

            $country->delete();
            
            request()->json([
                'success' => APP_TRUE,
                'message' => 'DELETE_COUNTRY_SUCCESSFULLY',
                'data' => $country,
            ], Request::HTTP_OK);
        }catch( ModelNotFoundException $ex ){
            // Ha a cég nem található
            ErrorController::logServerError($ex, [
                'context' => 'ERROR_DELETE_COMPANY', // updateCountry not found error
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'COUNTRY_NOT_FOUND', // The specified country was not found
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }catch( QueryException $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'deleteCountry database error',
                'route' => request()->path(),
            ]);
            //
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error occurred while deleting the country.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch( Exception $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'deleteCountry general error',
                'route' => request()->path(),
            ]);
            
            //
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteCountries(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:countries,id', // Az id-k egész számok és létező cégek legyenek
            ]);
            // Az azonosítók kigyűjtése
            $ids = $validated['ids'];
            // A cégek törlése
            $deletedCount = Country::whereIn('id', $ids)->delete();
            // Válasz visszaküldése
            return response()->json([
                'success' => APP_TRUE,
                'message' => 'Selected countries deleted successfully.',
                'deleted_count' => $deletedCount,
            ], Response::HTTP_OK);
        } catch( ValidationException $ex ) {
            // Validációs hiba logolása
            //ErrorController::logClientValidationError($request);
            ErrorController::logServerValidationError($ex, $request);

            // Kliens válasz
            return response()->json([
                'error' => 'Validation error occurred',
                'details' => $ex->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( QueryException $ex ) {
            // Adatbázis hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteCountries database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error occurred while deleting the selected countries.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            // Általános hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteCountries general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
