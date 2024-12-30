<?php

namespace App\Repositories;

use App\Models\Country;
use App\Services\CacheService;
use App\Traits\Functions;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\CountryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Override;
use Exception;
use Symfony\Component\HttpFoundation\Response;

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

    public function getActiveCountries()
    {
        $model = $this->model();
        $countries = $model::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->where('active', '=', 1)
            ->get()->toArray();

            return $countries;
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
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);
            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return Country::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getCountry error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getCountryByName(string $name)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $name);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return Country::where('name', '=', $name)->firstOrFail();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getCountryByName error', ['name' => $name]);
            throw $ex; // A kivétel dobása a kontroller szintjére.
        }
    }

    public function createCountry(Request $request)
    {
        try{
            $country = null;
            
            DB::transaction(function() use($request, &$country) {
                $country = Country::create($request->all());
                
                $this->createDefaultSettings($country);
                
                $this->cacheService->forgetAll($this->tag);
            });
            
            return $country;
        } catch(Exception $ex) {
            $this->logError($ex, 'createCountry error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function updateCountry(Request $request, int $id)
    {
        try {
            $country = null;
            DB::transaction(function() use($request, $id, &$country) {
                $country = Country::lockForUpdate()->findOrFail($id);
                $country->update($request->all());
                $country->refresh();

                $this->cacheService->forgetAll($this->tag);
            });
            
            return $country;
        } catch(Exception $ex) {
            $this->logError($ex, 'updateCompany error', ['id' => $id, 'request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteCountries(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:roles,id', // Az id-k egész számok és létező cégek legyenek
            ]);
            
            $ids = $validated['ids'];
            $deletedCount = 0;
            
            \DB::transaction(function () use ($ids, &$deletedCount) {
                $countries = Country::whereIn('id', $ids)->lockForUpdate()->get();

                $deletedCount = $countries->each(function ($country) {
                    $country->delete();
                })->count();

                // Cache törlése, ha szükséges
                $this->cacheService->forgetAll($this->tag);
            });

            return $deletedCount;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteCountries error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteCountry(Request $request)
    {
        try {
            $country = null;
            
            DB::transaction(function() use($request, &$country) {
                $country = Country::lockForUpdate()->findOrFail($request->id);
                $country->delete();

                $this->cacheService->forgetAll($this->tag);
            });

            return $country;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteCountry error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function restoreCountry(Request $request)
    {
        try {
            $country = null;
            DB::transaction(function() use($request, &$country) {
                $country = Country::withTrashed()->lockForUpdate()->findOrFail($request->id);
                $country->restore();

                $this->cacheService->forgetAll($this->tag);
            });

            return $country;
        } catch(Exception $ex) {
            $this->logError($ex, 'restoreCountry error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function realDeleteCountry(Request $request)
    {
        try {
            $country = null;
            DB::transaction(function() use($request, &$country) {
                $country = Country::withTrashed()->lockForUpdate()->findOrFail($request->id);
                $country->forceDelete();

                $this->cacheService->forgetAll($this->tag);
            });

            return $country;
        } catch(Exception $ex) {
            $this->logError($ex, 'realDeleteCountry error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    private function createDefaultSettings(Company $company): void
    {
        //
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
    
    /**
     * Boot up the repository, pushing criteria
     */
    #[Override]
     public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
