<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppSetting;
use Illuminate\Support\Facades\Schema;
//use Spatie\Activitylog\Models\Activity;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        AppSetting::truncate();
        Schema::enableForeignKeyConstraints();

        // Logolás letiltása
        activity()->disableLogging();

        $settings = [
            ['key' => 'default_language', 'value' => 'en', 'active' => 1],
            ['key' => 'theme', 'value' => 'light', 'active' => 1],
        ];

        foreach($settings as $setting) {
            AppSetting::create($setting);
        }

        activity()->enableLogging();
    }
}
