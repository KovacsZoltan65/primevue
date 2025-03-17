<?php

namespace App\Repositories;

use App\Interfaces\EntityCalendarRepositoryInterface;
use App\Services\CacheService;
use App\Traits\Functions;
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
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'entity_calendars';

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
        $this->tag = EntityCalendar::getTag();
    }

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
