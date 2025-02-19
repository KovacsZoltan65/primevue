<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Models\Activity;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        MenuItem::truncate();
        Schema::enableForeignKeyConstraints();

        // Logolás letiltása
        activity()->disableLogging();

        // Főmenü - Home
        $home = MenuItem::create(
            [ 'label' => 'home', 'url' => '/home', 'default_weight' => 1, ]
        );

        $home->children()->create(
            [ 'label' => 'dashboard', 'url' => '/dashboard', 'default_weight' => 1, ]
        );

        // Főmenü - Administration
        $administration = MenuItem::create(
            [ 'label' => 'administration', 'url' => null, 'default_weight' => 2, ]
        );

        $administration->children()->createMany(
            [
                [ 'label' => 'users', 'url' => '/users', 'default_weight' => 1, ],
                [ 'label' => 'roles', 'url' => '/roles', 'default_weight' => 2, ],
                [ 'label' => 'permissions', 'url' => '/permissions', 'default_weight' => 3, ],
                [ 'label' => 'activities', 'url' => '/activities', 'default_weight' => 4, ]
            ]
        );

        // Főmenü - System
        $system = MenuItem::create(
            [ 'label' => 'system', 'url' => null, 'default_weight' => 3, ]
        );

        $geo = $system->children()->create(
            [ 'label' => 'geo', 'url' => null, 'default_weight' => 1, ]
        );

        $geo->children()->createMany(
            [
                [ 'label' => 'countries', 'url' => '/countries', 'default_weight' => 1, ],
                [ 'label' => 'regions', 'url' => '/regions', 'default_weight' => 2, ],
                [ 'label' => 'cities', 'url' => '/cities', 'default_weight' => 3, ],
            ]
        );

        $system->children()->createMany([
            [ 'label' => 'subdomain_states', 'url' => '/subdomain_states', 'default_weight' => 2, ],
            [ 'label' => 'acs_systems', 'url' => '/acs_systems', 'default_weight' => 3, ],
            [ 'label' => 'app_settings', 'url' => '/app_settings', 'default_weight' => 4, ],
            [ 'label' => 'comp_settings', 'url' => '/comp_settings', 'default_weight' => 5, ],
        ]);

        // Főmenü - Specimens
        $specimens = MenuItem::create(
            [ 'label' => 'specimens', 'url' => null, 'default_weight' => 4, ]
        );

        $specimens->children()->createMany(
            [
                [ 'label' => 'companies', 'url' => '/companies', 'default_weight' => 1, ],
                [ 'label' => 'subdomains', 'url' => '/subdomains', 'default_weight' => 2, ],
                [ 'label' => 'entities', 'url' => '/entities', 'default_weight' => 3, ],
            ]
        );

        // Logolás engedélyezése
        activity()->enableLogging();

    }
}
