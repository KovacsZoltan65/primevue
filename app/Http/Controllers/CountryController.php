<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCountryRequest;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Repositories\CountryRepository;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\CacheService;

use Inertia\Inertia;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\Functions;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected CountryRepository $countryRepository;

    public function __construct(CountryRepository $repository)
    {
        $this->countryRepository = $repository;

        $this->middleware('can:countries list', ['only' => ['index', 'applySearch', 'getCountries', 'getCountry', 'getCountryByName']]);
        $this->middleware('can:countries create', ['only' => ['createCountry']]);
        $this->middleware('can:countries edit', ['only' => ['updateCountry']]);
        $this->middleware('can:countries delete', ['only' => ['deleteCountry', 'deleteCountries']]);
        $this->middleware('can:countries restore', ['only' => ['restoreCountry']]);
    }

    /**
     * Jelenítse meg az erőforrás listáját.
     */
    public function index(Request $request)
    {
        //$cities = City::where('active', 1)->orderBy('name')->get()->toArray();
        //$regions = Region::where('active', 1)->orderBy('name')->get()->toArray();

        return Inertia::render('Geo/Country/Index', [
            //'cities' => $cities,
            //'regions' => $regions,
            'search' => request('search'),
        ]);
    }

    /**
     * Módosítja a lekérdezést az országok név szerinti kereséséhez.
     *
     * Ez a módszer a keresési lekérdezést az adatbázis-lekérdezésre alkalmazza.
     * Ha a keresési lekérdezés nem üres, akkor egy where záradékot ad a lekérdezéshez
     * azzal a feltétellel, hogy az országnévnek tartalmaznia kell a keresési lekérdezést.
     *
     * @param  Builder  $query
     * @param  string  $search
     * @return Builder
     */
    public function applySearch(Builder $query, string $search)
    {
        return $query->when($search, function($query, string $search) {
            // Adjon hozzá egy where záradékot a lekérdezéshez azzal a feltétellel, hogy a
            // az ország nevének tartalmaznia kell a keresési lekérdezést
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }

    public function getCountries(Request $request): JsonResponse
    {
        try {
            $countries = $this->countryRepository->getCountries($request);
            $countries = CountryResource::collection($countries);

            return response()->json($countries, Response::HTTP_OK);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getCountries query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getCountries general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCountry(GetCountryRequest $request): JsonResponse
    {
        try {
            $company = $this->countryRepository->getCountry($request->id);

            return response()->json($company, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getCountry model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getCountry query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getCountry general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCountryByName(string $name): JsonResponse
    {
        try {
            $company = $this->countryRepository->getCountryByName($name);

            return response()->json($company, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getCountryByName model not found error', Response::HTTP_NOT_FOUND);
        } catch (QueryException $ex) {
            return $this->handleException($ex, 'getCountryByName query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'getCountryByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createCountry(StoreCountryRequest $request): JsonResponse
    {
        try {
            $country = $this->countryRepository->createCountry($request);

            return response()->json($country, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'createCountry query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createCountry general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCountry(UpdateCountryRequest $request, int $id): JsonResponse
    {
        try{
            $company = $this->updateCountry($request, $id);

            return response()->json($company, Response::HTTP_CREATED);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updateCountry model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updateCountry query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updateCountry general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCountries(Request $request): JsonResponse
    {
        try {
            $company = $this->countryRepository->deleteCountries($request);

            return response()->json($company, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteCompany model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteCompany database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteCompany database error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCountry(GetCountryRequest $request): JsonResponse
    {
        try {
            $deletedCount = $this->countryRepository->deleteCountry($request);
            return response()->json($deletedCount, Response::HTTP_OK);

        } catch(ValidationException $ex) {
            return $this->handleException($ex, 'deleteCountry model not found error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteCountry query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteCountry general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreCountry(GetCountryRequest $request): JsonResponse
    {
        try {
            $company = $this->countryRepository->restoreCountry($request);

            return response()->json($company, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restoreCountry model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restoreCountry query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restoreCountry general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
