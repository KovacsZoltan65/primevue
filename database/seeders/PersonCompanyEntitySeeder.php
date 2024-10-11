<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Entity;
use App\Models\Person;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PersonCompanyEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Company::truncate();
        Person::truncate();
        Entity::truncate();
        Schema::enableForeignKeyConstraints();
        
        $faker = Factory::create();
        
        $this->command->warn(PHP_EOL . 'Companies creating ...');
        
        // Példa cégek létrehozása
        $company_01 = Company::create([ 'id' =>  1, 'name' => 'Company 01', 'directory' => 'company_01', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  1, 'address' => 'Address 01' ]);
        $company_02 = Company::create([ 'id' =>  2, 'name' => 'Company 02', 'directory' => 'company_02', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  2, 'address' => 'Address 02' ]);
        $company_03 = Company::create([ 'id' =>  3, 'name' => 'Company 03', 'directory' => 'company_03', 'registration_number' => fake()->phoneNumber(), 'tax_id' => fake()->phoneNumber(), 'country_id' => 92, 'city_id' =>  3, 'address' => 'Address 03' ]);
        
        $this->command->warn(PHP_EOL . 'Companies created');
        
        $this->command->warn(PHP_EOL . 'Persons creating ...');
        
        // Példa személyek létrehozása
        $person_01 = Person::create(['id' => 1, 'name' => 'Person 01', 'email' => $faker->email(), 'password' => $faker->email(), 'language' => 'hu', 'birthdate' => $faker->date('Y-m-d', '-25 year'), 'active' => 1]);
        $person_02 = Person::create(['id' => 2, 'name' => 'Person 02', 'email' => $faker->email(), 'password' => $faker->email(), 'language' => 'hu', 'birthdate' => $faker->date('Y-m-d', '-25 year'), 'active' => 1]);
        $person_03 = Person::create(['id' => 3, 'name' => 'Person 03', 'email' => $faker->email(), 'password' => $faker->email(), 'language' => 'hu', 'birthdate' => $faker->date('Y-m-d', '-25 year'), 'active' => 1]);
        
        $this->command->warn(PHP_EOL . 'Companies creating ...');
        
        $this->command->warn(PHP_EOL . 'Persons attaching Company ...');
        
        $person_01->companies()->attach([1, 2]);
        $person_02->companies()->attach([2]);
        $person_02->companies()->attach([3]);
        
        $this->command->warn(PHP_EOL . 'Persons attached Companies');
        
        $this->command->warn(PHP_EOL . 'Entity creating in Company ...');
        
        //
        $company_01->entities()->create(['id' => 1, 'name' => 'Entity 01', 'email' => $faker->email(), 'start_date' => $faker->dateTimeBetween('-10 year', 'now'), 'end_date' => $faker->dateTimeBetween('now', '+10 year'), 'last_export' => NULL, 'active' => 1]);
        $company_01->entities()->create(['id' => 2, 'name' => 'Entity 02', 'email' => $faker->email(), 'start_date' => $faker->dateTimeBetween('-10 year', 'now'), 'end_date' => $faker->dateTimeBetween('now', '+10 year'), 'last_export' => NULL, 'active' => 1]);
        
        $company_02->entities()->create(['id' => 3, 'name' => 'Entity 03', 'email' => $faker->email(), 'start_date' => $faker->dateTimeBetween('-10 year', 'now'), 'end_date' => $faker->dateTimeBetween('now', '+10 year'), 'last_export' => NULL, 'active' => 1]);
        $company_02->entities()->create(['id' => 4, 'name' => 'Entity 04', 'email' => $faker->email(), 'start_date' => $faker->dateTimeBetween('-10 year', 'now'), 'end_date' => $faker->dateTimeBetween('now', '+10 year'), 'last_export' => NULL, 'active' => 1]);
        
        $company_03->entities()->create(['id' => 5, 'name' => 'Entity 05', 'email' => $faker->email(), 'start_date' => $faker->dateTimeBetween('-10 year', 'now'), 'end_date' => $faker->dateTimeBetween('now', '+10 year'), 'last_export' => NULL, 'active' => 1]);
        $company_03->entities()->create(['id' => 6, 'name' => 'Entity 06', 'email' => $faker->email(), 'start_date' => $faker->dateTimeBetween('-10 year', 'now'), 'end_date' => $faker->dateTimeBetween('now', '+10 year'), 'last_export' => NULL, 'active' => 1]);
        
        $this->command->warn(PHP_EOL . 'Entity created in Comapny');
    }
}
