<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Http\Resources\CountryResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
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
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applySearch(Builder $query, string $search)
    {
        return $query->when($search, function($query, string $search) {
            // Adjon hozzá egy where záradékot a lekérdezéshez azzal a feltétellel, hogy a
            // az ország nevének tartalmaznia kell a keresési lekérdezést
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }
    /**
     * Visszaadja az országok listáját a keresési paraméter alapján.
     *
     * Ez a módszer a keresési lekérdezést az adatbázis-lekérdezésre alkalmazza.
     * Ha a keresési lekérdezés nem üres, akkor egy where záradékot ad a lekérdezéshez
     * azzal a feltétellel, hogy az országnévnek tartalmaznia kell a keresési lekérdezést.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getCountries(Request $request)
    {
        // Szerezze be a lekérdezést az országok neve szerinti kereséséhez
        $countryQuery = Country::search($request);

        // Alakítsa át a lekérdezés eredményét erőforrásgyűjteménybe
        $countries = CountryResource::collection($countryQuery->get());

        // Az erőforrásgyűjtemény visszaadása
        return $countries;
    }

    /**
     * Visszaadja a megadott azonosítóval rendelkező országot.
     *
     * @param int $id Az ország azonosítója.
     * @return \Illuminate\Http\JsonResponse A keresett ország adatait tartalmazó JSON-válasz.
     */
    public function getCountry(int $id)
    {
        // Szerezze be az országot a megadott azonosító alapján
        $country = Country::find($id);

        // A keresett ország adatait tartalmazó JSON-válasz visszaadása
        return response()->json($country, Response::HTTP_OK);
    }

    /**
     * Visszaadja a megadott névvel rendelkező országot.
     *
     * @param string $name Az ország neve.
     * @return \Illuminate\Http\JsonResponse A keresett ország adatait tartalmazó JSON-válasz.
     */
    public function getCountryByName(string $name)
    {
        // Szerezze be az országot a megadott név alapján
        $country = Country::where('name', $name)->first();

        // A keresett ország adatait tartalmazó JSON-válasz visszaadása
        return response()->json($country, Response::HTTP_OK);
    }

    /**
     * Hozzon létre új országot az adatbázisban.
     *
     * A létrehozott ország adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  Request  $request  A HTTP kérés objektum, amely tartalmazza a város új adatait.
     * @return \Illuminate\Http\JsonResponse  A létrehozott ország adatait tartalmazó JSON-válasz.
     */
    public function createCountry(Request $request)
    {
        // Hozzon létre új országot az adatbázisban
        $country = Country::create($request->all());

        // A létrehozott ország adatait tartalmazó JSON-válasz visszaadása
        return response()->json($country, Response::HTTP_OK);
    }

    /**
     * Frissít egy országot az adatbázisban.
     *
     * A frissített ország adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  Request  $request  A HTTP kérés objektum, amely tartalmazza az ország új adatait.
     * @param  int  $id  A frissítendő ország azonosítója.
     * @return \Illuminate\Http\JsonResponse  A frissített ország adatait tartalmazó JSON-válasz.
     */
    public function updateCountry(Request $request, int $id)
    {
        // Szerezze be a frissítendő országot az adatbázisból
        $old_country = Country::where('id', $id)->first();

        // Frissítse a város adatait a HTTP kérésben szereplő adatokkal
        $success = $old_country->update($request->all());

        // A frissített város adatait tartalmazó JSON-válasz visszaadása
        return response()->json(['success' => $success], Response::HTTP_OK);
    }

    /**
     * Töröljön egy országot az adatbázisból.
     *
     * A törölt ország adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  int  $id  A törölni kívánt ország azonosítója.
     * @return \Illuminate\Http\JsonResponse  A törölt ország adatait tartalmazó JSON-válasz.
     */
    public function deleteCountry(int $id)
    {
        // Szerezze be a törölni kívánt országot az adatbázisból
        $country = Country::where('id', $id)->first();

        // Törölje az országot az adatbázisból
        $success = $country->delete();

        // A törölt ország adatait tartalmazó JSON-válasz visszaadása
        return response()->json(['success' => $success], Response::HTTP_OK);
    }
}
