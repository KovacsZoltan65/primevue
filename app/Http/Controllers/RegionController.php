<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegionResource;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as Response2;
use Symfony\Component\HttpFoundation\Response;

class RegionController extends Controller
{
    /**
     * Jelenítse meg az erőforrás listáját.
     *
     * Ez a módszer felelős a megyék listájának megjelenítéséért.
     * Egy opcionális keresési paramétert vesz igénybe, és visszaadja a régiók listáját
     * amelyek megfelelnek a keresési feltételeknek.
     *
     * @param  Request  $request
     * @return Response2
     */
    public function index(Request $request)
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
    public function applySearch(Builder $query, string $search)
    {
        return $query->when($search, function($query, string $search) {
            // A lekérdezéshez hozzáadja a keresési feltételt
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }
    
    /**
     * Keresse meg a régiókat a keresési paraméter alapján.
     *
     * Ez a módszer a régiók lekérdezését módosítja a keresési paraméter
     * alapján. Ha a keresési paraméter nem üres, akkor a lekérdezés
     * tartalmazza a feltételt, hogy a régió neve tartalmazza a keresési
     * paramétert.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getRegions(Request $request)
    {
        // Lekérdezi a régiókat a keresési paraméter alapján
        $regionQuery = Region::search($request);
        
        // Alakítsa át a régiókat erőforrásgyűjteménybe
        $regions = RegionResource::collection($regionQuery->get());
        
        // Az erőforrásgyűjtemény visszaadása
        return $regions;
    }
    
    /**
     * Hozzon létre új régiót az adatbázisban.
     *
     * A létrehozott régió adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  Request  $request  A HTTP kérés objektum, amely tartalmazza a régió új adatait.
     * @return JsonResponse  A létrehozott régió adatait tartalmazó JSON-válasz.
     */
    public function createRegion(Request $request)
    {
        // Hozzon létre új régiót az adatbázisban
        $region = Region::create($request->all());
        
        // A létrehozott régió adatait tartalmazó JSON-válasz visszaadása
        return response()->json($region, Response::HTTP_OK);
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
    public function updateRegion(Request $request, int $id)
    {
        // Szerezze be a régiót az adatbázisból
        $old_region = Region::find($id);
        
        // Frissítse a régió adatait a HTTP kérésben szereplő adatokkal
        $success = $old_region->update($request->all());
        
        // A frissített régió adatait tartalmazó JSON-válasz visszaadása
        return response()->json(['success' => $success], Response::HTTP_OK);
    }
    
    /**
     * Töröljön egy régiót az adatbázisból.
     *
     * A törölt régió adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  int  $id  A törölni kívánt régió azonosítója.
     * @return JsonResponse  A törölt régió adatait tartalmazó JSON-válasz.
     */
    public function deleteRegion(int $id)
    {
        // Szerezze be a régiót az adatbázisból
        $old_region = Region::find($id);
        
        // Törölje a régiót az adatbázisból
        $success = $old_region->delete();
        
        // A törölt régió adatait tartalmazó JSON-válasz visszaadása
        return response()->json(['success' => $success], Response::HTTP_OK);
    }
}
