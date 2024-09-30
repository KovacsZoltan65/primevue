<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubdomainRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Resources\SubdomainResource;
use App\Models\Subdomain;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use function response;

class SubdomainController extends Controller
{
    public function __construct() {
        //
    }
    
    public function index(Request $request)
    {
        return Inertia::render('Subdomain/Index', [
            'search' => $request->get('search')
        ]);
    }
    
    public function applySearch(Builder $query, string $search)
    {
        return $query->when($search, function($query, string $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }
    
    public function getSubdomains(Request $request): AnonymousResourceCollection
    {
        $subdomainQuery = Subdomain::search($request);
        $subdomains = SubdomainResource::collection($subdomainQuery->get());
        
        return $subdomains;
    }
    
    public function createSubdomain(StoreSubdomainRequest $request)
    {
        $subdomain = Subdomain::create($request->all());
        
        return response()->json($subdomain, Response::HTTP_OK);
    }
    
    public function updateSubdomain(UpdateCityRequest $request, int $id)
    {
        $old_subdomain = Subdomain::find($id);
        $success = $old_subdomain->update();
        
        return response()->json(['success' => $success], Response::HTTP_OK);
    }
    
    public function deleteSubdomain(int $id)
    {
        $subdomain = Subdomain::find($id);
        $success = $subdomain->delete();
        
        return response()->json(['success' => $success], Response::HTTP_OK);
    }
}
