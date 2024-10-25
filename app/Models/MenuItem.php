<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    
    protected $fillable = ['title', 'icon', 'url', 'default_weight', 'parent_id'];
    
    public function children()
    {
        //return $this->hasMany(MenuItem::class, 'parent_id');
        return $this->hasMany(MenuItem::class, 'parent_id')->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function usages()
    {
        return $this->hasMany(MenuItemUsage::class);
    }
}
