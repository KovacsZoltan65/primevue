<?php

namespace App\Services\Setting;

use App\Models\AppSetting;
use App\Repositories\AppSettingRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
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
        try {
            return $this->appSettingRepository->getActiveSettings();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAppSettings(Request $request): Collection
    {
        try {
            return $this->appSettingRepository->getSettings($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAppSetting(int $id): AppSetting
    {
        try {
            return $this->appSettingRepository->getAppSetting($id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAppSettingByKey(string $key): AppSetting
    {
        try {
            return $this->appSettingRepository->getAppSettingByKey($key);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function createAppSetting(Request $request): ?AppSetting
    {
        try {
            return $this->appSettingRepository->createAppSetting($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateAppSetting(Request $request, int $id): ?AppSetting
    {
        try {
            return $this->appSettingRepository->updateAppSetting($request, $id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteAppSettings(Request $request): int
    {
        try {
            return $this->appSettingRepository->deleteSettings($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteAppSetting(Request $request): bool
    {
        try {
            return $this->appSettingRepository->deleteAppSetting($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function restoreAppSettings(Request $request): ?AppSetting
    {
        try {
            return $this->appSettingRepository->restoreSettings($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function realDeleteSetting(Request $request): ?AppSetting
    {
        try {
            return $this->appSettingRepository->realDeleteSetting($request);
        } catch (Exception $e) {
            throw $e;
        }
    }
}