<?php

namespace App\Repositories;

use App\Http\Controllers\ErrorController;
use App\Interfaces\CompanyRepositoryInterface;
use App\Models\Company;
use App\Services\CacheService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use TheSeer\Tokenizer\Exception;

/**
 * Class CityRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CompanyRepository extends BaseRepository implements CompanyRepositoryInterface
{
    protected CacheService $cacheService;

    protected string $tag = 'companies';

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }


    public function getActiveCompanies()
    {
        $companies = $this->model->select('id', 'name')
            ->orderBy('name')
            ->active()->get()->toArray();

        return $companies;
    }

    public function getCompanies(Request $request)
    {
        try
        {
            $cacheKey = "{$this->tag}_" . md5(json_encode($request->all()));

            return $this->cacheService->remember('companies', $cacheKey, function () use ($request) {
                $companyQuery = Company::search($request);
                return $companyQuery->get();
            });

        } catch (QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompanies query error',
                'params' => ['filters' => $request],
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            throw $ex;
        } catch (Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompanies general error',
                'params' => ['filters' => $request],
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            throw $ex;
        }
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Company::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
