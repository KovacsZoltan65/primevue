<?php

/**
 * Régiók, megyék
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
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
    public $timestamps = false;

    protected $casts = [
            'country_id' => 'int'
    ];

    protected $fillable = [
        'name',
        'code',
        'country_id'
    ];

    public function scopeSearch(Builder $query, Request $request)
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
