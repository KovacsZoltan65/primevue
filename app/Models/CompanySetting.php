<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Override;
use Illuminate\Http\Request;

class CompanySetting extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'company_settings';
    protected $fillable = ['company_id', 'key', 'value', 'is_active'];

    protected $logAttributes = ['company_id', 'key', 'value', 'is_active'];

    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];

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
    
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    #[Override]
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logFillable()
            ->logAll();
    }
}
