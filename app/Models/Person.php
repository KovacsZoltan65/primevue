<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

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
    
    /*
     * =========================================================
     * Egy Person lekérdezése, amelyhez tartozó Company-k vannak
     * =========================================================
     * $person = Person::find(1);
     * $companies = $person->companies;
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'person_company');
    }
    
    /**
     * =========================================================
     * Person -hoz kapcsolódó Entity -k lekérése
     * =========================================================
     * Entity::class: Az a modell, amelyet le akarsz kérdezni.
     * Company::class: Az a köztes modell, amely összeköti a Person-t és az Entity-t.
     * 'person_company': A köztes táblád neve.
     * 'company_id': A köztes táblában található Company ID.
     * 'id': A Person ID.
     * 'id': Az Entity ID.
     */
    public function entities()
    {
        return $this->hasManyThrough(Entity::class, Company::class, 'person_company', 'company_id', 'id', 'id');
    }
}
