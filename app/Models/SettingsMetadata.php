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

    protected $table = 'settings_metadata';
    protected $fillable = ['key', 'label', 'description', 'type', 'level', 'is_required', 'default_value', 'validation_rules'];

    protected $logAttributes = ['key', 'label', 'description', 'type', 'level', 'is_required', 'default_value', 'validation_rules'];

    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];

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
