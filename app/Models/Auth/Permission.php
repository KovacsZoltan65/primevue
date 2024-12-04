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
    
    protected static $logAttributes = [
        'name', 'guard_name'
    ];
    
    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];
    
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
