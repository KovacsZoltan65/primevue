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
        $model = $this->model();
        $settings = $model::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->where('active','=',1)
            ->get()->toArray();

        return $settings;
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
            $setting = null;
            
            DB::transaction(function() use($request, &$setting) {
                $setting = CompanySetting::create($request->all());
                
                $this->createDefaultSettings($setting);
                
                $this->cacheService->forgetAll($this->tag);
            });
            
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
                $setting = CompanySetting::lockForUpdate()->findOrFail($id);
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

    public function deleteCompSettings(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:roles,id', // Az id-k egész számok és létező cégek legyenek
            ]);
            
            $ids = $validated['ids'];
            $deletedCount = 0;
            
            DB::transaction(function () use ($ids, &$deletedCount) {
                $settings = CompanySetting::whereIn('id', $ids)->lockForUpdate()->get();

                $deletedCount = $settings->each(function ($setting) {
                    $setting->delete();
                })->count();

                // Cache törlése, ha szükséges
                $this->cacheService->forgetAll($this->tag);
            });

            return $deletedCount;
        } catch(Exception $ex) {
            $this->logError($ex, 'deleteCompSettings error', ['request' => $request->all()]);
            throw $ex;
        }
    }
    
    public function deleteCompSetting(Request $request)
    {
        try {
            $setting = null;
            DB::transaction(function() use($request, &$setting) {
                $setting = CompanySetting::lockForUpdate()->findOrFail($request->id);
                $setting->delete();

                $this->cacheService->forgetAll($this->tag);
            });
            
            return $setting;
        } catch (Exception $ex) {
            $this->logError($ex, 'deleteCompSetting error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function restoreCompSetting(Request $request)
    {
        try {
            $setting = null;
            
            DB::transaction(function() use($request, &$setting) {
                $setting = CompanySetting::withTrashed()->lockForUpdate()->findOrFail($request->id);
                $setting->restore();

                $this->cacheService->forgetAll($this->tag);
            });

            return $setting;
        } catch(Exception $ex) {
            $this->logError($ex, 'restoreCompSetting error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function realDeleteSetting(Request $request)
    {
        try {
            $setting = null;
            
            DB::transaction(function() use($request, &$setting) {
                $setting = CompanySetting::withTrashed()->lockForUpdate()->findOrFail($id);
                $setting->forceDelete();
                
                $this->cacheService->forgetAll($this->tag);
            });
            
            

            return $setting;
        } catch(Exception $ex) {
            $this->logError($ex, 'realDeleteSetting error', ['request' => $request->all()]);
            throw $ex;
        }
    }
    
    private function createDefaultSettings(CompanySetting $setting): void
    {
        //
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
