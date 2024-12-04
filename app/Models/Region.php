<?php

/**
 * Régiók, megyék
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
    use HasFactory, 
        LogsActivity;

    protected $table = 'regions';

    protected $casts = [
        'country_id' => 'int'
    ];

    protected $fillable = [
        'name',
        'code',
        'country_id'
    ];

    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];
    
    protected static $logAttributes = [
        'name', 'code', 'country_id', 'active'
    ];
    
    /**
     * A régiók listázásához keresési feltételek
     *
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeSearch(Builder $query, Request $request): Builder
    {
        return $query->when($request->search, function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            });
        })->where('active', 1);
    }
    
    /**
     * A régióhoz tartozó ország
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * A régióban levő városok
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'region_id');
    }
    
    #[Override]
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logFillable()
            ->logAll();
    }
}
