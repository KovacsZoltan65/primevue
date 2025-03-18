<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Calendar extends Model
{
    use HasFactory,
        SoftDeletes,
        LogsActivity;

    protected $table = 'calendars';

    protected $fillable = ['date'];

    protected $casts = [
        'date' => 'date',
    ];

    /*
     * ==============================================================
     * LOGOLÁS
     * ==============================================================
     */

    // Ha szeretnéd, hogy minden mezőt automatikusan naplózzon:
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true; // Csak a változásokat naplózza
    protected static $logName = 'calendars';

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
                $query->where('date', '=', $request->search);
            });
        });
    }

    /**
     * Kapcsolat az EntityCalendar modellel (sok entity tartozhat egy naptárnaphoz)
     */
    public function entityCalendars()
    {
        return $this->hasMany(EntityCalendar::class);
    }
    
    /**
     * Azon entitások lekérése, akik ezen a napon szerepelnek
     */
    public function entities()
    {
        return $this->belongsToMany(Entity::class, 'entity_calendar')->withTimestamps();
    }
    
    /**
     * Naptári nap létrehozása vagy lekérése egy adott dátum alapján
     */
    public static function firstOrCreateByDate($date)
    {
        return self::firstOrCreate(['date' => $date]);
    }
    
    #[\Override]
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
}
