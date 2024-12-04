<?php

/**
 * Oszágok
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Override;
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

    protected $fillable = ['name', 'code', 'active'];

    protected $casts = [
        'active' => 'integer',
    ];

    protected static $logAttributes = [
        'name', 'code', 'active'
    ];
    
    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];
    
    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($request->search, function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            });
        })->where('active', APP_ACTIVE);
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('active', 1);
    }

    /**
     * A kapcsolódó városok listája.
     *
     * Egy országnak több városa is lehet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities()
    {
        return $this->hasMany(City::class, 'country_id');
    }

    /**
     * A kapcsolódó régiók listája.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function regions()
    {
        return $this->hasMany(Region::class, 'region_id');
    }
    
    #[Override]
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logFillable()
            ->logAll();
    }
}

