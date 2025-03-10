<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Permission extends Model
{
    use HasFactory,
        LogsActivity;
    
    protected $table = 'permissions';
    
    protected $fillable = [
        'name', 'guard_name'
    ];
    
    /*
     * ==============================================================
     * LOGOLÁS
     * ==============================================================
     */
    
    // Ha szeretnéd, hogy minden mezőt automatikusan naplózzon:
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true; // Csak a változásokat naplózza
    protected static $logName = 'permissions';
    
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
        return $query->when($request->search, function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            });
        });
    }
    
    #[Override]
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logFillable()
            ->logAll();
    }
}
