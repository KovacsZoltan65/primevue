<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity as SpatieActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class Activity extends SpatieActivity
{
    use HasFactory,
        SoftDeletes,
        LogsActivity;
    
    protected $table = 'activity_log';
    protected $fillable = [
        'log_name','description','subject_type','event','subject_id',
        'causer_type','causer_id','properties','batch_uuid','occurrence_count',
    ];
    
    /*
     * ==============================================================
     * LOGOLÁS
     * ==============================================================
     */

    // Ha szeretnéd, hogy minden mezőt automatikusan naplózzon:
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true; // Csak a változásokat naplózza
    protected static $logName = 'activities';

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
        //
    }
    
    #[Override]
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
}
