<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Entity;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Models\Activity;

class CompanyEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Logolás letiltása
        activity()->disableLogging();

        // Create 3 companies
        //$companies = Company::factory()->count(3)->create();
        //$companies = Company::all();

        // Create 3 persons
        //$persons = Person::factory()->count(3)->create();
        $users = User::all();

        // Attach each person to 1, 2 or 3 companies.
        $users->each(function(User $user): void {
            $user->companies()->attach(Company::all()->random(rand(1, 10))->pluck('id'));
        });

        // Create 1-3 entities in each company
        //$companies->each(function (Company $company) {
        //    Entity::factory()->count(rand(1, 3))->create(['company_id' => $company->id]);
        //});

        activity()->enableLogging();
    }
}
