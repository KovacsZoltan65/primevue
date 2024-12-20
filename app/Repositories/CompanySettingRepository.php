<?php

namespace App\Repositories;

use App\Services\CacheService;
use App\Traits\Functions;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\CompanySettingRepositoryInterface;
use App\Models\CompanySetting;
use Exception;
use Override;
use Illuminate\Support\Facades\DB;

/**
 * Class CompanySettingRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CompanySettingRepository extends BaseRepository implements CompanySettingRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'comp_settings';

    public function getActiveCompSettings()
    {
        /*
        $model = $this->model();
        $settings = $model::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->where('active','=',1)
            ->get()->toArray();

        return $settings;
        */
    }

    public function getCompSettings(Request $request)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $settingsQuery = CompanySetting::search($request);
                return $settingsQuery->get();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getCompSettings error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function getCompSetting(int $id)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return CompanySetting::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getCompSetting error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getCompSettingByKey(string $key)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $key);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($key) {
                return CompanySetting::where('key', '=', $key)->firstOrFail();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getSettingByKey error', ['key' => $key]);
            throw $ex;
        }
    }

    public function createCompSetting(Request $request)
    {
        try {
            $setting = CompanySetting::create($request->all());

            $this->cacheService->forgetAll($this->tag);

            return $setting;
        } catch(Exception $ex) {
            $this->logError($ex, 'createCompSetting error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function updateCompSetting(Request $request, int $id)
    {
        try {
            $setting = null;

            DB::transaction(function() use($request, $id, &$setting) {
                $setting = CompanySetting::findOrFail($id)->lockForUpdate();
                $setting->update($request->all());
                $setting->refresh();

                $this->cacheService->forgetAll($this->tag);
            });

            return $setting;
        } catch(Exception $ex) {
            $this->logError($ex, 'updateCompSetting error', ['id' => $id, 'request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteCompSettings(Request $request) {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:roles,id', // Az id-k egész számok és létező cégek legyenek
            ]);
            $ids = $validated['ids'];
            $deletedCount = CompanySetting::whereIn('id', $ids)->delete();

            $this->cacheService->forgetAll($this->tag);

            return $deletedCount;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteCompSettings error', ['request' => $request->all()]);
            throw $ex;
        }
    }
    
    public function deleteCompSetting(Request $request)
    {
        try {
            $appSetting = CompanySetting::findOrFail($request->id);
            $appSetting->delete();

            $this->cacheService->forgetAll($this->tag);

            return $appSetting;
        } catch (Exception $ex) {
            $this->logError($ex, 'deleteCompSetting error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function restoreCompSetting(Request $request){
        try {
            $compSetting = CompanySetting::withTrashed()->findOrFail($request->id);
            $compSetting->restore();

            $this->cacheService->forgetAll($this->tag);

            return $compSetting;
        } catch(Exception $ex) {
            $this->logError($ex, 'restoreCompSetting error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    #[Override]
     public function model(): string
    {
        return CompanySetting::class;
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
