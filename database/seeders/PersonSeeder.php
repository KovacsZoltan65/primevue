<?php

namespace Database\Seeders;

use App\Models\Entity;
use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Entity::truncate();
        Schema::enableForeignKeyConstraints();

        $this->command->warn(PHP_EOL . __('persons.create_title'));
        
        $count = 500;
        
        $this->command->getOutput()->progressStart($count);
        
        for( $i = 0; $i < $count; $i++ )
        {
            Person::factory(1)->create();
            
            $this->command->getOutput()->progressAdvance();
        }
        
        $this->command->info(PHP_EOL . __('persons.created_title'));
        
    }
}
