<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Company extends Model
{
    use HasFactory,
        SoftDeletes;
    
    protected $table = 'companies';
    protected $fillable = ['name', 'country', 'city'];

    public function scopeSearch(Builder $query, Request $request)
    {
        /*
        // If search parameter is present, filter results by name or email containing the search term
        return $query->when($request->search, function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            });
        })
        // If class_id parameter is present, filter results by that class_id
        ->when($request->country, function ($query) use ($request) {
            $query->where('country', $request->country);
        })
        // If section_id parameter is present, filter results by that section_id
        ->when($request->city, function ($query) use ($request) {
            $query->where('city', $request->city);
        });
        */
    }

    /**
     * Get the country that owns the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        // A vállalat tartozó országa
        // 
        // Az ország, amelyhez a vállalat tartozik.
        // 
        // @return \Illuminate\Database\Eloquent\Relations\BelongsTo
        return $this->belongsTo(Country::class, 'country');
    }

    /**
     * Get the city that owns the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        // A vállalat tartozó városa
        // 
        // Egy vállalatnak pontosan egy városa van.
        // 
        // @return \Illuminate\Database\Eloquent\Relations\BelongsTo
        return $this->belongsTo(City::class, 'city');
    }
    
    /**
     * =========================================================
     * 
     * =========================================================
     * $person = Person::find(1);
     * $entities = $person->entities;
     * foreach ($entities as $entity) {
     *     echo $entity->name;
     * }
     */
    public function persons()
    {
        return $this->belongsToMany(Person::class, 'person_company');
    }
    
    /**
     * =========================================================
     * Egy Company lekérdezése, amelyhez tartozó Entity-je van
     * =========================================================
     * $company = Company::find(1);
     * $entity = $company->entities;
     */
    public function entities()
    {
        return $this->hasMany(Entity::class, 'company_id');
    }
}
