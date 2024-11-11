<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class City extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $table = 'cities';

    protected $fillable = [
        'region_id',
        'country_id',
        'latitude',
        'longitude',
        'name'
    ];

    protected $casts = [
        'region_id' => 'int',
        'country_id' => 'int',
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    //protected static $logAttributes = ['region_id', 'country_id', 'latitude', 'longitude', 'name'];

    /**
         * Határozza meg a lekérdezést, hogy csak olyan városokat tartalmazzon,
         * amelyek neve megegyezik a keresési kifejezéssel.
         *
         * @param Builder $query A lekérdezéskészítő példány.
         * @param Request $request Az aktuális HTTP-kérelem objektum,
         *        amely keresési paramétereket tartalmaz.
         * @return Builder A módosított lekérdezéskészítő példány.
         */
    public function scopeSearch(Builder $query, Request $request): Builder
    {
        // If search parameter is present, filter results by name or email containing the search term
        return $query->when($request->search, function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            });
        });
    }

    /**
     * Határozza meg a lekérdezést, hogy csak az aktív városokat tartalmazza.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('active', APP_ACTIVE);
    }

    /**
     * Get the region that owns the City
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    /**
     * Get the country that owns the City
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
