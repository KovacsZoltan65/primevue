<?php

namespace App\Repositories;

use App\Interfaces\ShiftTypeRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\ShiftType;

/**
 * Class ShiftTypeRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ShiftTypeRepositoryEloquent extends BaseRepository implements ShiftTypeRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ShiftType::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
