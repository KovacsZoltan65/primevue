<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Entity extends Model
{
    use HasFactory,
        SoftDeletes;

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

    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($request->search, function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                });
            })->where('active', APP_ACTIVE);
    }

    /**
     * A lekérdezést úgy módosítja, hogy csak azokat a szervezeteket tartalmazza,
     * amelyeknél az 'active' mező 'APP_ACTIVE'-ra van állítva.
     *
     * @param Builder $query A lekérdezéskészítő példány.
     * @return Builder A módosított lekérdezéskészítő példány.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', APP_ACTIVE);
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
}
