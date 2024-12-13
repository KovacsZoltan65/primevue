<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Models\Activity;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        activity()->disableLogging();

        $password = 'password';
        $arr_users = [
            ['id' => 1, 'name' => 'Kovács Zoltán',  'email' => 'zoltan1_kovacs@msn.com',     'password' => Hash::make($password), 'email_verified_at' => '2023-01-01 00:00:00', 'language' => 'hu'],
            ['id' => 2, 'name' => 'Admin',          'email' => 'admin@admin.com',            'password' => Hash::make($password), 'email_verified_at' => '2023-01-01 00:00:00', 'language' => 'hu'],
            ['id' => 3, 'name' => 'Super Admin',    'email' => 'superadmin@laraveltuts.com', 'password' => Hash::make($password), 'email_verified_at' => '2023-01-01 00:00:00', 'language' => 'hu'],
            ['id' => 4, 'name' => 'Admin User',     'email' => 'admin@laraveltuts.com',      'password' => Hash::make($password), 'email_verified_at' => '2023-01-01 00:00:00', 'language' => 'hu'],
            ['id' => 5, 'name' => 'Example User',   'email' => 'test@laraveltuts.com',       'password' => Hash::make($password), 'email_verified_at' => '2023-01-01 00:00:00', 'language' => 'hu'],
        ];
        $count = count($arr_users);

        $this->command->warn(PHP_EOL . 'Creating users...');

        $this->command->getOutput()->progressStart($count);
        foreach($arr_users as $user)
        {
            User::factory()->create($user);
            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();

        $this->command->info(PHP_EOL . 'Users created');

        activity()->enableLogging();
    }
}
