<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Entity extends Model
{
    use HasFactory,
        SoftDeletes;
    
    protected $table = 'entities';
    protected $fillable = ['name', 'email', 'persons_id', 'companies_id', 'active'];
    protected $attributes = [
        'name' => '', 
        'email' => '', 
        'persons_id' => 0, 
        'companies_id' => 0, 
        'active' => 1
    ];
    
    public function scopeSerach(Builder $query, Request $request)
    {
        return $query->when($request->search, function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                });
            })->where('active', APP_ACTIVE);
    }
}
