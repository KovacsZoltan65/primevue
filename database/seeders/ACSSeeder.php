<?php

namespace Database\Seeders;

use App\Models\ACS;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ACSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ACS::truncate();
        Schema::enableForeignKeyConstraints();
        
        activity()->disableLogging();
        
        $arr_acs = [
            ['id' => 1, 'name' => 'WinAccess','active' => 1],
        ];
        
        $count = count($arr_acs);
        $this->command->warn(PHP_EOL . __('migration_creating_acs_systems') );
        
        $this->command->getOutput()->progressStart($count);
        foreach($arr_acs as $acs)
        {
            SubdomainState::factory()->create($acs);
            $this->command->getOutput()->progressAdvance();
        }
        
        $this->command->getOutput()->progressFinish();
        $this->command->info(PHP_EOL . __('migration_created_acs_systems') );
        
        activity()->enableLogging();
    }
}
