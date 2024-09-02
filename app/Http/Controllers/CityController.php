<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use Inertia\Inertia;

class CityController extends Controller
{
    /**
     * Jelenítse meg az erőforrás listáját.
     *
     * Ez a módszer felelős a városok listájának megjelenítéséért.
     * Egy opcionális keresési paramétert vesz igénybe, és visszaadja a városok listáját
     * amelyek megfelelnek a keresési feltételeknek.
     *
     * @param  \Illuminate\Http\Request  $request
     * @retirn \Inertia\Response
     */
    public function index(Request $request)
    {
        // Szerezze be a városokat az adatbázisból.
        // Ha van megadva keresési paraméter, szűrje le az eredményeket.
        $cityQuery = City::search($request);

        // Alakítsa át a városokat erőforrásgyűjteménybe
        $cities = CityResource::collection($cityQuery->get());

        // Jelenítse meg a nézetet a városokkal és a keresési paraméterrel
        return Inertia::render('Cities/Index', [
            'cities' => $cities,
            'search' => request('search'),
        ]);
        /*
        return Inertia::render('Geo/City/Index', [
            'cities' => $cities,
            'search' => request('search'),
        ]);
        */
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getCities(Request $request)
    {
        // Szerezze be a városokat az adatbázisból
        // Ha keresési paramétert ad meg, szűrje az eredményeket
        $cityQuery = City::search($request);

        // Alakítsa át a városokat erőforrásgyűjteménybe
        $cities = CityResource::collection($cityQuery->get());
        
        // Az erőforrásgyűjtemény visszaadása
        return $cities;
    }
    
    public function createCity(Request $request)
    {
        $city = City::create($request->all());
        return response()->json($city, Response::HTTP_OK);
    }
    
    public function updateCity(Request $request, int $id)
    {
        $old_city = City::where('id', $id)->first();
        $city = $old_city->update($request->all());
        return response()->json($city, Response::HTTP_OK);
    }
    
    public function deleteCity(int $id)
    {
        $city = City::where('id', $id)->first();
        $city->delete();
        return response()->json($city, Response::HTTP_OK);
    }
    
    
    
    
    
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCityRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCityRequest $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        //
    }
}
