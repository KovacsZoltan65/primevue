<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Subdomain extends Model
{
    use HasFactory,
        SoftDeletes,
        LogsActivity;

    protected $table = 'subdomains';

    protected $casts = [
        'db_port' => 'integer',
        'notification' => 'bool',
        'state_id' => 'integer',
        'is_mirror' => 'bool',
        'sso' => 'bool',
        'acs_id' => 'int',
        'active' => 'integer',
    ];

    protected $fillable = [
        'subdomain', 
        'url', 
        'name',
        'db_host', 
        'db_port', 
        'db_name', 
        'db_user', 
        'db_password',
        'notification', 
        'state_id', 
        'is_mirror', 
        'sso',
        'acs_id', 
        'active',
    ];

    /*
     * ==============================================================
     * LOGOLÁS
     * ==============================================================
     */

    // Ha szeretnéd, hogy minden mezőt automatikusan naplózzon:
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true; // Csak a változásokat naplózza
    protected static $logName = 'subdomain';

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

    public function scopeSearch(Builder $query, Request $request): Builder
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
    
    /**
     * Aldomaint keres az azonosítója alapján.
     *
     * @param int $subdomain_id A keresendő aldomain azonosítója.
     * @return Subdomain|null Az adott azonosítónak megfelelő aldomain, vagy nulla, ha nem található.
     */
    public static function getSubdomainById(int $subdomain_id): Subdomain
    {
        //return self::where('id', $subdomain_id)->first();
        return self::find($subdomain_id);
    }

    /**
     * Szerezze be az aldomainhez társított aldomain állapotát
     *
     * @return BelongsTo
     */
    public function subdomainState(): BelongsTo
    {
        return $this->belongsTo(SubdomainState::class, 'state_id');
    }

    #[Override]
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logFillable()
            ->logAll();
    }
}
