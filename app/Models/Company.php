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
    protected $fillable = ['name', 'country_id', 'city_id', 'directory', 'registration_number', 'tax_id', 'address', 'active'];

    public function scopeSearch(Builder $query, Request $request)
    {
        // Ha van keresési paraméter, szűrje az eredményeket név 
        // vagy a keresési kifejezést tartalmazó e-mail cím alapján
        return $query->when($request->search, function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            });
        })
        // Ha a class_id paraméter jelen van, szűrje az eredményeket a class_id alapján
        ->when($request->country, function ($query) use ($request) {
            $query->where('country', $request->country);
        })
        // Ha szerepel a section_id paraméter, akkor szűrje az eredményeket a section_id alapján
        ->when($request->city, function ($query) use ($request) {
            $query->where('city', $request->city);
        });
    }

/**
 * Módosítsa a lekérdezést úgy, hogy csak az aktív cégeket tartalmazza.
 *
 * @param Builder $query A lekérdezéskészítő példány.
 * @return Builder A módosított lekérdezéskészítő példány.
 */
    public function scopeActive(Builder $query)
    {
        return $query->where('active', APP_ACTIVE);
    }

    /**
     * Szerezd meg azt az országot, amelyik a cég tulajdonosa
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country');
    }

    /**
     * Szerezd meg a várost, amelyik a cég tulajdonosa
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city');
    }

    /**
     * =========================================================
     * Szerezd meg azokat a személyeket, amelyek ehhez a céghez tartoznak.
     * =========================================================
     * $person = Person::find(1);
     * $entities = $person->entities;
     * foreach ($entities as $entity) {
     *     echo $entity->name;
     * }
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function persons()
    {
        return $this->belongsToMany(Person::class, 'person_company');
    }

    /**
     * =========================================================
     * Szerezd meg azokat az entitásokat, amelyek ehhez a céghez tartoznak.
     * =========================================================
     * $company = Company::find(1);
     * $entity = $company->entities;
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entities()
    {
        return $this->hasMany(Entity::class, 'company_id');
    }
}
