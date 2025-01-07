<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Interfaces\CompanyRepositoryInterface;
use App\Models\Company;
use App\Services\CacheService;
use App\Traits\Functions;
//use Override;
use Exception;

/**
 * Class CityRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CompanyRepository extends BaseRepository implements CompanyRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'companies';

    public function __construct(CacheService $cacheService)
    {
        $this->tag = Company::getTag();

        $this->cacheService = $cacheService;
    }

    public function getActiveCompanies()
    {
        $model = $this->model();
        $companies = $model::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->where('active','=',1)
            ->get()->toArray();

        return $companies;
    }

    public function getCompanies(Request $request)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $companyQuery = Company::search($request);
                return $companyQuery->get();
            });
        } catch (Exception $ex) {
            $this->logError($ex, 'getCompanies error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function getCompany(int $id)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return Company::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getCompany error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getCompanyByName(string $name)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $name);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return Company::where('name', '=', $name)->firstOrFail();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getCompanyByName error', ['name' => $name]);
            throw $ex;
        }
    }

    public function createCompany(Request $request)
    {
        try{
            $company = null;

            DB::transaction(function() use($request, &$company) {
                // 1. Cég létrehozása
                $company = Company::create($request->all());

                // 2. Kapcsolódó rekordok létrehozása (pl. alapértelmezett beállítások)
                $this->createDefaultSettings($company);

                // 3. Cache törlése, ha releváns
                $this->cacheService->forgetAll($this->tag);
            });
            return $company;
        } catch(Exception $ex) {
            $this->logError($ex, 'createCompany error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function updateCompany($request, int $id)
    {
        try {
            $company = null;
            DB::transaction(function() use($request, $id, &$company) {
                $company = Company::lockForUpdate()->findOrFail($id);
                $company->update($request->all());
                $company->refresh();
                
                $this->cacheService->forgetAll($this->tag);
            });

            return $company;
        } catch(Exception $ex) {
            $this->logError($ex, 'updateCompany error', ['id' => $id, 'request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteCompanies(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:roles,id', // Az id-k egész számok és létező cégek legyenek
            ]);

            $ids = $validated['ids'];
            $deletedCount = 0;

            DB::transaction(function () use ($ids, &$deletedCount) {
                $companies = Company::whereIn('id', $ids)->lockForUpdate()->get();

                $deletedCount = $companies->each(function ($company) {
                    $company->delete();
                })->count();

                // Cache törlése, ha szükséges
                $this->cacheService->forgetAll($this->tag);
            });

            return $deletedCount;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteCompanies error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteCompany(Request $request)
    {
        try {
            $company = null;
            DB::transaction(function() use($request, &$company) {
                $company = Company::lockForUpdate()->findOrFail($request->id);
                $company->delete();

                $this->cacheService->forgetAll($this->tag);
            });

            return $company;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteCompany error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function restoreCompany(Request $request): Company
    {
        try {
            $company = new Company();
            DB::transaction(function() use($request, &$company) {
                $company = Company::withTrashed()->lockForUpdate()->findOrFail($request->id);
                $company->restore();

                $this->cacheService->forgetAll($this->tag);
            });

            return $company;
        } catch(Exception $ex) {
            $this->logError($ex, 'restoreCompany error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function realDeleteCompany(int $id): Company
    {
        try {
            $company = null;
            DB::transaction(function() use($id, &$company) {
                $company = Company::withTrashed()->lockForUpdate()->findOrFail($id);
                $company->forceDelete();

                $this->cacheService->forgetAll($this->tag);
            });


            return $company;
        } catch(Exception $ex) {
            $this->logError($ex, 'realDeleteCompany error', ['id' => $id]);
            throw $ex;
        }
    }

    private function createDefaultSettings(Company $company): void
    {
        //
    }

    public function model()
    {
        return Company::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
