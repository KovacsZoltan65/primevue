<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    
    protected $fillable = [
        'region_id',
        'country_id',
        'latitude',
        'longitude',
        'name'
    ];

    protected static $logAttributes = [
        'region_id',
        'country_id',
        'latitude',
        'longitude',
        'name'
    ];
}
