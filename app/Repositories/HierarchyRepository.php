<?php

namespace App\Repositories;

use App\Models\Entity;
use App\Services\CacheService;
use App\Traits\Functions;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\HierarchyRepositoryInterface;
use App\Models\Hierarchy;
use Override;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class HierarchyRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class HierarchyRepository extends BaseRepository implements HierarchyRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'hierarchies';

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function addParent(Request $request, $childId)
    {
        try {
            $child = null;

            DB::transaction(function() use($request, $childId, &$child){
                $parentId = $request->input('parent_id');
                $parent = Entity::findOrFail($parentId);
                $child = Entity::findOrFail($childId);

                $child->parents()->attach($parent);

                return $child->load('parents');
            });

            return $child;

        } catch(Exception $ex) {
            $this->logError($ex, 'addParent error', ['request' => $request->all(), 'childId' => $childId]);
            throw $ex;
        }
    }

    public function addChild(Request $request, int $parentId)
    {
        try {
            //
        } catch(Exception $ex) {
            $this->logError($ex, 'addChild error', ['request' => $request->all(), 'parentId' => $parentId]);
            throw $ex;
        }
    }

    public function getHierarchy(int $entityId)
    {
        try {
            //
        } catch(Exception $ex) {
            $this->logError($ex, 'getHierarchy error', ['entityId' => $entityId]);
            throw $ex;
        }
    }

    public function removeChild(int $entityId)
    {
        try {
            //
        } catch(Exception $ex) {
            $this->logError($ex, 'removeChild error', ['entityId' => $entityId]);
            throw $ex;
        }
    }

    public function getBigBosses()
    {
        try {
            //
        } catch(Exception $ex) {
            $this->logError($ex, 'getBigBosses error', []);
            throw $ex;
        }
    }

    public function transferSubordinates(Request $request, $fromManagerId)
    {
        try {
            //
        } catch(Exception $ex) {
            $this->logError($ex, 'transferSubordinates error', ['request' => $request->all(), 'fromManagerId' => $fromManagerId]);
            throw $ex;
        }
    }

    public function swapSubordinates(Request $request)
    {
        try {
            //
        } catch(Exception $ex) {
            $this->logError($ex, 'swapSubordinates error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function validateHierarchy()
    {
        try {
            //
        } catch(Exception $ex) {
            $this->logError($ex, 'validateHierarchy error', []);
            throw $ex;
        }
    }

    private function canReachBigBoss(Entity $entity, $bigBosses)
    {
        try {
            //
        } catch(Exception $ex) {
            $this->logError($ex, 'canReachBigBoss error', ['entity' => $entity, 'bigBosses' => $bigBosses]);
            throw $ex;
        }
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    #[Override]
     public function model()
    {
        return Hierarchy::class;
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
