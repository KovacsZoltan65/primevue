<?php

namespace Database\Seeders;

use App\Models\ACS;
use App\Models\Company;
use App\Models\Workplan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Faker\Factory;

class WorkplanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Workplan::truncate();
        Schema::enableForeignKeyConstraints();

        // Logolás letiltása
        activity()->disableLogging();

        $faker = Factory::create();

        // Lekérjük az összes céget
        $companies = Company::all();

        // Lekérjük az összes ACS rendszert
        $acsIds = ACS::pluck('id')->toArray();
        
        $count = count($companies);
        $this->command->warn(PHP_EOL . __('migration_creating_workplans') );
        $this->command->getOutput()->progressStart($count);

        // Minden céghez két munkarend létrehozása
        foreach ($companies as $company) {
            for ($i = 1; $i <= 2; $i++) {
                Workplan::create([
                    // A névhez beépítjük a cég nevét, hogy egyedi legyen
                    'name'       => "{$company->name} Munkarend " . (($i < 10) ? "0{$i}" : (string)$i),
                    'company_id' => $company->id,
                    'acs_id'     => $faker->randomElement($acsIds),
                    'active'     => true,
                ]);
            }

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
        $this->command->info(PHP_EOL . __('migration_created_workplans') );

        activity()->enableLogging();
    }
}
