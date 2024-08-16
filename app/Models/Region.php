<?php

/**
 * Régiók, megyék
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Spatie\Activitylog\LogOptions;
//use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Region
 * 
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $country_id
 *
 * @package App\Models
 */
class Region extends Model
{
    use HasFactory
        //, LogsActivity
    ;
    
    protected $table = 'regions';
    public $timestamps = false;

    protected $casts = [
            'country_id' => 'int'
    ];

    protected $fillable = [
            'name',
            'code',
            'country_id'
    ];

    /**
     * A tevékenység naplózásakor naplózott attribútumok.
     *
     * @var array<string>
     */
    protected static $logAttributes = [];

	/**
     * A tevékenységnaplózást kiváltó esemény(ek).
     *
     * @var array<string>
     */
    protected static $recordEvents = [
        'created', // Új könyv beillesztésekor
        'updated',  // Amikor egy meglévő könyvet frissítenek
        'deleted',  // Amikor egy könyvet törölnek
    ];

	/**
     * A getActivitylogOptions metódus felülbírálása
     * 
     * Ezzel a módszerrel konfigurálhatóak a tevékenységnapló naplózási beállításai.
     * A tevékenységi napló létrehozásakor vagy frissítésekor hívják meg.
     *
     * @return LogOptions
     */
    //#[\Override]
    //public function getActivitylogOptions(): LogOptions
    //{
        // Állítsa be az alapértelmezett naplózási beállításokat
    //    return LogOptions::defaults()
            // Naplózza az összes kitölthető attribútumot
    //        ->logFillable()
            // Minden esemény naplózása
    //        ->logAllEvents();
    //}
}
