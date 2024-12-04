<?php

namespace Database\Seeders;

use App\Models\User;
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
        
        $classes = [
            'companies', 'roles', 'permissions', 'subdomainstate'
        ];
        
        $permissions = [
            'list', 'create', 'edit', 'delete', 'restore'
        ];
        
        $admin = Role::create(['name' => 'admin']);
        
        foreach($classes as $class)
        {
            foreach($permissions as $permission)
            {
                Permission::create(['name' => "{$class} {$permission}"]);
                $admin->givePermissionTo(["{$class} {$permission}"]);
            }
        }
        
        // Admin szerepköró hozzárendelés
        $user = User::find(1);
        $user->assignRole('admin');
    }
}
