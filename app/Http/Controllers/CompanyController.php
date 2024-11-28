<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCompanyRequest;
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
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;
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

    public function getCompanies(Request $request): JsonResponse
    {
        try {
            // A cégek listájának lekérése a request paraméterei alapján
            $companyQuery = Company::search($request);

            // JSON válaszként adja vissza a cégeket
            $companies = CompanyResource::collection($companyQuery->get());

            return response()->json($companies, Response::HTTP_OK);

        } catch (QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANIES',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getCompanies general error',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCompany(GetCompanyRequest $request): JsonResponse
    {
        try {
            $company = Company::findOrFail($request->id);

            return response()->json($company, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompany error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Company not found'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANY',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompany general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCompanyByName(string $name): JsonResponse
    {
        try {
            // Cég lekérdezése név alapján
            $company = Company::where('name', '=', $name)->first();

            if (!$company) {
                // Ha a cég nem található, 404-es hibát adunk vissza
                return response()->json([
                    'success' => APP_FALSE,
                    'error' => 'Company not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json($company, Response::HTTP_OK);

        } catch (QueryException $ex) {
            // Adatbázis hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANY_BY_NAME',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getCompanyByName general error',
                'route' => request()->path(),
            ]);

            // JSON-választ küld vissza, jelezve, hogy váratlan hiba történt
            return response()->json([
                'success' => APP_FALSE, // A művelet nem volt sikeres
                'error' => 'An unexpected error occurred' // Hibaüzenet a visszatéréshez
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // HTTP állapotkód belső szerverhiba miatt
        }
    }

    /**
     * Hozzon létre egy új céget az API-ban.
     * Elküldi a cég adatait a szervernek a POST-kérésben,
     * és az API válaszát visszaadja.
     * @param StoreCompanyRequest $request A cég adatait tartalmazó HTTP-kérés objektum.
     * @return JsonResponse Az API válasza a cég létrehozásáról.
     * @throws QueryException Ha a cég létrehozása során adatbázis-hiba történik.
     * @throws Exception Ha egyéb hiba történik a cég létrehozása során.
     */
    public function createCompany(StoreCompanyRequest $request): JsonResponse
    {
        try {
            // Hozzon létre egy új céget a HTTP-kérés adatainak felhasználásával
            $company = Company::create($request->all());

            // Sikeres válasz
            return response()->json([
                'success' => APP_TRUE,
                'message' => __('command_company_created', ['id' => $request->id]),
                'data' > $company
            ], Response::HTTP_CREATED);
        } catch( QueryException $ex ) {
            // Naplózza a cég létrehozása során észlelt adatbázis-hibát
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_COMPANY_DATABASE_ERROR', // A hiba háttere
                'route' => request()->path(), // Útvonal, ahol a hiba történt
            ]);

            // Adatbázis hiba esetén a hiba részletes leírását is visszaküldi a kliensnek.
            // Ebben az esetben a HTTP-kód 422 lesz.
            return response()->json([
                'success' => APP_FALSE,
                'error' => __('command_company_create_database_error'), // A hiba részletes leírása
                'details' => $ex->getMessage(), // A hiba részletes leírása
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch( Exception $ex ) {
            // A cég létrehozása során fellépő általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'createCompany general error', // Adja meg a hiba kontextusát
                'route' => request()->path(), // Adja meg az útvonalat, ahol a hiba történt
            ]);

            // Ha egyéb hiba történt, akkor a szerveroldali hiba részletes leírását
            // is visszaküldi a kliensnek.
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

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
                'success' => APP_FALSE,
                'error' => 'COMPANY_NOT_FOUND', // The specified company was not found
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANY', // updateCompany database error
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'DB_ERROR_COMPANY', // Database error occurred while updating the company
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'updateCompany general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Töröl egy céget az azonosítója alapján.
     *
     * @param GetCompanyRequest $request
     * @return JsonResponse
     */
    public function deleteCompany(GetCompanyRequest $request): JsonResponse
    {
        try {
            $company = Company::findOrFail($request->id)->delete();

            return response()->json([
                'success' => APP_TRUE,
                'message' => 'Company deleted successfully.',
                'data' => $company,
            ], Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteCompany error',
                'route' => request()->path(),
            ]);

            return response()->json(['error' => 'Company not found'], 404);
        } catch( QueryException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteCompany database error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error occurred while deleting the company.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'deleteCompany general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Több cég törlése egy kérelemben.
     *
     * @param Request $request
     * @return JsonResponse
     */
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
                'success' => APP_FALSE,
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
                'success' => APP_FALSE,
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
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred.',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
