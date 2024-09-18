<?php

namespace App\Console\Commands;

/**
 * Futtatás:
 * php artisan table:truncate users
 */

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TruncateTableWithForeignKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'table:truncate {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate a table and delete all related foreign keys';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // Szerezze be a tábla nevét a parancs argumentumából
        $table = $this->argument('table');

        // Dobja el az idegen kulcsokat más táblákból,
        // amelyek ennek a táblának az uuid oszlopára hivatkoznak
        $this->dropForeignKeysFromReferencingTables($table, 'uuid');

        // Dobja el az idegen kulcsokat a megadott táblából
        $this->dropForeignKeysFromTable($table);

        // Törli a sorokat a táblából
        DB::table($table)->truncate();

        // Kiír egy siker üzenetet
        $this->info("Table {$table} truncated and foreign keys dropped.");

        // A sikeres végrehajtás jelzésére adja vissza a 0-t
        return 0;
    }

    /**
     * Dobja el az idegen kulcsokat a megadott táblából.
     *
     * A metódus a megadott táblából törli az idegen kulcsokat.
     *
     * @param string $table A tábla neve, amelyb l az idegen kulcsokat el szeretn jük törölni.
     * @return void
     */
    protected function dropForeignKeysFromTable($table): void
    {
        // A megadott tábla idegen kulcsait lekérjük
        $foreignKeys = $this->getForeignKeys($table);

        // Lekérdezzük a táblát, és törli az idegen kulcsokat
        Schema::table($table, function ($table) use ($foreignKeys) {
            // Végigiterálunk az idegen kulcsokon
            foreach ($foreignKeys as $foreignKey) {
                // A táblából eldobja az idegen kulcsot
                $table->dropForeign($foreignKey);
            }
        });
    }

    /**
     * Dobja el az idegen kulcsokat a megadott tábla oszlopára hivatkozó táblákból.
     *
     * A metódus a megadott tábla oszlopára hivatkozó idegen kulcsokat dobja el
     * a más táblákból.
     *
     * @param string $table A tábla neve, amely oszlopára hivatkozik az idegen kulcs.
     * @param string $column A tábla oszlopának a neve, amelyre az idegen kulcs hivatkozik.
     * @return void
     */
    protected function dropForeignKeysFromReferencingTables($table, $column): void
    {
        // A Doctrine DBAL használatával lekérdezzük a táblák listáját
        $schemaManager = DB::getDoctrineSchemaManager();

        // Beállítjuk a DB-platformot
        $databasePlatform = $schemaManager->getDatabasePlatform();
        $databasePlatform->registerDoctrineTypeMapping('enum', 'string');

        // Lekérdezzük az idegen kulcsokat a más táblákból,
        // amelyek a megadott tábla oszlopára hivatkoznak
        $foreignKeys = [];

        foreach ($schemaManager->listTableNames() as $tableName) {
            if ($tableName !== $table) {
                $foreignKeys[$tableName] = [];

                // Lekérdezzük az idegen kulcsokat a táblából
                foreach ($schemaManager->listTableForeignKeys($tableName) as $foreignKey) {
                    // Ellen rizük, hogy az idegen kulcs a megadott tábla oszlopára hivatkozik
                    if ($foreignKey->getForeignTableName() === $table) {
                        $foreignKeys[$tableName][] = $foreignKey->getName();
                    }
                }
            }
        }

        // Törli az idegen kulcsokat a más táblákból
        foreach ($foreignKeys as $tableName => $keys) {
            Schema::table($tableName, function ($table) use ($keys) {
                foreach ($keys as $key) {
                    // Dobja el az idegen kulcsot a táblából
                    $table->dropForeign($key);
                }
            });
        }
    }

    /**
     * Szerezze be a megadott tábla idegen kulcsait.
     *
     * A metódus visszaadja a megadott tábla idegen kulcsait
     * egy tömbben.
     *
     * @param string $table A tábla neve, amelyb l az idegen kulcsokat szeretn jük megkapni.
     * @return array A megadott tábla idegen kulcsainak a listája.
     */
    protected function getForeignKeys($table)
    {
        // A Doctrine DBAL használatával lekérdezzük a tábla idegen kulcsait
        $schemaManager = DB::getDoctrineSchemaManager();
        $keys = $schemaManager->listTableForeignKeys($table);

        // A kapott idegen kulcsokat egy tömbbe tesszük
        $foreignKeys = [];
        foreach ($keys as $key) {
            $foreignKeys[] = $key->getName();
        }

        // Visszaadjuk a megadott tábla idegen kulcsait
        return $foreignKeys;
    }
}
