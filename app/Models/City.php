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
    
    protected $casts = [
        'region_id' => 'int',
        'country_id' => 'int',
        'latitude' => 'float',
        'longitude' => 'float'
    ];
    
    protected $fillable = ['region_id', 'country_id', 'latitude', 'longitude', 'name'];

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
}
