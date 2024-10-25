<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
        
        $system->children()->create(
            [ 'label' => 'subdomain_states', 'url' => '/subdomain_states', 'default_weight' => 2, ]
        );
        
        // Főmenü - Specimens
        $specimens = MenuItem::create(
            [ 'label' => 'specimens', 'url' => null, 'default_weight' => 4, ]
        );
        
        $specimens->children()->createMany(
            [
                [ 'label' => 'companies', 'url' => '/companies', 'default_weight' => 1, ],
                [ 'label' => 'subdomains', 'url' => '/subdomains', 'default_weight' => 2, ],
            ]
        );
    }
}
