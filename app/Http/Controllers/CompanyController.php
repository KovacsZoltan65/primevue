<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller
{
    /**
     * Jelenítse meg a cégek listáját.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // Készítsen lekérdezést, amely a keresési paraméterek alapján keres cégeket.
        //$companyQuery = Company::search($request);

        // Szerezze le a vállalatokat a lekérdezésből, és alakítsa őket AnonymousResourceCollection-vé.
        //$companies = CompanyResource::collection($companyQuery->get());

        $cities = City::where('active', 1)->orderBy('name')->select('id', 'name')->get()->toArray();
        $countries = Country::where('active', 1)->orderBy('name')->select('id', 'name')->get()->toArray();

        // Adjon vissza egy Inertia választ a vállalatok és a keresési paraméterek megadásával.
        return Inertia::render("Companies/Index", [
            //'companies' => $companies,
            'countries' => $countries,
            'cities' => $cities,
            'search' => request('search')
        ]);
    }

    /**
     * Módosítsa a lekérdezést a keresési paraméter alapján.
     *
     * Ha a keresési paraméter nem üres, akkor a lekérdezés tartalmazza
     * a feltételt, hogy a vállalat neve tartalmazza a keresési paramétert.
     *
     * @param Builder $query A lekérdezés, amelyet módosítani kell.
     * @param string $search A keresési paraméter.
     * @return Builder A módosított lekérdezés.
     */
    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function ($query, string $search) {
            $query->where('name', 'like', "%{$search}%");
        });
    }

    /**
     * Szerezd meg a cégek listáját.
     *
     * @param Request $request A keresési paramétert tartalmazó HTTP kérelem objektum.
     * @return AnonymousResourceCollection A vállalatok listáját tartalmazó JSON-válasz.
     */
    public function getCompanies(Request $request): AnonymousResourceCollection
    {
        $companies = new AnonymousResourceCollection([], []);
        
        try {
            
            // Valami kód, ami hibát dobhat
            //throw new \Exception("Database connection failed!");
            
            
            // Szerezd meg a cégek listáját
            $companyQuery = Company::search($request);
            
            // JSON-válaszként adja vissza a cégek listáját
            $companies = CompanyResource::collection($companyQuery->get());
            
        } catch (\Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompanies error',
                'route' => request()->path(),
            ]);
            
            ErrorController::logServerError($ex, [
                'context' => 'getCompanies error',
                'route' => request()->path(),
            ]);
            
        }
        
        return $companies;
    }

    public function getCompany(int $id)
    {
        //
    }
    
    public function getCompanyByName(string $name)
    {
        //
    }
    
    /**
     * Hozzon létre egy új céget.
     *
     * @param Request $request A vállalati adatokat tartalmazó HTTP kérési objektum.
     * @return \Illuminate\Http\JsonResponse A létrehozott vállalatot tartalmazó JSON-válasz.
     */
    public function createCompany(Request $request)
    {
        // Hozzon létre egy új céget a HTTP-kérés adatainak felhasználásával
        $company = Company::create($request->all());
//\Log::info('$company: ' . print_r($company, true));
        // A létrehozott vállalatot JSON-válaszként küldje vissza sikeres állapotkóddal
        return response()->json($company, Response::HTTP_OK);
    }

    /**
     * Frissítsen egy meglévő céget.
     *
     * @param Request $request A vállalati adatokat tartalmazó HTTP kérési objektum.
     * @param int $id A frissítendő cég azonosítója.
     * @return \Illuminate\Http\JsonResponse A frissített vállalatot tartalmazó JSON-válasz.
     */
    public function updateCompany(Request $request, int $id)
    {
        // Keresse meg a frissítendő céget az azonosítója alapján
        $old_company = Company::where('id', $id)->first();

        // Frissítse a vállalatot a HTTP-kérés adataival
        $company = $old_company->update($request->all());

        // A frissített vállalatot JSON-válaszként küldje vissza sikeres állapotkóddal
        return response()->json($company, Response::HTTP_OK);
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
}
