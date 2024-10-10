<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Person extends Model
{
    use HasFactory,
        SoftDeletes;
    
    protected $table = 'persons';
    protected $fillable = ['name', 'email', 'password', 'language', 'birthdate', 'active'];
    protected $attributes = [
        'name' => '', 
        'email' => '', 
        'password' => '', 
        'language' => 'hu', 
        'birthdate' => '', 
        'active' => 1
    ];
    
    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($request->search, function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                });
            })->where('active', APP_ACTIVE);
    }
}
