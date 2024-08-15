<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render("Companies/Index");
        /*
        $companyQuery = Company::search($request);
        //$companies = CompanyResource::collection($companyQuery->paginate(5));
        $companies = CompanyResource::collection($companyQuery->get());

        return Inertia::render("Companies/Index", [
            'companies' => $companies,
            'search' => request('search')
        ]);
        */
    }
    
    public function applySearch(Builder $query, string $search)
    {
        return $query->when($search, function ($query, string $search) {
            $query->where('name', 'like', "%{$search}%");
        }); 
    }

    public function getCompanies(Request $request)
    {
        $companyQuery = Company::search($request);
        $companies = CompanyResource::collection($companyQuery->get());

        return $companies;
    }
    
    public function createCompany(Request $request)
    {
        $company = Company::create($request->all());

        return response()->json($company, Response::HTTP_OK);
    }
    
    public function updateCompany(Request  $request, int $id)
    {
        $old_company = Company::where('id', $id)->first();
        
        $company = $old_company->update($request->all());
        
        return response()->json($company, Response::HTTP_OK);
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
