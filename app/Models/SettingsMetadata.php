<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Client\Request;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Override;

class SettingsMetadata extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'setting_metadata';
    protected $fillable = ['key', 'type', 'description', 'default_value', 'scope'];

    /*
     * ==============================================================
     * LOGOL�S
     * ==============================================================
     */

    // Ha szeretn�d, hogy minden mez�t automatikusan napl�zzon:
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true; // Csak a v�ltoz�sokat napl�zza
    protected static $logName = 'settingMetadata';

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

    public function scopeActive(Builder $query, Request $request): Builder
    {
        return $query->where('active', 1);
    }

    #[Override]
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logFillable()
            ->logAll();
    }
}
