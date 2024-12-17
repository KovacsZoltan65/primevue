<?php

namespace App\Repositories;

use App\Services\CacheService;
use App\Traits\Functions;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\CompanySettingRepositoryInterface;
use App\Models\CompanySetting;
use App\Validators\CompanySettingValidator;
use Exception;

/**
 * Class CompanySettingRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CompanySettingRepository extends BaseRepository implements CompanySettingRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'company_settings';

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return CompanySetting::class;
    }

    public function getActiveCompSettings()
    {
        // TODO: Implement getActiveCompSettings() method.
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

            \DB::transaction(function() use($request, $id, &$setting) {
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

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
