<?php

/**
 * Oszágok
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Country
 * 
 * @property int $id
 * @property string $name
 * @property string $code
 *
 * @package App\Models
 */
class Country extends Model
{
	use HasFactory,
            LogsActivity;

	protected $table = 'countries';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'code'
	];

	/**
     * A tevékenység naplózásakor naplózott attribútumok.
     *
     * @var array<string>
     */
    protected static $logAttributes = [
		'name',
		'code'
	];

	/**
     * A tevékenységnaplózást kiváltó esemény(ek).
     *
     * @var array<string>
     */
    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];

	/**
     * A getActivitylogOptions metódus felülbírálása
     * 
     * Ezzel a módszerrel konfigurálhatóak a tevékenységnapló naplózási beállításai.
     * A tevékenységi napló létrehozásakor vagy frissítésekor hívják meg.
     *
     * @return LogOptions
     */
    #[\Override]
    public function getActivitylogOptions(): LogOptions
    {
        // Állítsa be az alapértelmezett naplózási beállításokat
        return LogOptions::defaults()
            // Naplózza az összes kitölthető attribútumot
            ->logFillable()
            // Minden esemény naplózása
            ->logAllEvents();
    }
}
