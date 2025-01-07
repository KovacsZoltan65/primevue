<?php

namespace App\Repositories;

use App\Services\CacheService;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\AppSettingRepositoryInterface;
use App\Models\AppSetting;
use App\Traits\Functions;
use Illuminate\Support\Facades\DB;
use Override;
use \Exception;

/**
 * Class AppSettingRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class AppSettingRepository extends BaseRepository implements AppSettingRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'appSettings';

    public function __construct(CacheService $cacheService)
    {
        $this->tag = AppSetting::getTag();
        $this->cacheService = $cacheService;
    }

    public function getActiveAppSettings()
    {
        $model = $this->model();
        $settings = $model::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->where('active','=',1)
            ->get()->toArray();

        return $settings;
    }

    public function getAppSettings(Request $request)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $appSettingQuery = AppSetting::search($request);
                return $appSettingQuery->get();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getAppSettings error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function getAppSetting(int $id): AppSetting
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return AppSetting::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getAppSettings error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getAppSettingByKey(string $key): AppSetting
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $key);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($key) {
                return AppSetting::where('key', '=', $key)->firstOrFail();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getAppSettingByKey error', ['key' => $key]);
            throw $ex;
        }
    }

    public function createAppSetting(Request $request): AppSetting
    {
        try {
            $setting = null;

            DB::transaction(function()use($request, &$setting) {
                $setting = AppSetting::create($request->all());

                $this->createDefaultSettings($setting);

                $this->cacheService->forgetAll($this->tag);
            });

            return $setting;
        } catch(Exception $ex) {
            $this->logError($ex, 'createAppSetting error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function updateAppSetting(Request $request, int $id): AppSetting
    {
        try {
            $setting = null;
            DB::transaction(function() use($request, $id, &$setting) {
                $setting = AppSetting::lockForUpdate()->findOrFail($id);
                $setting->update($request->all());
                $setting->refresh();

                $this->cacheService->forgetAll($this->tag);
            });

            return $setting;
        } catch(Exception $ex) {
            $this->logError($ex, 'updateAppSetting error', ['id' => $id, 'request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteAppSettings(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:roles,id', // Az id-k egész számok és létező cégek legyenek
            ]);
            $ids = $validated['ids'];
            $deletedCount = 0;

            DB::transaction(function() use($request, &$deletedCount) {
                $settings = AppSetting::whereIn('id', $request->ids)->lockForUpdate()->get();

                $deletedCount = $settings->each(function ($setting) {
                    $setting->delete();
                })->count();

                // Cache törlése, ha szükséges
                $this->cacheService->forgetAll($this->tag);
            });

            return $deletedCount;
        } catch (Exception $ex) {
            $this->logError($ex, 'deleteAppSettings error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteAppSetting(Request $request)
    {
        try {
            $appSetting = AppSetting::findOrFail($request->id);
            $appSetting->delete();

            $this->cacheService->forgetAll($this->tag);

            return $appSetting;
        } catch (Exception $ex) {
            $this->logError($ex, 'deleteAppSetting error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function restoreAppSettings(Request $request)
    {
        try {
            $appSetting = AppSetting::withTrashed()->findOrFail($request->id);
            $appSetting->restore();

            $this->cacheService->forgetAll($this->tag);

            return $appSetting;
        } catch(Exception $ex) {
            $this->logError($ex, 'restoreAppSettings error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    private function createDefaultSettings(AppSetting $setting): void
    {
        //
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    #[Override]
    public function model()
    {
        return AppSetting::class;
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
