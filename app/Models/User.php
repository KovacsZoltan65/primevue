<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\DateTime;

class User extends Authenticatable
{
    use HasFactory,
        Notifiable,
        HasRoles,
        LogsActivity,
        DateTime;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /*
     * ==============================================================
     * LOGOLÁS
     * ==============================================================
     */
    // Ha szeretnéd, hogy minden mezőt automatikusan naplózzon:
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true; // Csak a változásokat naplózza
    protected static $logName = 'users';

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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function getTag(): string
     {
         return self::$logName;
     }

    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($request->search, function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            });
        });
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
     * Azok a cégek, amelyekhez az adott személy tartozik.
     * =========================================================
     * $user = User::find(1);
     * $companies = $user->companies;
     * foreach ($companies as $company) {
     *     echo $company->name;
     * }
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'user_company_rel');
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
            Entity::class,  // Cél tábla
            Company::class, // Közvetítő tábla
            'id',           // A `companies` idegen kulcsa a `person_company`-ban
            'company_id',   // Az `entities` idegen kulcsa
            'id',           // A `users` kulcsa
            'id'            // A `companies` kulcsa
        );
    }

    public function getCreatedAtAttribute()
    {
        return date(
            'd-m-Y H:i',
            strtotime($this->attributes['created_at']));
    }

    public function getUpdatedAtAttribute(): string
    {
        return date(
            'd-m-Y H:i',
            strtotime($this->attributes['updated_at'])
        );
    }

    public function getEmailVerifiedAtAttribute()
    {
        return $this->attributes['email_verified_at'] == null
            ? null
            : date('d-m-Y H:i', strtotime($this->attributes['email_verified_at']));
    }

    #[Override]
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logFillable();
    }

    public function getPermissionArray()
    {
        return $this->getAllPermissions()
        ->mapWithKeys(function ($pr) {
            return [$pr['name'] => true];
        });
    }

    #[Override]
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logFillable();
    }
}
