<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanySettigRel extends Pivot
{
    protected $table = 'setting_company_rel'; // Kapcsolótábla neve
    
    protected $fillable = [
        'companies_id',
        'settings_id',
        'value',
    ];
    
    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }

    public function setting()
    {
        return $this->belongsTo(Setting::class, 'settings_id');
    }
}
