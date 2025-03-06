<?php

namespace App\Services\AppSettings;

use App\Models\AppSetting;
use App\Repositories\AppSettingRepository;
use Illuminate\Http\Request;

class AppSettingService
{
    protected AppSettingRepository $appSettingRepository;

    public function __construct(AppSettingRepository $appSettingRepository)
    {
        $this->appSettingRepository = $appSettingRepository;
    }

    public function getActiveAppSettings(): Array
    {
        $appSettings = $this->appSettingRepository->getActiveAppSettings();

        return $appSettings;
    }

    public function getAppSettings(Request $request)
    {
        $appSettings = $this->appSettingRepository->getAppSettings($request);

        return $appSettings;
    }

    public function getAppSetting(int $id): AppSetting
    {
        $appSetting = $this->appSettingRepository->getAppSetting($id);

        return $appSetting;
    }

    public function getAppSettingByKey(string $key): AppSetting
    {
        $appSetting = $this->appSettingRepository->getAppSettingByKey($key);

        return $appSetting;
    }

    public function createAppSetting(Request $request): ?AppSetting
    {
        $appSetting = $this->appSettingRepository->createAppSetting($request);

        return $appSetting;
    }

    public function updateAppSetting(Request $request, int $id): ?AppSetting
    {
        $appSetting = $this->appSettingRepository->updateAppSetting($request, $id);

        return $appSetting;
    }

    public function deleteAppSettings(Request $request): int
    {
        $deletedCount = $this->appSettingRepository->deleteAppSettings($request);

        return $deletedCount;
    }

    public function deleteAppSetting(Request $request): bool
    {
        $result = $this->appSettingRepository->deleteAppSetting($request);

        return $result;
    }

    public function restoreAppSettings(Request $request): ?AppSetting
    {
        $appSetting = $this->appSettingRepository->restoreAppSettings($request);

        return $appSetting;
    }

    public function realDeleteSetting(Request $request): ?AppSetting
    {
        $appSetting = $this->appSettingRepository->realDeleteSetting($request);

        return $appSetting;
    }
}
