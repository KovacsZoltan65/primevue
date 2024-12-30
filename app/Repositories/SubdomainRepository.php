<?php

namespace App\Repositories;

use App\Http\Resources\SubdomainResource;
use App\Services\CacheService;
use App\Traits\Functions;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\SubdomainRepositoryInterface;
use App\Models\Subdomain;
use Exception;
use Override;
use Illuminate\Support\Facades\DB;

/**
 * Class SubdomainRepository.
 *
 * @package namespace App\Repositories;
 */
class SubdomainRepository extends BaseRepository implements SubdomainRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'subdomains';

    public function __construct(CacheService $cacheService)
    {
        $this->tag = Subdomain::getTag();
        
        $this->cacheService = $cacheService;
    }

    public function getActiveSubdomains()
    {
        $model = $this->model();
        $subdomains = $model::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->where('active','=',1)
            ->get()->toArray();

        return $subdomains;
    }

    public function getSubdomains(Request $request)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));
            
            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $subdomainQuery = Subdomain::search($request);
                return SubdomainResource::collection($subdomainQuery->get());
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getSubdomains error', []);
            throw $ex;
        }
    }

    public function getSubdomain(int $id){
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return Subdomain::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getSubdomain error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getSubdomainByName(string $name){
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $name);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return Subdomain::where('name', '=', $name)->firstOrFail();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getSubdomainByName error', ['name' => $name]);
            throw $ex;
        }
    }

    public function createSubdomain(Request $request){
        try {
            $subdomain = Subdomain::create($request->all());

            $this->cacheService->forgetAll($this->tag);

            return $subdomain;
        } catch(Exception $ex) {
            $this->logError($ex, 'createSubdomain error', ['request' => $request]);
            throw $ex;
        }
    }

    public function updateSubdomain(Request $request, int $id){
        try {
            $subdomain = null;

            DB::transaction(function() use($request, $id, &$subdomain) {
                $subdomain = Subdomain::findOrFail($id)->lockForUpdate();
                $subdomain->update($request->all());
                $subdomain->refresh();

                $this->cacheService->forgetAll($this->tag);
            });

            return $subdomain;
        } catch(Exception $ex) {
            $this->logError($ex, 'updateSubdomain error', ['request' => $request]);
            throw $ex;
        }
    }

    public function deleteSubdomains(Request $request){
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:roles,id', // Az id-k egész számok és létező cégek legyenek
            ]);
            $ids = $validated['ids'];
            $deletedCount = Subdomain::whereIn('id', $ids)->delete();

            $this->cacheService->forgetAll($this->tag);

            return $deletedCount;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteSubdomains error', ['request' => $request]);
            throw $ex;
        }
    }

    public function deleteSubdomain(Request $request){
        try {
            $subdomain = Subdomain::findOrFail($request->id);
            $subdomain->delete();

            $this->cacheService->forgetAll($this->tag);

            return $subdomain;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteSubdomain error', ['request' => $request]);
            throw $ex;
        }
    }

    public function restoreSubdomain(Request $request){
        try {
            $subdomain = Subdomain::withTrashed()->findOrFail($request->id);
            $subdomain->restore();

            $this->cacheService->forgetAll($this->tag);

            return $subdomain;
        } catch(Exception $ex) {
            $this->logError($ex, 'restoreSubdomain error', ['request' => $request]);
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
        return Subdomain::class;
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
