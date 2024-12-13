<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Hierarchy extends Model
{
    use HasFactory,
        SoftDeletes,
        LogsActivity;

    protected $table = 'entity_rel';

    protected $fillable = [
        'parent_id',
        'child_id',
    ];

    // Ha szeretnéd, hogy minden mezőt automatikusan naplózzon:
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true; // Csak a változásokat naplózza
    protected static $logName = 'hierarchy';
    
    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'parent_id');
    }

    public function child(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'child_id');
    }

    #[Override]
    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logFillable();
    }
}
