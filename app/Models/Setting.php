<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Setting extends Model
{
    use HasFactory, 
        SoftDeletes;
    
    protected  $table = 'settings';
    protected $fillable = ['name', 'defailt_value', 'active'];
    
    public function scopeSearch(Builder $query, Request $request): Builder
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

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_setting_rel', 'settings_id', 'companies_id')
            ->withPivot('value', 'active')
            ->withTimestamps();
    }

    /**
     * ===
     * HASZNÁLAT
     * ===
     * 
     * $company_id = 1;
     * $settings = Setting::getCompnySetting($company_id);
     * foreach(){
     *     echo "Setting ID: {$setting->setting_id}, Value: {$setting->value}" . PHP_EOL;
     * }
     * 
     */
    
    
    public function getCompanySetting(int $companyId)
    {
        return $this->leftJoin('company_setting_rel', function ($join) use ($companyId) {
            $join->on('settings.id', '=', 'company_setting_rel.settings_id')
                ->where('company_setting_rel.companies_id', '=', $companyId);
            })
        ->select(
            'settings.id as setting_id',
            'settings.default_value',
            'company_setting_rel.value as company_value'
        )
        ->get()
        ->map(function ($item) {
            $item->value = $item->company_value ?? $item->default_value;
            unset($item->company_value, $item->default_value);
            return $item;
        });
    }
}
