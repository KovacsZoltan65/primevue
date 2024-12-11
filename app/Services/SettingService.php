<?php

namespace App\Services;

use App\Models\ApplicationSetting;
use App\Models\CompanySetting;

class SettingService
{
    public function get($key, $companyId = null)
    {
        if ($companyId) {
            $companySetting = CompanySetting::where('company_id', $companyId)->where('key', $key)->first();
            if ($companySetting) {
                return $companySetting->value;
            }
        }

        return ApplicationSetting::where('key', $key)->value('value');
    }
}
