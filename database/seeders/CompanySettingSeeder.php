<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\Schema;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        CompanySetting::truncate();
        Schema::enableForeignKeyConstraints();

        $settings = [
            ['company_id' => 1, 'key' => 'timezone', 'value' => 'UTC', 'active' => 1],
            ['company_id' => 1, 'key' => 'currency', 'value' => 'USD', 'active' => 1],
        ];

        foreach ($settings as $setting) {
            CompanySetting::create($setting);
        }
    }
}
