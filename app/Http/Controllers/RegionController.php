<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetRegionRequest;
use App\Http\Requests\StoreRegionRequest;
use App\Models\Country;
use App\Models\Region;
use App\Repositories\RegionRepository;
use App\Services\Region\RegionService;
use App\Traits\Functions;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Nette\Schema\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class RegionController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected string $tag = 'regions';
    protected RegionService $regionService;

    public function __construct(RegionRepository $repository)
    {
        $this->regionRepository = $repository;

        $this->tag = Region::getTag();

        $this->middleware("can:{$this->tag} list", ['only' => ['index', 'applySearch', 'getRegions', 'getSRegion', 'getRegionByName']]);
        $this->middleware("can:{$this->tag} create", ['only' => ['createRegion']]);
        $this->middleware("can:{$this->tag} edit", ['only' => ['updateRegion']]);
        $this->middleware("can:{$this->tag} delete", ['only' => ['deleteRegion', 'deleteRegions']]);
        $this->middleware("can:{$this->tag} restore", ['only' => ['restoreRegion']]);
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
        $roles = $this->getUserRoles($this->tag);

        $countries = Country::where('active', 1)->orderBy('name')->get()->toArray();

        $search = $request->query('search');

        return Inertia::render('Geo/Region/Index', [
            'countries' => $countries,
            'search' => $search,
            'can' => $roles,
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
            $regions = $this->regionService->getRegions($request);

            return response()->json($regions, Response::HTTP_OK);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getRegions query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getRegions general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getRegion(GetRegionRequest $request): JsonResponse
    {
        try {
            $region = $this->regionService->getRegion($request->id);

            return response()->json($region, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getRegion model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getRegion query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getRegion general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getRegionByName(string $name): JsonResponse
    {
        try {
            $region = $this->regionService->getRegionByName($name);

            return response()->json($region, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getRegionByName model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getRegionByName query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getRegionByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $region = $this->regionService->createRegion($request);

            return response()->json($region, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'createRegion query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createRegion general error', Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $region = $this->regionService->updateRegion($request, $id);

            return response()->json($region, Response::HTTP_OK);
        }catch(ModelNotFoundException $ex){
            return $this->handleException($ex, 'updateRegion model not found error', Response::HTTP_NOT_FOUND);
        }catch(QueryException $ex){
            return $this->handleException($ex, 'updateRegion query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch(Exception $ex){
            return $this->handleException($ex, 'updateRegion general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteRegions(Request $request): JsonResponse
    {
        try {
            $deletedCount = $this->regionService->deleteRegions($request);

            return response()->json($deletedCount, Response::HTTP_OK);
        } catch(ValidationException $ex) {
            return $this->handleException($ex, 'deleteRegions validation error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteRegions database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteRegions general error', Response::HTTP_INTERNAL_SERVER_ERROR);
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
    public function deleteRegion(StoreRegionRequest $request): JsonResponse
    {
        try {
            $region = $this->regionService->deleteRegion($request);

            return response()->json($region, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteRegion model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteRegion database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteRegion general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreRegion(GetRegionRequest $request): JsonResponse
    {
        try {
            $region = $this->regionService->restoreRegion($request);

            return response()->json($region, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restoreRegion model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restoreRegion query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restoreRegion general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function realDeleteRegion(GetRegionRequest $request)
    {
        try {
            $deletedCount = $this->regionService->realDeleteRegion($request->id);

            return response()->json($deletedCount, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'realDeleteRegion model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'realDeleteRegion query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'realDeleteRegion general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
