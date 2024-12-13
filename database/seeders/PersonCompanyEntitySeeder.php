<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Entity;
use App\Models\Person;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Models\Activity;

class PersonCompanyEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Logolás letiltása
        activity()->disableLogging();
        
        // Create 3 companies
        $companies = Company::factory()->count(3)->create();

        // Create 3 persons
        $persons = Person::factory()->count(3)->create();

        // Attach each person to 1, 2 or 3 companies.
        $persons->each(function (Person $person): void {
            $person->companies()
            ->attach(Company::all()->random(rand(1, 3))->pluck('id'));
        });

        // Create 1-3 entities in each company
        $companies->each(function (Company $company) {
            Entity::factory()->count(rand(1, 3))->create(['company_id' => $company->id]);
        });

        activity()->enableLogging();
    }
}
