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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cities = City::where('active', 1)->orderBy('name')->get()->toArray();
        $regions = Region::where('active', 1)->orderBy('name')->get()->toArray();
        
        return Inertia::render('Geo/Country/Index', [
            'cities' => $cities,
            'regions' => $regions,
            'search' => request('search'),
        ]);
    }
    
    public function applySearch(Builder $query, string $search)
    {
        return $query->when($search, function($query, string $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }

    public function getCountries(Request $request)
    {
        $countryQuery = Country::search($request);
        
        $countries = CountryResource::collection($countryQuery->get());
        
        return $countries;
    }
    
    public function createCountry(Request $request)
    {
        $country = Country::create($request->all());
        
        return response()->json($country, Response::HTTP_OK);
    }
    
    public function updateCountry(Request $request, int $id)
    {
        $old_country = Country::where('id', $id)->first();
        $success = $old_country->update($request->all());
        
        return response()->json(['success' => $success], Response::HTTP_OK);
    }
    
    public function deleteCountry(int $id)
    {
        $country = Country::where('id', $id)->first();
        $success = $country->delete();
        
        return response()->json(['success' => $success], Response::HTTP_OK);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
