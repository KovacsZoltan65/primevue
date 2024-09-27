<?php

namespace Database\Seeders;

use App\Models\SubdomainState;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubdomainStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        SubdomainState::truncate();
        Schema::enableForeignKeyConstraints();

        $arr_subdomain_states = [
            ['id' => 1, 'name' => 'Aktív',],
            ['id' => 2, 'name' => 'Felfüggesztve',],
            ['id' => 3, 'name' => 'Leállítva(HQ)',],
            ['id' => 4, 'name' => 'Leállítva(példány)',],
        ];
        $count = count($arr_subdomain_states);
        
        $this->command->warn(PHP_EOL . __('migration_creating_subdomain_states') );
        
        $this->command->getOutput()->progressStart($count);
        foreach($arr_subdomain_states as $state)
        {
            SubdomainState::factory()->create($state);
            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();
        
        $this->command->info(PHP_EOL . __('migration_created_subdomain_states') );
    }
}
