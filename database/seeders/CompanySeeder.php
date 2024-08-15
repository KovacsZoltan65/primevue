<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('companies')->truncate();

        $companies = [
            [ 'id' =>  1, 'name' => 'Company 01', 'country' => 'Hungary', 'city' => 'Budapest' ],
            [ 'id' =>  2, 'name' => 'Company 02', 'country' => 'Hungary', 'city' => 'Győr' ],
            [ 'id' =>  3, 'name' => 'Company 03', 'country' => 'Hungary', 'city' => 'Székesfehérvár' ],
            [ 'id' =>  4, 'name' => 'Company 04', 'country' => 'Hungary', 'city' => 'Kecskemét' ],
            [ 'id' =>  5, 'name' => 'Company 05', 'country' => 'Hungary', 'city' => 'Cegléd' ],
            [ 'id' =>  6, 'name' => 'Company 06', 'country' => 'Hungary', 'city' => 'Sopron' ],
            [ 'id' =>  7, 'name' => 'Company 07', 'country' => 'Hungary', 'city' => 'Pécs' ],
            [ 'id' =>  8, 'name' => 'Company 08', 'country' => 'Hungary', 'city' => 'Miskolc' ],
            [ 'id' =>  9, 'name' => 'Company 09', 'country' => 'Hungary', 'city' => 'Veszprém' ],
            [ 'id' => 10, 'name' => 'Company 10', 'country' => 'Hungary', 'city' => 'Szombathely' ],
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
