<?php

namespace App\Repositories;

use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\AppSettingRepositoryInterface;
use App\Models\AppSetting;
use App\Traits\Functions;
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

    public function getActiveAppSettings(): Array
    {
        $model = $this->model();
        $settings = $model::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->where('active','=',1)
            ->get()->toArray();

        return $settings;
    }

    public function getAppSettings(Request $request): Collection
    {
        $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

        return $this->cacheService->remember($this->tag, $cacheKey, function () use($request) {
            $appSettingQuery = AppSetting::search($request);
            return $appSettingQuery->get();
        });
    }

    public function getAppSetting(int $id): AppSetting
    {
        $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

        return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
            return AppSetting::findOrFail($id);
        });
    }

    public function getAppSettingByKey(string $key): AppSetting
    {
        $cacheKey = $this->generateCacheKey($this->tag, $key);

        return $this->cacheService->remember($this->tag, $cacheKey, function () use ($key) {
            return AppSetting::where('key', '=', $key)->firstOrFail();
        });
    }

    public function createAppSetting(Request $request): ?AppSetting
    {
        $setting = null;

        DB::transaction(function()use($request, &$setting) {
            $setting = AppSetting::create($request->all());

            $this->createDefaultSettings($setting);

            $this->cacheService->forgetAll($this->tag);
        });

        return $setting;
    }

    public function updateAppSetting(Request $request, int $id): ?AppSetting
    {
        $setting = null;
        DB::transaction(function() use($request, $id, &$setting) {
            $setting = AppSetting::lockForUpdate()->findOrFail($id);
            $setting->update($request->all());
            $setting->refresh();

            $this->cacheService->forgetAll($this->tag);
        });

        return $setting;
    }

    public function deleteAppSettings(Request $request): int
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
            'ids.*' => 'integer|exists:roles,id', // Az id-k egész számok és létező cégek legyenek
        ]);
        $ids = $validated['ids'];
        $deletedCount = 0;

        DB::transaction(function() use($ids, &$deletedCount) {
            $settings = AppSetting::whereIn('id', $ids)->lockForUpdate()->get();

            $deletedCount = $settings->each(function ($setting) {
                $setting->delete();
            })->count();

            // Cache törlése, ha szükséges
            $this->cacheService->forgetAll($this->tag);
        });

        return $deletedCount;
    }

    public function deleteAppSetting(Request $request): bool
    {
        $appSetting = AppSetting::lockForUpdate()->findOrFail($request->id);
        $result = $appSetting->delete();

        $this->cacheService->forgetAll($this->tag);

        return $result;
    }

    public function restoreAppSettings(Request $request): ?AppSetting
    {
        $appSetting = AppSetting::withTrashed()->findOrFail($request->id);
        $appSetting->restore();

        $this->cacheService->forgetAll($this->tag);

        return $appSetting;
    }

    public function realDeleteSetting(Request $request): ?AppSetting
    {
        $setting = null;

        DB::transaction(function() use($request, &$setting) {
            $setting = AppSetting::withTrashed()->lockForUpdate()->findOrFail($request->id);
            $setting->forceDelete();

            $this->cacheService->forgetAll($this->tag);
        });

        return $setting;
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
