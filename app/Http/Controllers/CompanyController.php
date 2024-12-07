<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCompanyRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Services\CacheService;
use App\Traits\Functions;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Spatie\Permission\Models\Role;
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
    use AuthorizesRequests,
        Functions;

    protected string $tag = 'companies';

    public function __construct() {
        $this->middleware('can:companies list', ['only' => ['index', 'applySearch', 'getCompanies', 'getCompany', 'getCompanyByName']]);
        $this->middleware('can:companies create', ['only' => ['createCompany']]);
        $this->middleware('can:companies edit', ['only' => ['updateCompany']]);
        $this->middleware('can:companies delete', ['only' => ['deleteCompany', 'deleteCompanies']]);
        $this->middleware('can:companies restore', ['only' => ['restoreCompany']]);
    }
    /**
     * Jelenítse meg a cégek listáját.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles('companies');
        /*
        $user = \App\Models\User::find(1); // Például az első felhasználó
        dd(
            'user', $user,
            'hasRole admin', $user->hasRole('admin'),
            'has permission companies create', auth()->user()->hasPermissionTo('companies create')
        );
        */
        // Vagy közvetlen jogosultság hozzárendelés:
        //$user->givePermissionTo('edit companies');

        // A City modelben a városok listáját adjuk vissza, azokkal a mezőkkel, amelyek
        // az Inertia oldalakon használtak.
        $cities = City::select('id', 'name')
            ->orderBy('name')
            ->active()->get()->toArray();
        $countries = Country::select('id', 'name')
            ->orderBy('name')
            ->active()->get()->toArray();

        // Adjon vissza egy Inertia választ a vállalatok és a keresési paraméterek megadásával.
        return Inertia::render("Companies/Index", [
            'countries' => $countries,
            'cities' => $cities,
            'search' => request('search'),
            'can' => $roles,
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

    public function getCompanies(Request $request, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "company_" . md5(json_encode($request->all()));

            $companies = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $companyQuery = Company::search($request);
                return CompanyResource::collection($companyQuery->get());
            });

            return response()->json($companies, Response::HTTP_OK);

        } catch (QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANIES',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (Exception $ex) {
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

    public function getCompany(GetCompanyRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            
            $cacheKey = "company_{$request->id}";

            $company = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                return Company::findOrFail($request->id);
            });
            
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

    public function getCompanyByName(string $name, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "company_name_" . md5($name);

            $company = $cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return Company::where('name', '=', $name)->first();
            });

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
                'error' => 'Database error',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (Exception $ex) {
            // Általános hiba naplózása
            ErrorController::logServerError($ex, [
                'context' => 'getCompanyByName general error',
                'route' => request()->path(),
            ]);

            // JSON-választ küld vissza, jelezve, hogy váratlan hiba történt
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createCompany(StoreCompanyRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $company = Company::create($request->all());
            $cacheService->forgetAll($this->tag);
            
            return response()->json($company, Response::HTTP_CREATED);
            
        } catch( QueryException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_COMPANY_DATABASE_ERROR',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => __('command_company_create_database_error'),
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch( Exception $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'createCompany general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCompany(UpdateCompanyRequest $request, int $id, CacheService $cacheService): JsonResponse
    {
        try {
            
            // Keresse meg a frissítendő céget az azonosítója alapján
            $company = Company::findOrFail($id);
            
            // Frissítse a vállalatot a HTTP-kérés adataival
            $company->update($request->all());
            // Frissítjük a modelt
            $company->refresh();

            $cacheService->forgetAll($this->tag);

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

    public function restoreCompany(GetCompanyRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $company = Company::withTrashed()->findOrFail($request->id);
            $company->restore();
            
            $cacheService->forgetAll($this->tag);

            return response()->json([
                'success' => true,
                'message' => 'Company restored successfully',
                'data' => $company, // Az aktuális adatokat is visszaadjuk
            ], Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_RESTORE_COMPANY', // updateCompany not found error
                'route' => request()->path(),
            ]);

            // Ha a rekord nem található
            return response()->json([
                'success' => false,
                'message' => 'Company not found in trashed records',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANY', // updateCompany database error
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'DB_ERROR_COMPANY', // Database error occurred while updating the company
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'restoreCompany general error',
                'route' => request()->path(),
            ]);

            // Általános hibakezelés
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while restoring the company',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteCompany(GetCompanyRequest $request, CacheService $cacheService): JsonResponse
    {
        try {
            $company = Company::findOrFail($request->id);
            $company->delete();

            $cacheService->forgetAll($this->tag);
            
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

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Company not found',
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
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

    public function deleteCompanies(Request $request, CacheService $cacheService): JsonResponse
    {
        try {
            // Az azonosítók tömbjének validálása
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:roles,id', // Az id-k egész számok és létező cégek legyenek
            ]);

            // Az azonosítók kigyűjtése
            $ids = $validated['ids'];

            // A cégek törlése
            $deletedCount = Role::whereIn('id', $ids)->delete();

            $cacheService->forgetAll($this->tag);
            
            // Válasz visszaküldése
            return response()->json([
                'success' => true,
                'message' => 'Selected companies deleted successfully.',
                'deleted_count' => $deletedCount,
            ], Response::HTTP_OK);
        } catch( ValidationException $ex ){
            // Validációs hiba logolása
            //ErrorController::logClientValidationError($request);
            ErrorController::logServerValidationError($ex, $request);

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
