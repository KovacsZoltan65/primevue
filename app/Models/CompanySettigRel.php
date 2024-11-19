<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CompanySettigRel extends Pivot
{
    protected $table = 'setting_company_rel'; // Kapcsolótábla neve
    
    protected $fillable = [
        'companies_id',
        'settings_id',
        'value', 
        'is_active',
    ];
    
    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($request->search, function($query) use($request){
            $query->where(function($query) use($request) {
                $query->where('name', 'like', "%{$request->search}%");
            });
        })->active();
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
