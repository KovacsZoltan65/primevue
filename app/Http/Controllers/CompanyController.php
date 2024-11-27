<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse as JsonResponse2;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * A CompanyController osztálya a cégek listázásához, létrehozásához, módosításához és
 * törléséhez szükséges metódusokat tartalmazza.
 *
 * @package App\Http\Controllers
 */
class CompanyController extends Controller
{
    /**
     * Jelenítse meg a cégek listáját.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request): InertiaResponse
    {
        // A City modelben a városok listáját adjuk vissza, azokkal a mezőkkel, amelyek
        // az Inertia oldalakon használtak.
        $cities = City::select('id', 'name')
            ->orderBy('name')
            ->active()
            ->get()->toArray();
        $countries = Country::select('id', 'name')
            ->orderBy('name')
            ->active()
            ->get()->toArray();

        // Adjon vissza egy Inertia választ a vállalatok és a keresési paraméterek megadásával.
        return Inertia::render("Companies/Index", [
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
        $companies = new AnonymousResourceCollection([], CompanyResource::class);
        
        try {
            
            // Valami kód, ami hibát dobhat
            //throw new \Exception("Database connection failed!");
            
            
            // Szerezd meg a cégek listáját
            $companyQuery = Company::search($request);
            
            // JSON-válaszként adja vissza a cégek listáját
            $companies = CompanyResource::collection($companyQuery->get());
            
        } catch (Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompanies error',
                'route' => request()->path(),
            ]);
            
        }
        
        return $companies;
    }

    /**
     * Szerezd meg egy cég adatait az azonosítója alapján.
     *
     * @param int $id A lekérni kívánt cég azonosítója.
     * @return JsonResponse A cég adatait tartalmazó JSON-válasz.
     */
    public function getCompany(int $id): JsonResponse
    {
        $company = null;
        $response = Response::HTTP_OK;

        try {
            $company = Company::find($id);
        } catch(  Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompany error',
                'route' => request()->path(),
            ]);

            $response = Response::HTTP_NOT_FOUND;
        }

        return response()->json($company, $response);
    }
    
    /**
     * Szerezd meg egy cég adatait a neve alapján.
     *
     * @param string $name A lekérni kívánt cég neve.
     * @return JsonResponse A cég adatait tartalmazó JSON-válasz.
     */
    public function getCompanyByName(string $name)
    {
        $company = Company::where('name', '=', $name)
            ->get();

        return response()->json($company, Response::HTTP_OK);
    }
    
    /**
     * Hozzon létre egy új céget.
     *
     * @param Request $request A vállalati adatokat tartalmazó HTTP kérési objektum.
     * @return JsonResponse2 A létrehozott vállalatot tartalmazó JSON-válasz.
     */
    public function createCompany(StoreCompanyRequest $request): JsonResponse
    {
        try {
            // Hozzon létre egy új céget a HTTP-kérés adatainak felhasználásával
            $company = Company::create($request->all());

            // Sikeres válasz
            return response()->json([
                'success' => true,
                'message' => __('command_company_created', ['id' => $request->id]),
                'data' > $company
            ], Response::HTTP_CREATED);
        } catch( QueryException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_COMPANY_DATABASE_ERROR',
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'CREATE_COMPANY_DATABASE_ERROR',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch( Exception $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'createCompany general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Frissítsen egy meglévő céget.
     *
     * @param Request $request A vállalati adatokat tartalmazó HTTP kérési objektum.
     * @param int $id A frissítendő cég azonosítója.
     * @return JsonResponse2 A frissített vállalatot tartalmazó JSON-válasz.
     */
    public function updateCompany(UpdateCompanyRequest $request, int $id): JsonResponse
    {
        try {
            // Keresse meg a frissítendő céget az azonosítója alapján
            $company = Company::findOrFail($id);

            // Frissítse a vállalatot a HTTP-kérés adataival
            $company->update($request->all());
            // Frissítjük a modelt
            $company->update();

            // A frissített vállalatot JSON-válaszként küldje vissza sikeres állapotkóddal
            return response()->json([
                'success' => APP_TRUE,
                'message' => 'COMPANY_UPDATED_SUCCESSFULLY',
                'data' => $company,
            ], Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            // Ha a cég nem található
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_UPDATE_COMPANY', // updateCompany not found error
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'COMPANY_NOT_FOUND', // The specified company was not found
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANY', // updateCompany database error
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'DB_ERROR_COMPANY', // Database error occurred while updating the company
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'updateCompany general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Töröljön egy meglévő céget.
     *
     * @param int $id A törölni kívánt cég azonosítója.
     * @return JsonResponse2 A törölt vállalatot tartalmazó JSON-válasz.
     */
    public function deleteCompany(int $id): JsonResponse
    {
        try {
            // Keresse meg a törölni kívánt céget az azonosítója alapján
            $company = Company::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Company deleted successfully.',
                'data' => $company,
            ], Response::HTTP_OK);
        } catch( QueryException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteCompany database error',
                'route' => request()->path(),
            ]);
        
            return response()->json([
                'error' => 'Database error occurred while deleting the company.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteCompany general error',
                'route' => request()->path(),
            ]);
        
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteCompanies(Request $request): JsonResponse
    {
        try {
            // Az azonosítók tömbjének validálása
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:companies,id', // Az id-k egész számok és létező cégek legyenek
            ]);
            
            // Az azonosítók kigyűjtése
            $ids = $validated['ids'];
            
            // A cégek törlése
            $deletedCount = Company::whereIn('id', $ids)->delete();
            
            // Válasz visszaküldése
            return response()->json([
                'success' => true,
                'message' => 'Selected companies deleted successfully.',
                'deleted_count' => $deletedCount,
            ], Response::HTTP_OK);
        } catch( ValidationException $ex ){
            // Validációs hiba logolása
            ErrorController::logClientValidationError($request);

            // Kliens válasz
            return response()->json([
                'error' => 'Validation error occurred',
                'details' => $ex->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( QueryException $ex ) {
            // Adatbázis hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteCompanies database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'Database error occurred while deleting the selected companies.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            // Általános hiba logolása és visszajelzés
            ErrorController::logServerError($ex, [
                'context' => 'deleteCompanies general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
