<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CompanySettigRel extends Pivot
{
    protected $table = 'coompany_setting_rel'; // Kapcsolótábla neve
    
    protected $fillable = [
        'companies_id',
        'settings_id',
        'value', 
        'active',
    ];
    
    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($request->search, function($query) use($request){
            $query->where(function($query) use($request) {
                $query->where('name', 'like', "%{$request->search}%");
            });
        })->active();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', APP_ACTIVE);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }

    public function setting()
    {
        return $this->belongsTo(Setting::class, 'settings_id');
    }
}
