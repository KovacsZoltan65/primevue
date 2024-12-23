<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class MenuItemUsage extends Model
{
    use HasFactory,
        LogsActivity;

    protected $table = 'menu_item_usages';
    protected $fillable = ['menu_item_id', 'user_id', 'usage_count'];

    /*
     * ==============================================================
     * LOGOLÁS
     * ==============================================================
     */
    // Ha szeretnéd, hogy minden mezőt automatikusan naplózzon:
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true; // Csak a változásokat naplózza
    protected static $logName = 'menu_item_usage';

    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];

    /*
     * ==============================================================
     */

    /**
     * Szerezze be a rekordhoz tartozó menüpontot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }
}
