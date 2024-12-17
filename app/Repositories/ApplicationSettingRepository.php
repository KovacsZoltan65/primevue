<?php

namespace App\Repositories;

use App\Services\CacheService;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\ApplicationSettingRepositoryInterface;
use App\Models\ApplicationSetting;
use App\Traits\Functions;
use Override;
use \Exception;

/**
 * Class ApplicationSettingRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ApplicationSettingRepository extends BaseRepository implements ApplicationSettingRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'app_settings';

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getActiveSettings()
    {
        $model = $this->model();
        $settings = $model::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->where('active','=',1)
            ->get()->toArray();

        return $settings;
    }

    public function getSettings(Request $request)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $appSettingQuery = ApplicationSetting::search($request);
                return $appSettingQuery->get();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getAppSettings error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function getSetting(int $id): ApplicationSetting
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return ApplicationSetting::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getAppSettings error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getSettingByKey(string $key): ApplicationSetting
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $key);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($key) {
                return ApplicationSetting::where('key', '=', $key)->firstOrFail();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getAppSettingByKey error', ['key' => $key]);
            throw $ex;
        }
    }

    public function createSetting(Request $request): ApplicationSetting
    {
        try {
            $setting = ApplicationSetting::create($request->all());

            return $setting;
        } catch(Exception $ex) {
            $this->logError($ex, 'createAppSetting error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function updateSetting(Request $request, int $id): ApplicationSetting
    {
        try {
            $setting = null;
            \DB::transaction(function() use($request, $id, &$setting) {
                $setting = ApplicationSetting::findOrFail($id)->lockForUpdate();
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
            $deletedCount = ApplicationSetting::whereIn('id', $ids)->delete();

            $this->cacheService->forgetAll($this->tag);

            return $deletedCount;
        } catch (Exception $ex) {
            $this->logError($ex, 'deleteAppSettings error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function deleteAppSetting(Request $request)
    {
        try {
            $appSetting = ApplicationSetting::findOrFail($request->id);
            $appSetting->delete();

            $this->cacheService->forgetAll($this->tag);

            return response()->json($appSetting, Response::HTTP_OK);
        } catch (Exception $ex) {
            $this->logError($ex, 'deleteAppSetting error', ['request' => $request->all()]);
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
        return ApplicationSetting::class;
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
