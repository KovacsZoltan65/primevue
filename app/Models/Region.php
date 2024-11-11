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

    protected $casts = [
        'country_id' => 'int'
    ];

    protected $fillable = [
        'name',
        'code',
        'country_id'
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

/*************  ✨ Codeium Command ⭐  *************/
/**
 * Határozza meg a lekérdezést, hogy csak az aktív régiókat tartalmazza.
 *
 * @param Builder $query A lekérdezéskészítő példány.
 * @return Builder A módosított lekérdezéskészítő példány.
 */
/******  44861449-bf10-46bc-8206-f761aa3e8697  *******/
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', APP_ACTIVE);
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
}
