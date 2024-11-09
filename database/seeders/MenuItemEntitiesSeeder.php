<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuItemEntitiesSeeder extends Seeder
{
    /**
     * Futtassa az adatbázis-seed -et.
     *
     * Ez a seed hozzáadja az "Entitások" menüpontot a "Példányok" menühöz.
     * Az "Entitások" menüpont a maximális alapértelmezett súllyal kerül hozzáadásra.
     */
    public function run(): void
    {
        // Keresse meg a "Példányok" menüpontot a címkéje alapján
        $menu_specimens = MenuItem::findByLabel('specimens');

        // Ellenőrizze, hogy létezik-e a "Példányok" menüpont
        if ($menu_specimens) {
            // Lekéri a menüpontok default_weight oszlopának maximumát,
            // hogy a maximális értéknél nagyobb súllyal kerüljön hozzáadásra az "Entitások" menüpont.
            $max_weight = MenuItem::getMaxDefaultWeight();

            // Hozzáadja az "Entitások" menüpontot a "Példányok" menühöz a maximális alapértelmezett súllyal.
            // A children() metódus határozza meg, hogy a menüpont a "Példányok" menüpont almenüje legyen.
            // A createMany() metódus pedig a menüpontok tömeges létrehozását valósítja meg.
            $menu_specimens->children()->createMany([
                [
                    'label' => 'entities',
                    'url' => '/entities',
                    'default_weight' => $max_weight + 1,
                ],
            ]);
        }
    }
}
