<?php

namespace App\Http\Controllers;

use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class PersonController extends Controller
{
    public function __construct() {
        //
    }
    
    public function index(Request $request)
    {
        Inertia::render('Person/Index');
    }
    
    public function applySearch(Builder $query, string $search)
    {
        return $query->when($search, function($query, string $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }
    
    public function getPersons(Request $request)
    {
        $personQuery = Person::Search($request);
        $person = PersonResource::collection($personQuery->get());
        
        return $person;
    }
    
    public function getPerson(int $id)
    {
        $person = Person::find($id);
        
        return response()->json($person, Response::HTTP_OK);
    }
    
    public function getPersonByName(string $name)
    {
        $person = Person::where('name', $name)->first();
        
        return response()->json($person, Response::HTTP_OK);
    }
    
    public function createPerson(StorePersonRequest $request)
    {
        $person = Person::create($request);
        
        return response()->json($person, Response::HTTP_OK);
    }
    
    public function updatePerson(UpdatePersonRequest $request, int $id)
    {
        $old_person = Person::find($id);
        
        $success = $old_person->update($request->all());
        
        return response()->json(['success' => $success], Response::HTTP_OK);
    }
    
    public function deletePerson(int $id)
    {
        $person = Person::find($id);
        $success = $person->delete();
        
        return response()->json(['success' => $success], Response::HTTP_OK);
    }
}
