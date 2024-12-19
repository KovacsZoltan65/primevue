<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\SubdomainStateRepositoryInterface;
use App\Models\SubdomainState;

/**
 * Class SubdomainStateRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class SubdomainStateRepository extends BaseRepository implements SubdomainStateRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SubdomainState::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
