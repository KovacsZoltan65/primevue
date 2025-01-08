<?php

namespace App\Repositories;

use App\Entities\ACS;
use App\Interfaces\ACSRepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ACSRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ACSRepository extends BaseRepository implements ACSRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ACS::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
