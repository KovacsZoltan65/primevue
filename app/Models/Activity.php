<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity as SpatieActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class Activity extends SpatieActivity
{
    protected static $logName = 'activities';
    
    public static function getTag(): string
    {
        return self::$logName;
    }
    
    public function scopeSearch(Builder $query, Request $request): Builder
    {
        return $query->when($request->search, function($query) use($request) {
            $query->where(function ($query) use ($request) {
                $query->where('log_name', 'like', "%{$request->search}%");
            });
        });
    }
}
