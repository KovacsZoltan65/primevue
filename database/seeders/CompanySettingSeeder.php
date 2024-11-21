<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanySettigRel;
use App\Models\Setting;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        
        Schema::disableForeignKeyConstraints();
        // Törli a meglévő adatokat
        Setting::truncate();
        CompanySettigRel::truncate();
        Schema::enableForeignKeyConstraints();
        
        // Cég adatok lekérése
        $companies = Company::all();
        
        // Beállítások létrehozása
        $setting1 = Setting::create(['name' => 'Settings 1', 'default_value' => 'Default Value 1', 'active' => true]);
        $setting2 = Setting::create(['name' => 'Settings 2', 'default_value' => 'Default Value 2', 'active' => false]);
        $setting3 = Setting::create(['name' => 'Settings 3', 'default_value' => 'Default Value 3', 'active' => true]);
        
        // Kapcsolatok létrehozása
        foreach($companies as $company)
        {
            $rand = $faker->randomElement([0, 1]);
                    
            if( $rand == 0 ) {
                $company->settings()
                    ->attach([
                        $setting1->id => ['value' => 'Custom Value A1', 'active' => true],
                        $setting3->id => ['value' => 'Custom Value A3', 'active' => false],
                    ]);
            }else{
                $company->settings()->attach([
                    $setting2->id => ['value' => 'Custom Value B2'],
                ]);
            }
        }
    }
}
