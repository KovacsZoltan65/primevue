<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuItemEntitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specimens = MenuItem::where('label', '=', 'specimens')->first();

        $specimens->children()->createMany([
            [ 'label' => 'entities', 'url' => '/entities', 'default_weight' => 3, ],
        ]);
    }
}
