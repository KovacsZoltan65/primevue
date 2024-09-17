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
    
    public function scopeSearch(Builder $query, Request $request)
    {
        // If search parameter is present, filter results by name or email containing the search term
        return $query->when($request->search, function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            });
        });
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
