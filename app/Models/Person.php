<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Person extends Model
{
    use HasFactory,
        SoftDeletes,
        LogsActivity;
    
    protected $table = 'persons';
    protected $fillable = ['name', 'email', 'password', 'language', 'birthdate', 'active'];
    protected $attributes = [
        'name' => '', 
        'email' => '', 
        'password' => '', 
        'language' => 'hu', 
        'birthdate' => '', 
        'active' => 1
    ];

    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];
    
    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($request->search, function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%")
                          ->orWhere('email', 'like', "%{$request->search}%");
                });
            })->when($request->active, function ($query) use ($request) {
                $query->where('active', $request->active);
            });
    }
    
    /**
     * =========================================================
     * Azok a cégek, amelyekhez az adott személy tartozik.
     * =========================================================
     * $person = Person::find(1);
     * $companies = $person->companies;
     * foreach ($companies as $company) {
     *     echo $company->name;
     * }
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'person_company');
    }
    
    /**
     * =========================================================
     * Azok az entitások, amelyekhez az adott személy a cégén keresztül tartozik.
     * =========================================================
     * $person = Person::find(1);
     * $entities = $person->entities;
     * foreach ($entities as $entity) {
     *     echo $entity->name;
     * }
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function entities(): HasManyThrough
    {
        return $this->hasManyThrough(
            Entity::class,          // Cél tábla
            Company::class,         // Közvetítő tábla
            'id',                  // A `companies` idegen kulcsa a `person_company`-ban
            'company_id',         // Az `entities` idegen kulcsa
            'id',                  // A `persons` kulcsa
            'id'             // A `companies` kulcsa
        );

        /*
        return $this->hasManyThrough(
            Entity::class,      // Cél tábla
            Company::class,     // Közvetítő tábla
            'person_company', 
            'company_id', 
            'id', 
            'id'
        );
        */
    }

    #[Override]
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logFillable();
    }
}
