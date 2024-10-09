<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Subdomain::truncate();
        Schema::enableForeignKeyConstraints();
        
        $company_count = \App\Models\Company::all()->count();
        
        $arr_entities = [];
        
        for( $i = 1; $i == 40; $i++ )
        {
            $start_time = fake()->dateTimeBetween('-10 year', 'now');
            $end_time = fake()->dateBetween('now', '+10 year');
            $entity_name = 'Entity_' . ($i < 10) ? "0{$i}" : $i;
            $email = 'entity_' . ($i < 10) ? "0{$i}" : $i . '@company.com';
            
            array_push(
                $arr_entities,
                [
                    'id' =>  $i,
                    'name' => $entity_name,
                    'email' => $email,
                    'companies_id' => fake()->randomFloat(null, 1, $company_count),
                    'persons_id' => '',
                    'start_date' => $start_time,
                    'end_date' => $end_time,
                    'last_export' => NULL,
                    'active' => 1,
                ]
            );
            
        }
        
        $arr_entities = [
            ['id' =>  1,'name' => '','email' => '', 'companies_id' => '','persons_id' => '','start_date' => '','end_date' => '','last_export' => '','active' => '',],
            ['id' =>  2,'name' => '','email' => '','companies_id' => '','persons_id' => '','start_date' => '','end_date' => '','last_export' => '','active' => '',],
            ['id' =>  3,'name' => '','email' => '','companies_id' => '','persons_id' => '','start_date' => '','end_date' => '','last_export' => '','active' => '',],
            ['id' =>  4,'name' => '','email' => '','companies_id' => '','persons_id' => '','start_date' => '','end_date' => '','last_export' => '','active' => '',],
            ['id' =>  5,'name' => '','email' => '','companies_id' => '','persons_id' => '','start_date' => '','end_date' => '','last_export' => '','active' => '',],
            ['id' =>  6,'name' => '','email' => '','companies_id' => '','persons_id' => '','start_date' => '','end_date' => '','last_export' => '','active' => '',],
            ['id' =>  7,'name' => '','email' => '','companies_id' => '','persons_id' => '','start_date' => '','end_date' => '','last_export' => '','active' => '',],
            ['id' =>  8,'name' => '','email' => '','companies_id' => '','persons_id' => '','start_date' => '','end_date' => '','last_export' => '','active' => '',],
            ['id' =>  9,'name' => '','email' => '','companies_id' => '','persons_id' => '','start_date' => '','end_date' => '','last_export' => '','active' => '',],
            ['id' => 10,'name' => '','email' => '','companies_id' => '','persons_id' => '','start_date' => '','end_date' => '','last_export' => '','active' => '',],
        ];
        
        $count = count($arr_entities);
        
        $this->command->warn(PHP_EOL . __('migration_creating_entities') );
        $this->command->getOutput()->progressStart($count);
        
        foreach($arr_entities as $entity)
        {
            \App\Models\Entity::create($entity);
            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();
        
        $this->command->info(PHP_EOL . __('migration_created_entities') );
    }
}
