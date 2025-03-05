<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Exception;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use App\Http\Requests\GetCompanyRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;

use App\Http\Resources\CompanyResource;
use App\Repositories\CityRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\CountryRepository;
use App\Traits\Functions;

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

    protected $cityRepository,
              $countryRepository,
              $companyRepository;

    protected string $tag = '';

    public function __construct(CityRepository $cityRepository, CountryRepository $countryRepository, CompanyRepository $companyRepository)
    {
        $this->cityRepository = $cityRepository;
        $this->countryRepository = $countryRepository;
        $this->companyRepository = $companyRepository;

        $this->tag = Company::getTag();

        $this->middleware("can:{$this->tag} list", ['only' => ['index', 'applySearch', 'getCompanies', 'getCompany', 'getCompanyByName']]);
        $this->middleware("can:{$this->tag} create", ['only' => ['createCompany']]);
        $this->middleware("can:{$this->tag} edit", ['only' => ['updateCompany']]);
        $this->middleware("can:{$this->tag} delete", ['only' => ['deleteCompany', 'deleteCompanies']]);
        $this->middleware("can:{$this->tag} restore", ['only' => ['restoreCompany']]);
    }
    /**
     * Jelenítse meg a cégek listáját.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles($this->tag);

        $cities = $this->cityRepository->getActiveCities();
        $countries = $this->countryRepository->getActiveCountries();

        // Adjon vissza egy Inertia választ a vállalatok és a keresési paraméterek megadásával.
        return Inertia::render("Companies/Index", [
            'countries' => $countries,
            'cities' => $cities,
            'search' => $request->input('search'),
            'can' => $roles,
        ]);
    }

    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function ($query, string $search) {
            $query->where('name', 'like', "%{$search}%");
        });
    }

    public function getActiveCompanies(): JsonResponse
    {
        try {
            $companies = $this->companyRepository->getActiveCompanies();

            return response()->json($companies, Response::HTTP_OK);
        } catch (QueryException $ex) {
            return $this->handleException($ex, 'getActiveCompanies query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'getActiveCompanies general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCompanies(Request $request): JsonResponse
    {
        try {
            $_companies = $this->companyRepository->getCompanies($request);
            $companies = CompanyResource::collection($_companies);

            return response()->json($companies, Response::HTTP_OK);
        } catch (QueryException $ex) {
            return $this->handleException($ex, 'getCompanies query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'getCompanies general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCompany(GetCompanyRequest $request): JsonResponse
    {
        try {
            $company = $this->companyRepository->getCompany($request->id);

            return response()->json($company, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getCompany model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getCompany query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getCompany general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCompanyByName(string $name): JsonResponse
    {
        try {
            $company = $this->companyRepository->getCompanyByName($name);

            return response()->json($company, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getCompanyByName model not found error', Response::HTTP_NOT_FOUND);
        } catch (QueryException $ex) {
            return $this->handleException($ex, 'getCompanyByName query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'getCompanyByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createCompany(StoreCompanyRequest $request): JsonResponse
    {
        try {
            $company = $this->companyRepository->createCompany($request);

            return response()->json($company, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'createCompany query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createCompany general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCompany(UpdateCompanyRequest $request, int $id): JsonResponse
    {
        try{
            $company = $this->companyRepository->updateCompany($request, $id);

            return response()->json($company, Response::HTTP_CREATED);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updateCompany model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updateCompany query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updateCompany general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCompanies(Request $request): JsonResponse
    {
        try {
            $deletedCount = $this->companyRepository->deleteCompanies($request);
            return response()->json($deletedCount, Response::HTTP_OK);

        } catch(ValidationException $ex) {
            return $this->handleException($ex, 'deleteCompanies validation error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteCompanies query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteCompanies general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCompany(GetCompanyRequest $request): JsonResponse
    {
        try {
            $company = $this->companyRepository($request);

            return response()->json($company, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteCompany model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteCompany database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteCompany general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreCompany(GetCompanyRequest $request): JsonResponse
    {
        try {
            $company = $this->companyRepository->restoreCompany($request);

            return response()->json($company, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restoreCompany model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restoreCompany query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restoreCompany general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function realDeleteCompany(GetCompanyRequest $request): JsonResponse
    {
        try {
            $deletedCount = $this->companyRepository->realDeleteCompany($request->id);

            return response()->json($deletedCount, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'realDeleteCompany model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'realDeleteCompany query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'realDeleteCompany general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
