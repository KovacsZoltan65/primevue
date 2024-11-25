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
    
    protected static $logAttributes = ['region_id', 'country_id', 'latitude', 'longitude', 'name'];
    
    public function scopeSearch(Builder $query, Request $request)
    {
        /**
         * Módosítsa a lekérdezést a keresési paraméter alapján.
         *
         * Ha a keresési paraméter nem üres, akkor a lekérdezés tartalmazza
         * a feltételt, hogy a város neve tartalmazza a keresési paramétert.
         *
         * @param Builder $query A lekérdezéskészítő példány.
         * @param Request $request A keresési paramétert tartalmazó HTTP kérés objektum.
         * @return Builder A módosított lekérdezéskészítő példány.
         */
        return $query->when($request->search, function (Builder $query) use ($request) {
            return $query->where(function (Builder $query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            });
        });
    }

    /**
     * Határozza meg a lekérdezést, hogy csak az aktív városokat tartalmazza.
     *
     * @param Builder $query A lekérdezéskészítő példány.
     * @return Builder A módosított lekérdezéskészítő példány.
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('active', 1);
    }

    /**
     * Szerezd meg a várost birtokló régiót
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    /**
     * Szerezd meg azt az országot, amelyik a város tulajdonosa
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
