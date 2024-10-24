<?php

namespace App\Console\Commands;

/**
 * php artisan db:backup -h
 * php artisan db:backup db_name
 * php artisan db:backup db_name --db_user=user
 */

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     * 
     * php artisan db:backup -h
     * php artisan db:backup db_name
     * php artisan db:backup db_name --db_user=felhasználó
     * php artisan db:backup db_name --db_password=pwrd
     * php artisan db:backup db_name --filename=file_name
     * php artisan db:backup db_name --db_user=felhasználó --db_password=pwrd
     * php artisan db:backup db_name --db_user=felhasználó --db_password=pwrd --filename=file_name
     *
     * @var string
     */
    protected $signature = "db:backup {db_name} "
            ."{--db_user= : Adatbázis felhasználó}"
            ."{--db_password= : Adatbázis jelszó}"
            ."{--filename= : Fájlnév}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database';

    public function __construct() {
        parent::__construct();
        /*
        $this->process = new Process([
            'mysqldump',
            '-u' . config('database.connections.mysql_db_admin.username'),
            '-p' . config('database.connections.mysql_db_admin.password'),
            config('database.connections.mysql.database'),
            '>',
            storage_path('backups/backup.sql')
        ]);
        */
    }
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Az argumentumok és opciók lekérése
        $dbName = $this->argument('db_name') ?? config('database.connections.mysql.username');
        $dbUser = $this->option('db_user') ?? config('database.connections.username');
        $dbPwrd = $this->option('db_password') ?? config('database.connections.password');
        $file_name = $this->option('filename') ?? config('',env('','default_filename'));
        
\Log::info('$dbName: ' . print_r($dbName, true));
\Log::info('$dbUser: ' . print_r($dbUser, true));
\Log::info('$dbPwrd: ' . print_r($dbPwrd, true));
\Log::info('$file_name: ' . print_r($file_name, true));

    }
}
