<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetRegionRequest;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Resources\RegionResource;
use App\Models\Country;
use App\Models\Region;
use App\Services\CacheService;
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
use App\Traits\Functions;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class RegionController extends Controller
{
    use AuthorizesRequests,
        Functions;

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

    public function getRegions(Request $request, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5(json_encode($request->all()));

            $regions = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $regionQuery = Region::search($request);
                return RegionResource::collection($regionQuery->get());
            });

            return response()->json($regions, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getRegions query error',
                'params' => ['request' => $request->all()],
                'route' => $request->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getRegions query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getRegions general error',
                'params' => ['request' => $request->all()],
                'route' => $request->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getRegions general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getRegion(GetRegionRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5($request->id);

            $region = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                return Region::findOrFail($request->id);
            });

            return response()->json($region, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getRegion model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getRegion model not found error',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getRegion query error',
                'params' => ['id' => $request->id],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getRegion query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getRegion general error',
                'params' => ['id' => $request->id],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getRegion general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getRegionByName(string $name, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "{$this->tag}_" . md5($name);

            $region = $cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return Region::where('name', '=', $name)->firstOrFail();
            });

            if (!$region) {
                // Ha a régió nem található, 404-es hibát adunk vissza
                return response()->json([
                    'success' => APP_FALSE,
                    'error' => 'Region not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json($region, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'getRegionByName model not found error',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => "getRegionByName model not found error"
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getRegionByName query error',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getRegionByName query error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getRegionByName general error',
                'params' => ['name' => $name],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            // JSON-választ küld vissza, jelezve, hogy váratlan hiba történt
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getRegionByName general error',
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
    public function createRegion(StoreRegionRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $region = Region::create($request->all());

            $cacheService->forgetAll($this->tag);

            // Sikeres válasz
            return response()->json($region, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'createRegion query error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'createRegion query error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'createRegion general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'createRegion general error',
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
    public function updateRegion(Request $request, int $id, CacheService $cacheService): JsonResponse
    {
        try{
            $region = null;

            DB::transaction(function() use($request, $id, $cacheService, &$region) {
                $region = Region::findOrFail($id)->lockForUpdate();
                $region->update($request->all());
                $region->refresh();
                $cacheService->forgetAll($this->tag);
            });

            return response()->json($region, Response::HTTP_OK);
        }catch(ModelNotFoundException $ex){
            // Ha a cég nem található
            ErrorController::logServerError($ex, [
                'context' => 'updateRegion model not found error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateRegion model not found error',
            ], Response::HTTP_NOT_FOUND);
        }catch(QueryException $ex){
            ErrorController::logServerError($ex, [
                'context' => 'updateRegion query error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateRegion query error',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch(Exception $ex){
            ErrorController::logServerError($ex, [
                'context' => 'updateRegion general error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateRegion general error',
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
    public function deleteRegion(StoreRegionRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $region = Region::findOrFail($request->id);
            $region->delete();

            $cacheService->forgetAll($this->tag);

            return response()->json($region, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            // Ha a cég nem található
            ErrorController::logServerError($ex, [
                'context' => 'deleteRegion model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteRegion model not found error',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteRegion database error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteRegion database error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteRegion general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteRegion general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteRegions(Request $request, CacheService $cacheService): JsonResponse
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

            $cacheService->forgetAll($this->tag);

            // Válasz visszaküldése
            return response()->json($deletedCount, Response::HTTP_OK);
        } catch(ValidationException $ex) {
            ErrorController::logServerValidationError($ex, $request);

            // Kliens válasz
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteRegions validation error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteRegions database error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteRegions database error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            // Általános hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteRegions general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'deleteRegions general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
