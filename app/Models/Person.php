<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    use HasFactory,
        SoftDeletes;
    
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
    
    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($request->search, function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                });
            })->where('active', APP_ACTIVE);
    }
    
    /**
     * Azok a cégek, amelyekhez az adott személy tartozik.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'person_company');
    }
    
    /**
     * Azok az entitások, amelyekhez az adott személy a cégén keresztül tartozik.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function entities(): HasManyThrough
    {
        return $this->hasManyThrough(Entity::class, Company::class, 'person_company', 'company_id', 'id', 'id');
    }
}
