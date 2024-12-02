<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Schema;
use DB;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        Role::truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        Schema::enableForeignKeyConstraints();
        
        $companies_list = 'companies list';
        $companies_create = 'companies create';
        $companies_edit = 'companies edit';
        $companies_delete = 'companies delete';
        
        // Jogosultságok létrehozása
        Permission::create(['name' => $companies_list]);
        Permission::create(['name' => $companies_create]);
        Permission::create(['name' => $companies_edit]);
        Permission::create(['name' => $companies_delete]);
        
        // Szerepkörök létrehozása és jogosultságok hozzárendelése
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            $companies_list, 
            $companies_create, 
            $companies_edit, 
            $companies_delete
        ]);
    }
}
