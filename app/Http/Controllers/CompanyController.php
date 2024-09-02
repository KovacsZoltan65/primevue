<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
//use Illuminate\Http\Response;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //return Inertia::render("Companies/Index");
        
        $companyQuery = Company::search($request);
        //$companies = CompanyResource::collection($companyQuery->paginate(5));
        $companies = CompanyResource::collection($companyQuery->get());

        return Inertia::render("Companies/Index", [
            'companies' => $companies,
            'countries' => '',
            'cityes' => '',
            'search' => request('search')
        ]);
        
    }
    
    public function applySearch(Builder $query, string $search)
    {
        return $query->when($search, function ($query, string $search) {
            $query->where('name', 'like', "%{$search}%");
        }); 
    }

    /**
     * Get the list of companies.
     * 
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function getCompanies(Request $request)
    {
        // Get the list of companies
        $companyQuery = Company::search($request);
        $companies = CompanyResource::collection($companyQuery->get());
        
        // Return the list of companies
        return $companies;
    }
    
    /**
     * Create a new company.
     *
     * @param Request $request The HTTP request object containing the company data.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the created company.
     */
    public function createCompany(Request $request)
    {
        // Create a new company using the data from the HTTP request
        $company = Company::create($request->all());

        // Return the created company as a JSON response with a success status code
        return response()->json($company, Response::HTTP_OK);
    }
    
    /**
     * Update an existing company.
     * 
     * @param Request $request The HTTP request object containing the company data.
     * @param int $id The ID of the company to update.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the updated company.
     */
    public function updateCompany(Request $request, int $id)
    {
        // Find the company to update by its ID
        $old_company = Company::where('id', $id)->first();
        
        // Update the company with the data from the HTTP request
        $company = $old_company->update($request->all());
        
        // Return the updated company as a JSON response with a success status code
        return response()->json($old_company, Response::HTTP_OK);
    }
    
    /**
     * Töröljön egy meglévő céget.
     * 
     * @param int $id A törölni kívánt cég azonosítója.
     * @return \Illuminate\Http\JsonResponse A törölt vállalatot tartalmazó JSON-válasz.
     */
    public function deleteCompany(int $id)
    {
        // Keresse meg a törölni kívánt céget az azonosítója alapján
        $old_company = Company::where('id', $id)->first();
        
        // Cég törlése
        $old_company->delete();
        
        // A törölt vállalatot JSON-válaszként küldje vissza sikeres állapotkóddal
        return response()->json($old_company, Response::HTTP_OK);
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
