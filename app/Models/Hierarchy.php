<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hierarchy extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $table = 'entity_rel';

    protected $fillable = [
        'parent_id',
        'child_id',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'parent_id');
    }

    public function child(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'child_id');
    }
}
