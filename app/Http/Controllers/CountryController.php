<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCountryRequest;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Services\CacheService;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\Functions;

class CountryController extends Controller
{
    use AuthorizesRequests,
        Functions;
    
    protected string $tag = 'countries';
    
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
    
    public function getCountries(Request $request, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5(json_encode($request->all()));
            
            $countries = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $countryQuery = Country::search($request);
                return CountryResource::collection($countryQuery->get());
            });

            return response()->json($countries, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getCountries query error',
                'params' => ['request' => $request->all()],
                'route' => $request->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCountries query error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getCountries general error',
                'route' => $request->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCountries general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCountry(GetCountryRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5($request->id);
            
            $country = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                return Country::findOrFail($request->id);
            });
            
            return response()->json($country, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCountry model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCountry model not found error',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCountry query error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCountry query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCountry general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCountry general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Visszaadja a megadott névvel rendelkező országot.
     *
     * @param string $name Az ország neve.
     * @return JsonResponse A keresett ország adatait tartalmazó JSON-válasz.
     */
    public function getCountryByName(string $name, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5($name);
            
            $country = $cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return Country::where('name', '=', $name)->firstOrFail();
            });
            
            return response()->json($country, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'getCountryByName model not found error',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => "getCountryByName model not found error"
            ], Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getCountryByName query error',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
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
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCountryByName general error',
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
    public function createCountry(StoreCountryRequest $request, CacheService $cacheService): JsonResponse
    {
        try{
            $country = Country::create($request->all());
            
            $cacheService->forgetAll($this->tag);
            
            // Sikeres válasz
            return response()->json($country, Response::HTTP_CREATED);
        }catch( QueryException $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'createCountry query error',
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'createCountry query error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch( Exception $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'createCountry general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'createCountry general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCountry(UpdateCountryRequest $request, int $id, CacheService $cacheService): JsonResponse
    {
        try{
            $country = null;
            
            \DB::transaction(function() use($request, $id, $cacheService, &$country) {
                $country = Country::findOrFail($id)->lockForUpdate();
                $country->update($request->all());
                $country->refresh();
                $cacheService->forgetAll($this->tag);
            });
            
            return response()->json($country, Response::HTTP_OK);
        }catch( ModelNotFoundException $ex ){
            // Ha a cég nem található
            ErrorController::logServerError($ex, [
                'context' => 'updateCountry model not found error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateCountry model not found error',
            ], Response::HTTP_NOT_FOUND);
        }catch( QueryException $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'updateCountry query error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateCountry query error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch( Exception $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'updateCountry general error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateCountry general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCountry(GetCountryRequest $request, CacheService $cacheService): JsonResponse
    {    
        try{
            // Keresse meg a törölni kívánt céget az azonosítója alapján
            $country = Country::findOrFail($request->id);

            $country->delete();
            
            $cacheService->forgetAll($this->tag);
            
            return response()->json($country, Response::HTTP_OK);
        }catch( ModelNotFoundException $ex ){
            // Ha a cég nem található
            ErrorController::logServerError($ex, [
                'context' => 'deleteCountry model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteCountry model not found error',
            ], Response::HTTP_NOT_FOUND);
        }catch( QueryException $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'deleteCountry database error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);
            //
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteCountry database error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch( Exception $ex ){
            ErrorController::logServerError($ex, [
                'context' => 'deleteCountry general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);
            
            //
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteCountry general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteCountries(Request $request, CacheService $cacheService): JsonResponse
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
            
            $cacheService->forgetAll($this->tag);
            
            // Válasz visszaküldése
            return response()->json($deletedCount, Response::HTTP_OK);
        } catch( ValidationException $ex ) {
            // Validációs hiba logolása
            //ErrorController::logClientValidationError($request);
            ErrorController::logServerValidationError($ex, $request);

            // Kliens válasz
            return response()->json([
                'success' => APP_TRUE,
                'error' => 'deleteCountries validation error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( QueryException $ex ) {
            // Adatbázis hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteCountries query error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteCountries query error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            // Általános hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteCountries general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteCountries general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
