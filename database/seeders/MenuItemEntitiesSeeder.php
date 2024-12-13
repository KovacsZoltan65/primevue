<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Activitylog\Models\Activity;

class MenuItemEntitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Logolás letiltása
        Activity::disableLogging();
        
        $specimens = MenuItem::where('label', '=', 'specimens')->first();

        $specimens->children()->createMany([
            [ 'label' => 'entities', 'url' => '/entities', 'default_weight' => 3, ],
        ]);

        Activity::enableLogging();
    }
}
