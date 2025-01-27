<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Hierarchy;

class HierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Hierarchy::truncate();
        Schema::enableForeignKeyConstraints();

        // Példa entitások ID-i
        $entities = \DB::table('entities')->pluck('id')->toArray();

        if (count($entities) < 2) {
            $this->command->info('Legalább 2 entitás szükséges a hierarchia létrehozásához.');
            return;
        }

        // Hierarchia feltöltése
        $hierarchyData = [];
        $usedPairs = [];

        foreach ($entities as $parent) {
            // Kiválasztunk véletlenszerű gyermekeket
            $children = collect($entities)
                ->reject(fn($child) => $child === $parent || isset($usedPairs["$parent-$child"]))
                ->random(rand(1, min(3, count($entities) - 1)))
                ->toArray();

            foreach ($children as $child) {
                // Eltároljuk a kapcsolatot
                $hierarchyData[] = [
                    'parent_id' => $parent,
                    'child_id' => $child,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $usedPairs["$parent-$child"] = true;
            }
        }

        // Beszúrás az adatbázisba
        \DB::table('hierarchy')->insert($hierarchyData);

        $this->command->info(count($hierarchyData) . ' hierarchikus kapcsolat létrehozva.');

        // Logolás letiltása
        activity()->disableLogging();


        activity()->enableLogging();
    }
}
