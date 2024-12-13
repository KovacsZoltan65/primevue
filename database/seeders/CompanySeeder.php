<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Models\Activity;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Company::truncate();
        Schema::enableForeignKeyConstraints();

        // Logolás letiltása
        Activity::disableLogging();
        
        $companies = [
            [ 'id' =>  1, 'name' => 'Company 01', 'directory' => 'company_01', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  1, 'address' => 'Address 01' ],
            [ 'id' =>  2, 'name' => 'Company 02', 'directory' => 'company_02', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  2, 'address' => 'Address 02' ],
            [ 'id' =>  3, 'name' => 'Company 03', 'directory' => 'company_03', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  3, 'address' => 'Address 03' ],
            [ 'id' =>  4, 'name' => 'Company 04', 'directory' => 'company_04', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  4, 'address' => 'Address 04' ],
            [ 'id' =>  5, 'name' => 'Company 05', 'directory' => 'company_05', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  5, 'address' => 'Address 05' ],
            [ 'id' =>  6, 'name' => 'Company 06', 'directory' => 'company_06', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  6, 'address' => 'Address 06' ],
            [ 'id' =>  7, 'name' => 'Company 07', 'directory' => 'company_07', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  7, 'address' => 'Address 07' ],
            [ 'id' =>  8, 'name' => 'Company 08', 'directory' => 'company_08', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  8, 'address' => 'Address 08' ],
            [ 'id' =>  9, 'name' => 'Company 09', 'directory' => 'company_09', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  9, 'address' => 'Address 09' ],
            [ 'id' => 10, 'name' => 'Company 10', 'directory' => 'company_10', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' => 10, 'address' => 'Address 10' ],
            [ 'id' => 11, 'name' => 'Company 11', 'directory' => 'company_11', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  1, 'address' => 'Address 11' ],
            [ 'id' => 12, 'name' => 'Company 12', 'directory' => 'company_12', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  2, 'address' => 'Address 12' ],
            [ 'id' => 13, 'name' => 'Company 13', 'directory' => 'company_13', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  3, 'address' => 'Address 13' ],
            [ 'id' => 14, 'name' => 'Company 14', 'directory' => 'company_14', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  4, 'address' => 'Address 14' ],
            [ 'id' => 15, 'name' => 'Company 15', 'directory' => 'company_15', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  5, 'address' => 'Address 15' ],
            [ 'id' => 16, 'name' => 'Company 16', 'directory' => 'company_16', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  6, 'address' => 'Address 16' ],
            [ 'id' => 17, 'name' => 'Company 17', 'directory' => 'company_17', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  7, 'address' => 'Address 17' ],
            [ 'id' => 18, 'name' => 'Company 18', 'directory' => 'company_18', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  8, 'address' => 'Address 18' ],
            [ 'id' => 19, 'name' => 'Company 19', 'directory' => 'company_19', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  9, 'address' => 'Address 19' ],
            [ 'id' => 20, 'name' => 'Company 20', 'directory' => 'company_20', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' => 10, 'address' => 'Address 20' ],
        ];

        $count = count($companies);

        $this->command->warn(PHP_EOL . __('migration_creating_companies'));
        $this->command->getOutput()->progressStart($count);

        foreach($companies as $company){
            Company::create($company);
            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();

        $this->command->info(PHP_EOL . __('migration_created_companies'));

        Activity::enableLogging();
    }
}
