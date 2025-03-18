<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Spatie\Activitylog\Traits\LogsActivity;

class EntityCalendar extends Model
{
    use HasFactory, 
        SoftDeletes, 
        LogsActivity;
    
    protected $table = 'entity_calendar';
    protected $fillable = ['entity_id', 'calendar_id'];
    
    /*
     * ==============================================================
     * LOGOLÁS
     * ==============================================================
     */

    // Ha szeretnéd, hogy minden mezőt automatikusan naplózzon:
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true; // Csak a változásokat naplózza
    protected static $logName = 'entity_calendars';

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
        return $query->when($request->search, function($query) use($request) {
            $query->where(function($query) use($request) {
                $query->where('date', '=', $request->search);
            });
        });
    }
    
    /**
     * Kapcsolat az `Entity` modellel
     */
    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }
    
    /**
     * Kapcsolat a `Calendar` modellel
     */
    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }
    
    /**
     * Ellenőrzi, hogy egy entitás szerepel-e egy adott naptári napon
     */
    public static function existsForEntityOnDate($entityId, $date)
    {
        return self::whereHas('calendar', function ($query) use ($date) {
                    $query->where('date', $date);
                })->where('entity_id', $entityId)->exists();
    }
    
    /**
     * Egy entitás hozzárendelése egy adott dátumhoz
     */
    public static function assignEntityToDate($entityId, $date)
    {
        $calendar = Calendar::firstOrCreateByDate($date);

        return self::firstOrCreate([
            'entity_id' => $entityId,
            'calendar_id' => $calendar->id
        ]);
    }
    
    /**
     * Egy entitás eltávolítása egy adott naptári napról
     */
    public static function removeEntityFromDate($entityId, $date)
    {
        return self::whereHas('calendar', function ($query) use ($date) {
                    $query->where('date', $date);
                })->where('entity_id', $entityId)->delete();
    }
    
    #[\Override]
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
    
}
