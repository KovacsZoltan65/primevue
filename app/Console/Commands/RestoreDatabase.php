<?php

namespace App\Console\Commands;

/**
 * php artisan db:restore
 */

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RestoreDatabase extends Command
{
    /**
     * A konzolparancs neve és aláírása.
     *
     * @var string
     */
    protected $signature = "db:backup {db_name} "
            ."{--db_user= : Adatbázis felhasználó}"
            ."{--db_password= : Adatbázis jelszó}"
            ."{--filename= : Fájlnév}";

    /**
     * A konzolparancs leírása.
     *
     * @var string
     */
    protected $description = 'Restore the database';

    /**
     * A folyamat.
     *
     * @var \Symfony\Component\Process\Process
     */
    protected $process;

    /**
     * Konstruktor
     */
    public function __construct() {
        parent::__construct();
        /*
        $this->process = new Process([
            'mysql',
            '-u' . config('database.connections.mysql_db_admin.username'),
            '-p' . config('database.connections.mysql_db_admin.password'),
            config('database.connections.mysql.database'),
            '<',
            storage_path('backups/backup.sql')
        ]);
        */
    }

    /**
     * Hajtsa végre a konzol parancsot.
     *
     * @return void
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
