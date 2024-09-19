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
     * A régióhoz tartozó ország
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * A régióban levő városok
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities()
    {
        return $this->hasMany(City::class, 'region_id');
    }
}
