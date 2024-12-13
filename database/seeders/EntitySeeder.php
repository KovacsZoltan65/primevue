<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Entity;
use App\Models\Person;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Models\Activity;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Entity::truncate();
        Schema::enableForeignKeyConstraints();
        
        // Logolás letiltása
        Activity::disableLogging();
        
        $faker = Factory::create();
        $company_ids = Company::pluck('id')->toArray();
        $person_ids = Person::pluck('id')->toArray();

        $arr_entities = array_map(function ($i) use ($faker, $company_ids, $person_ids): array {
            return [
                'id' => $i,
                'name' => "Entity_{$i}",
                'email' => "entity_{$i}@company.com",
                'companies_id' => $faker->randomElement($company_ids),
                'persons_id' => $faker->randomElement($person_ids),
                'start_date' => $faker->dateTimeBetween('-10 year', 'now'),
                'end_date' => $faker->dateTimeBetween('now', '+10 year'),
                'last_export' => NULL,
                'active' => 1,
            ];
        }, range(1, 40));
        
        $count = count($arr_entities);
        
        $this->command->warn(PHP_EOL . __('migration_creating_entities') );
        $this->command->getOutput()->progressStart($count);
        
        foreach($arr_entities as $entity)
        {
            Entity::create($entity);
            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
        
        $this->command->info(PHP_EOL . __('migration_created_entities') );

        Activity::enableLogging();
    }
}
