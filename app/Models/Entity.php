<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'start_date' => null,
        'end_date' => null,
        'last_export' => null,
        'active' => 1
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'last_export' => 'datetime',
        'active' => 'boolean',
    ];

    public function scopeSerach(Builder $query, Request $request)
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
     * Egy Entity lekérdezése, amelyhez tartozó Company-k vannak
     * =========================================================
     * $entity = Entity::find(1);
     * $companies = $entity->company;
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * =========================================================
     * Summary of parents
     * =========================================================
     * $entity = Entity::find(1);
     * $parents = $entity->parents;
     * foreach ($parents as $parent) {
     *     echo $parent->name;
     * }
     * 
     * Ha a töröltek is kellenek, tedd a végére:
     * ->withTrashed();
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(
            Entity::class, 
            'entity_rel', 
            'child_id', 
            'parent_id'
        )->withTimestamps();
    }

    /**
     * =========================================================
     * Summary of children
     * =========================================================
     * $entity = Entity::find(1);
     * $children = $entity->children;
     * foreach ($children as $child) {
     *     echo $child->name;
     * }
     * 
     * Ha a töröltek is kellenek, tedd a végére:
     * ->withTrashed();
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function children(): BelongsToMany
    {
        return $this->belongsToMany(
            Entity::class, 
            'entity_rel', 
            'parent_id', 
            'child_id'
        )->withTimestamps();
    }
}
