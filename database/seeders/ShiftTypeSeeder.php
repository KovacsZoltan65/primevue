<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\ShiftType;

class ShiftTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ShiftType::truncate();
        Schema::enableForeignKeyConstraints();

        activity()->disableLogging();

        $shiftTypes = [
            [ 'company_id' => 1, 'code' => '008', 'name' => '8 órás', 'trunk_time_start' => '08:00:00', 'trunk_time_end' => '16:30:00', 'edge_time_start' => '08:00:00', 'edge_time_end' => '16:30:00', 'active' => 1, ],
            [ 'company_id' => 1, 'code' => '006', 'name' => '6 órás', 'trunk_time_start' => '10:00:00', 'trunk_time_end' => '16:30:00', 'edge_time_start' => '10:00:00', 'edge_time_end' => '16:30:00', 'active' => 1, ],
            [ 'company_id' => 1, 'code' => '004', 'name' => '4 órás', 'trunk_time_start' => '10:00:00', 'trunk_time_end' => '14:30:00', 'edge_time_start' => '10:00:00', 'edge_time_end' => '14:30:00', 'active' => 1, ],
        ];

        $count = count($shiftTypes);

        $this->command->warn(PHP_EOL . __('migration_creating_shift_types'));
        $this->command->getOutput()->progressStart($count);

        foreach($shiftTypes as $shiftType) {
            ShiftType::create($shiftType);
            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();

        $this->command->info(PHP_EOL . __('migration_created_shift_types'));

        activity()->enableLogging();
    }
}
