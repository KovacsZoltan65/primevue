<?php

namespace App\Repositories;

use App\Models\City;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\CityRepositoryInterface;

/**
 * Class CityRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CityRepository extends BaseRepository implements CityRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return City::class;
    }

    public function getActiveCities()
    {
        $cities = $this->model->select('id', 'name')
            ->orderBy('name')
            ->active()->get()->toArray();
        
        return $cities;
    }
    
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
