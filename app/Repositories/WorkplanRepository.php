<?php

namespace App\Repositories;

use App\Interfaces\WorkplanRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Workplan;
use App\Validators\WorkplanValidator;

/**
 * Class WorkplanRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class WorkplanRepository extends BaseRepository implements WorkplanRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Workplan::class;
    }

    public function validator(){
        return WorkplanValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
