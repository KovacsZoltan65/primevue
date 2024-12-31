<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\CompSetting;

class SettingService
{
    public function get($key, $companyId = null)
    {
        if ($companyId) {
            $compSetting = CompSetting::where('company_id', $companyId)->where('key', $key)->first();
            if ($compSetting) {
                return $compSetting->value;
            }
        }

        return AppSetting::where('key', $key)->value('value');
    }
}
