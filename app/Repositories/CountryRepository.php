<?php

namespace App\Repositories;

use App\Models\Country;
use App\Services\CacheService;
use App\Traits\Functions;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\CountryRepositoryInterface;
use Override;
use Exception;

/**
 * Class CountryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CountryRepository extends BaseRepository implements CountryRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'countries';

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getCountries(Request $request)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $countryQuery = Country::search($request);
                return $countryQuery->get();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getCountries error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function getCountry(int $id)
    {
        try {
            //
        } catch(Exception $ex) {
            $this->logError($ex, 'getCountry error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getCountryByName(string $name)
    {
        try {
            //
        } catch(Exception $ex) {
            $this->logError($ex, 'getCountryByName error', ['name' => $name]);
            throw $ex;
        }
    }

    public function createCountry(Request $request)
    {
        try {
            //
        } catch(Exception $ex) {
            $this->logError($ex, 'createCountry error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function updateCountry(Request $request, int $id)
    {
        try {
            //
        } catch(Exception $ex) {
            $this->logError($ex, 'updateCountry error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteCountries(Request $request)
    {
        try {
            //
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteCountries error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteCountry(Request $request)
    {
        try {
            //
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteCountry error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function restoreCountry(Request $request)
    {
        try {
            //
        } catch(Exception $ex) {
            $this->logError($ex, 'restoreCountry error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    #[Override]
    public function model()
    {
        return Country::class;
    }

/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Retrieve a list of active countries.
     *
     * by name, and returns the result as an array of country IDs and names.
     * @return array The array of active countries with 'id' and 'name' keys.
     */



/******  69acc8a2-18bd-4f86-96c6-a832f5b990e3  *******/
    public function getActiveCountries()
    {
        $countries = $this->model->select('id', 'name')
            ->orderBy('name')
            ->active()->get()->toArray();
        
        return $countries;
    }
    
    /**
     * Boot up the repository, pushing criteria
     */
    #[Override]
     public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
