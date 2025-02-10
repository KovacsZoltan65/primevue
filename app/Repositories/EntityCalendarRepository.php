<?php

namespace App\Repositories;

use App\Interfaces\EntityCalendarRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\EntityCalendar;

/**
 * Class EntityCalendarRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class EntityCalendarRepository extends BaseRepository implements EntityCalendarRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return EntityCalendar::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
