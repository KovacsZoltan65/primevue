<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItemUsage extends Model
{
    use HasFactory;
    
    protected $table = 'menu_item_usages';
    protected $fillable = ['menu_item_id', 'user_id', 'usage_count'];

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
