<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Entity extends Model
{
    use HasFactory,
        SoftDeletes,
        LogsActivity;
    
    protected $table = 'entities';
    protected $fillable = ['name', 'email', 'start_date', 'end_date', 'last_export', 'active'];
    protected $attributes = [
        'name' => '', 
        'email' => '',
        'start_date' => '', 
        'end_date' => NULL, 
        'last_export' => NULL,
        'active' => 1
    ];
    
    protected static $logAttributes = [
        'name', 'email', 
        'start_date', 'end_date', 
        'last_export', 'company_id', 
        'active'
    ];
    
    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];
    
    public function scopeSerach(Builder $query, Request $request)
    {
        return $query->when($request->search, function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                });
            })->where('active', APP_ACTIVE);
    }
    
    //public function persons()
    //{
    //    return $this->belongsTo(Person::class, 'person_entity_rel');
    //}
    
    /**
     * =========================================================
     * Egy Entity lekérdezése, amelyhez tartozó Company-k vannak
     * =========================================================
     * $entity = Entity::find(1);
     * $companies = $entity->company;
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    #[Override]
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logFillable()
            ->logAll();
    }
}
