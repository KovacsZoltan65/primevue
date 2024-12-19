<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetRegionRequest;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Resources\RegionResource;
use App\Models\Country;
use App\Models\Region;
use App\Repositories\RegionRepositoryInterface;
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
    protected RegionRepositoryInterface $regionRepository;

    public function __construct(RegionRepositoryInterface $repository) {
        $this->regionRepository = $repository;
        
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
            $regions = $this->regionRepository->getRegions($request);

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
            $region = $this->regionRepository->getRegion($request->id);

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
            $region = $this->regionRepository->getRegionByName($name);

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
            $region = $this->regionRepository->createRegion($request);

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
            $region = $this->regionRepository->updateRegion($request, $id);

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
            $deletedCount = $this->regionRepository->deleteRegions($request);

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
            $region = $this->regionRepository->deleteREgion($request);

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
            $region = $this->regionRepository->restoreRegion($request);

            return response()->json($region, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restoreRegion model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restoreRegion query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restoreRegion general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
