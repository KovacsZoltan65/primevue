<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetRegionRequest;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Resources\RegionResource;
use App\Models\Country;
use App\Models\Region;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Nette\Schema\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class RegionController extends Controller
{
    protected string $tag = 'regions';
    
    public function __construct() {
        $this->middleware('can:regions list', ['only' => ['index', 'applySearch', 'getRegions', 'getSRegion', 'getRegionByName']]);
        $this->middleware('can:regions create', ['only' => ['createRegion']]);
        $this->middleware('can:regions edit', ['only' => ['updateRegion']]);
        $this->middleware('can:regions delete', ['only' => ['deleteRegion', 'deleteRegions']]);
        $this->middleware('can:regions restore', ['only' => ['restoreRegion']]);
    }
    
    /**
     * Jelenítse meg az erőforrás listáját.
     *
     * Ez a módszer felelős a megyék listájának megjelenítéséért.
     * Egy opcionális keresési paramétert vesz igénybe, és visszaadja a régiók listáját
     * amelyek megfelelnek a keresési feltételeknek.
     *
     * @param  Request  $request
     * @return InertiaResponse
     */
    public function index(Request $request): InertiaResponse
    {
        $countries = Country::where('active', 1)->orderBy('name')->get()->toArray();
        
        $search = $request->query('search');
        
        return Inertia::render('Geo/Region/Index', [
            'countries' => $countries,
            'search' => $search,
        ]);
    }
    
    /**
     * Módosítsa a lekérdezést a keresési paraméter alapján.
     * 
     * Ha a keresési paraméter nem üres, akkor a lekérdezés tartalmazza
     * a feltételt, hogy a régió neve tartalmazza a keresési paramétert.
     * 
     * @param  Builder  $query
     * @param  string  $search
     * @return Builder
     */
    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function($query, string $search) {
            // A lekérdezéshez hozzáadja a keresési feltételt
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }
    
    public function getRegions(Request $request): JsonResponse
    {
        try {
            $regionQuery = Region::search($request);
            $regions = RegionResource::collection($regionQuery->get());
            
            return response()->json($regions, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_REGIONS',
                'route' => $request->path(),
            ]);

            return response()->json([
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getRegions general error',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getRegion(GetRegionRequest $request): JsonResponse
    {
        try {
            $region = Region::findOrFail($request->id);

            return response()->json($region, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getRegion error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Region not found',
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_REGION',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getRegion general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getRegionByName(Request $request)
    {
        try {
            // Cég lekérdezése név alapján
            $region = Region::where('name', '=', $request->name)->first();
            
            if (!$region) {
                // Ha a régió nem található, 404-es hibát adunk vissza
                return response()->json([
                    'success' => APP_FALSE,
                    'error' => 'Region not found'
                ], Response::HTTP_NOT_FOUND);
            }
            
            return response()->json($region, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_REGION_BY_NAME',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getRegionByName general error',
                'route' => request()->path(),
            ]);

            // JSON-választ küld vissza, jelezve, hogy váratlan hiba történt
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Hozzon létre új régiót az adatbázisban.
     *
     * A létrehozott régió adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  Request  $request  A HTTP kérés objektum, amely tartalmazza a régió új adatait.
     * @return JsonResponse  A létrehozott régió adatait tartalmazó JSON-válasz.
     */
    public function createRegion(StoreRegionRequest $request): JsonResponse
    {
        try {
            $region = Region::create($request->all());
            
            // Sikeres válasz
            return response()->json([
                'success' => APP_TRUE,
                'message' => __('command_region_created', ['id' => $request->id]),
                'data' => $region
            ], Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_REGION_DATABASE_ERROR',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'CREATE_COUNTRY_DATABASE_ERROR',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'createRegion general error',
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
     * Frissítse egy régiót az adatbázisban.
     *
     * A frissített régió adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  Request  $request  A HTTP kérés objektum, amely tartalmazza a régió új adatait.
     * @param  int  $id  A frissítendő régió azonosítója.
     * @return JsonResponse  A frissített régió adatait tartalmazó JSON-válasz.
     */
    public function updateRegion(Request $request, int $id): JsonResponse
    {
        try{
            $region = Region::findOrFail($id);
            // Frissítse a vállalatot a HTTP-kérés adataival
            $region->update($request->all());
            // Frissítjük a modelt
            $region->refresh();
            
            return response()->json([
                'success' => APP_TRUE,
                'message' => 'REGION_UPDATED_SUCCESSFULLY',
                'data' => $region,
            ], Response::HTTP_OK);
        }catch(ModelNotFoundException $ex){
            // Ha a cég nem található
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_UPDATE_REGION', // updateRegion not found error
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'COUNTRY_NOT_FOUND', // The specified region was not found
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }catch(QueryException $ex){
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_REGION', // updateRegion database error
                'route' => request()->path(),
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'DB_ERROR_REGION', // Database error occurred while updating the region
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch(Exception $ex){
            ErrorController::logServerError($ex, [
                'context' => 'updateRegion general error',
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
     * Töröljön egy régiót az adatbázisból.
     *
     * A törölt régió adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  int  $id  A törölni kívánt régió azonosítója.
     * @return JsonResponse  A törölt régió adatait tartalmazó JSON-válasz.
     */
    public function deleteRegion(StoreRegionRequest $id): JsonResponse
    {
        try {
            $region = Region::findOrFail($id);
            $region->delete();
            
            return response()->json([
                'success' => APP_TRUE,
                'message' => 'DELETE_REGION_SUCCESSFULLY',
                'data' => $region,
            ], Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            // Ha a cég nem található
            ErrorController::logServerError($ex, [
                'context' => 'ERROR_DELETE_REGION', // deleteRegion not found error
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'REGION_NOT_FOUND', // The specified region was not found
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteRegion database error',
                'route' => request()->path(),
            ]);
            //
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error occurred while deleting the region.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteRegion general error',
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
    
    public function deleteRegions(Request $request): JsonResponse
    {
        try {
            // Az azonosítók tömbjének validálása
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:regions,id', // Az id-k egész számok és létező cégek legyenek
            ]);
            
            // Az azonosítók kigyűjtése
            $ids = $validated['ids'];
            
            // A cégek törlése
            $deletedCount = Region::whereIn('id', $ids)->delete();
            
            // Válasz visszaküldése
            return response()->json([
                'success' => true,
                'message' => 'Selected regions deleted successfully.',
                'deleted_count' => $deletedCount,
            ], Response::HTTP_OK);
        } catch(ValidationException $ex) {
            // Validációs hiba logolása
            //ErrorController::logClientValidationError($request);
            ErrorController::logServerValidationError($ex, $request);

            // Kliens válasz
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Validation error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            // Adatbázis hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteRegions database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error occurred while deleting the selected regions.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            // Általános hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteRegions general error',
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
