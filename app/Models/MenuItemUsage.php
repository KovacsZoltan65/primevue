<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemUsage extends Model
{
    use HasFactory;
    
    protected $fillable = ['menu_item_id', 'user_id', 'usage_count'];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
