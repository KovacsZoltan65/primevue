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

    protected $attributes = [];

    protected $casts = [
        'active' => 'integer',
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
        'last_export' => 'datetime:Y-m-d'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes = array_merge($this->attributes, [
            'name' => config('entity.defaults.name', ''),
            'email' => config('entity.defaults.email', ''),
            'start_date' => config('entity.defaults.start_date', null),
            'end_date' => config('entity.defaults.end_date', null),
            'last_export' => config('entity.defaults.last_export', null),
            'user_id' => config('entity.defaults.user_id', null),
            'company_id' => config('entity.defaults.company_id', null),
            'active' => config('entity.defaults.active', 1),
        ]);
    }

    public function getStartDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function getEndDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function getLastExportAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function setLastExportAttribute($value)
    {
        $this->attributes['last_export'] = $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    /*
     * ==============================================================
     * LOGOLÁS
     * ==============================================================
     */

    // Ha szeretnéd, hogy minden mezőt automatikusan naplózzon:
    protected static $logAttributes = [
        'name', 'email',
        'start_date', 'end_date',
        'last_export', 'user_id',
        'company_id', 'active'
    ];
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

    public function scopeWithArchived(Builder $query)
    {
        return $query->withTrashed();
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
