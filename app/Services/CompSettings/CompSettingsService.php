<?php

namespace App\Services\AppSettings;

use App\Models\CompSetting;
use App\Repositories\CompanyRepository;
use App\Repositories\CompSettingRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\Company;
use Exception;

class CompSettingsService
{
    protected CompSettingRepository $compSettingRepository;

    public function __construct(CompSettingRepository $compSettingRepository)
    {
        $this->compSettingRepository = $compSettingRepository;
    }

    public function getActiveCompSettings()
    {
        try {
            return $this->compSettingRepository->getActiveCompSettings();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getCompSettings(Request $request)
    {
        try {
            return $this->compSettingRepository->getCompSettings($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getCompSetting(int $id): CompSetting
    {
        try {
            return $this->compSettingRepository->getCompSetting($id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getCompSettingByKey(string $key): CompSetting
    {
        try {
            return $this->compSettingRepository->getCompSettingByKey($key);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function createCompSetting(Request $request): CompSetting
    {
        try {
            return $this->compSettingRepository->createCompSetting($request);;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateCompSetting(Request $request, int $id): CompSetting
    {
        try {
            return $this->compSettingRepository->updateCompSetting($request, $id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteCompSettings(Request $request): int
    {
        try {
            return $this->compSettingRepository->deleteCompSettings($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteCompSetting(Request $request): ?CompSetting
    {
        try {
            return $this->compSettingRepository->deleteCompSetting($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function restoreCompSetting(Request $request): ?CompSetting
    {
        try {
            return $this->compSettingRepository->restoreCompSetting($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function realDeleteSetting(Request $request): ?CompSetting
    {
        try {
            return $this->compSettingRepository->realDeleteSetting($request);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
