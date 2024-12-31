<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use App\Models\CompSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

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

        activity()->disableLogging();

        $classes = [
            \App\Models\Company::getTag(),
            \App\Models\Auth\Role::getTag(),
            \App\Models\Auth\Permission::getTag(),

            \App\Models\Subdomain::getTag(),
            \App\Models\SubdomainState::getTag(),

            \App\Models\Hierarchy::getTag(),

            \App\Models\Person::getTag(),
            \App\Models\Entity::getTag(),
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

        $classes = [
            AppSetting::getTag(),
            CompSetting::getTag(),
        ];

        $permissions = [
            'list', 'create', 'edit', 'delete'
        ];

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

        activity()->enableLogging();
    }
}
