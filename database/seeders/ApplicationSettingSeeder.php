<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\Schema;

class ApplicationSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ApplicationSetting::truncate();
        Schema::enableForeignKeyConstraints();
        
        $settings = [
            ['key' => 'default_language', 'value' => 'en', 'active' => 1],
            ['key' => 'theme', 'value' => 'light', 'active' => 1],
        ];
        
        foreach($settings as $setting) {
            ApplicationSetting::create($setting);
        }
    }
}
