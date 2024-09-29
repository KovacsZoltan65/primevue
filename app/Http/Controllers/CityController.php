<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
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
            'search' => request('search'),
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
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getCities(Request $request): AnonymousResourceCollection
    {
        // Szerezze be a városokat az adatbázisból
        // Ha keresési paramétert ad meg, szűrje az eredményeket
        $cityQuery = City::search($request);

        // Alakítsa át a városokat erőforrásgyűjteménybe
        $cities = CityResource::collection($cityQuery->get());
        //$cities = CityResource::collection(City::all());

        // Az erőforrásgyűjtemény visszaadása
        return $cities;
    }

    /**
     * Szerezzen várost azonosítóval.
     *
     * @param int $id A város id.
     * @return \Illuminate\Http\JsonResponse A város adatai JSON formátumban.
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
     * @return \Illuminate\Http\JsonResponse A keresett város adatait tartalmazó JSON-válasz.
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
     * @return \Illuminate\Http\JsonResponse  A létrehozott város adatait tartalmazó JSON-válasz.
     */
    public function createCity(StoreCityRequest $request)
    {
        // Hozzon létre új várost az adatbázisban
        $city = City::create($request->all());

        // A létrehozott város adatait tartalmazó JSON-válasz visszaadása
        return response()->json($city, Response::HTTP_OK);
    }

    /**
     * Frissít egy várost az adatbázisban.
     *
     * A frissített város adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  Request  $request  A HTTP kérés objektum, amely tartalmazza a város új adatait.
     * @param  int  $id  A frissítendő város azonosítója.
     * @return \Illuminate\Http\JsonResponse  A frissített város adatait tartalmazó JSON-válasz.
     */
    public function updateCity(UpdateCityRequest $request, int $id)
    {
        // Szerezze be a várost az adatbázisból
        $old_city = City::where('id', $id)->first();

        // Frissítse a város adatait a HTTP kérésben szereplő adatokkal
        $success = $old_city->update($request->all());

        // A frissített város adatait tartalmazó JSON-válasz visszaadása
        return response()->json(['success' => $success], Response::HTTP_OK);
    }

    /**
     * Töröljön egy várost az adatbázisból.
     *
     * A törölt város adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param int $id A törölni kívánt város azonosítója.
     * @return \Illuminate\Http\JsonResponse A törölt város adatait tartalmazó JSON-válasz.
     */
    public function deleteCity(int $id)
    {
        // Szerezze be a várost az adatbázisból
        $city = City::where('id', $id)->first();

        // Törölje a várost az adatbázisból
        $success = $city->delete();

        // A törölt város adatait tartalmazó JSON-válasz visszaadása
        return response()->json(['success' => $success], Response::HTTP_OK);
    }
}
