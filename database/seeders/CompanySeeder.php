<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        //\DB::table('companies')->truncate();
        Company::truncate();

        Schema::enableForeignKeyConstraints();

        $companies = [
            [ 'id' =>  1, 'name' => 'Company 01', 'country_id' => 92, 'city_id' =>  1 ],
            [ 'id' =>  2, 'name' => 'Company 02', 'country_id' => 92, 'city_id' =>  2 ],
            [ 'id' =>  3, 'name' => 'Company 03', 'country_id' => 92, 'city_id' =>  3 ],
            [ 'id' =>  4, 'name' => 'Company 04', 'country_id' => 92, 'city_id' =>  4 ],
            [ 'id' =>  5, 'name' => 'Company 05', 'country_id' => 92, 'city_id' =>  5 ],
            [ 'id' =>  6, 'name' => 'Company 06', 'country_id' => 92, 'city_id' =>  6 ],
            [ 'id' =>  7, 'name' => 'Company 07', 'country_id' => 92, 'city_id' =>  7 ],
            [ 'id' =>  8, 'name' => 'Company 08', 'country_id' => 92, 'city_id' =>  8 ],
            [ 'id' =>  9, 'name' => 'Company 09', 'country_id' => 92, 'city_id' =>  9 ],
            [ 'id' => 10, 'name' => 'Company 10', 'country_id' => 92, 'city_id' => 10 ],
            [ 'id' => 11, 'name' => 'Company 11', 'country_id' => 92, 'city_id' =>  1 ],
            [ 'id' => 12, 'name' => 'Company 12', 'country_id' => 92, 'city_id' =>  2 ],
            [ 'id' => 13, 'name' => 'Company 13', 'country_id' => 92, 'city_id' =>  3 ],
            [ 'id' => 14, 'name' => 'Company 14', 'country_id' => 92, 'city_id' =>  4 ],
            [ 'id' => 15, 'name' => 'Company 15', 'country_id' => 92, 'city_id' =>  5 ],
            [ 'id' => 16, 'name' => 'Company 16', 'country_id' => 92, 'city_id' =>  6 ],
            [ 'id' => 17, 'name' => 'Company 17', 'country_id' => 92, 'city_id' =>  7 ],
            [ 'id' => 18, 'name' => 'Company 18', 'country_id' => 92, 'city_id' =>  8 ],
            [ 'id' => 19, 'name' => 'Company 19', 'country_id' => 92, 'city_id' =>  9 ],
            [ 'id' => 20, 'name' => 'Company 20', 'country_id' => 92, 'city_id' => 10 ],
        ];

        $count = count($companies);

        $this->command->warn(PHP_EOL . 'Creating companies...');
        $this->command->getOutput()->progressStart($count);

        foreach($companies as $company){
            Company::create($company);
            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();

        $this->command->info(PHP_EOL . 'Companies created');
    }
}
