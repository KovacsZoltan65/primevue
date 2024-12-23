<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\Activitylog\LogOptions;
use Override;
use Spatie\Activitylog\Traits\LogsActivity;

class ApplicationSetting extends Model
{
    use HasFactory,
        LogsActivity;

    protected $table = 'application_settings';
    protected $fillable = ['key', 'value', 'active'];

    /*
     * ==============================================================
     * LOGOLÁS
     * ==============================================================
     */
    // Ha szeretnéd, hogy minden mezőt automatikusan naplózzon:
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true; // Csak a változásokat naplózza
    protected static $logName = 'application_settings';

    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];

    /*
     * ==============================================================
     */

    public function scopeSearch(Builder $query, Request $request): Builder {
        return $query->when($request->search, function (Builder $query) use ($request) {
            return $query->where(function (Builder $query) use ($request) {
                $query->where('key', 'like', "%{$request->search}%");
            });
        });

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
