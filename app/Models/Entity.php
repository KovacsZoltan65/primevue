<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Carbon\Carbon;

class Entity extends Model
{
    use HasFactory,
        SoftDeletes,
        LogsActivity;

    protected $table = 'entities';
    protected $fillable = [
        'name', 'email',
        'start_date', 'end_date',
        'last_export', 'user_id',
        'company_id', 'active'
    ];
    protected $attributes = [
        'name' => '',
        'email' => '',
        'start_date' => null,
        'end_date' => null,
        'last_export' => null,
        'user_id' => null,
        'company_id' => null,
        'active' => 1
    ];
    protected $casts = [
        'active' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function getStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    /*
     * ==============================================================
     * LOGOLÁS
     * ==============================================================
     */

    // Ha szeretnéd, hogy minden mezőt automatikusan naplózzon:
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true; // Csak a változásokat naplózza
    protected static $logName = 'entities';

    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];

    /*
     * ==============================================================
     */

     public static function getTag(): string
     {
         return self::$logName;
     }

    public function scopeSearch(Builder $query, Request $request)
    {
        $retVal = $query->when($request->search, function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%");
            });
        })->when($request->active, function ($query) use ($request) {
            $query->where('active', $request->active);
        });
        return $retVal;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', '=', 1);
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

    #[Override]
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logFillable();
    }
}
