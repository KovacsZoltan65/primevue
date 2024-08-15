<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Company extends Model
{
    use HasFactory,
        SoftDeletes;
    
    protected $fillable = ['name', 'country', 'city'];

    public function scopeSearch(Builder $query, Request $request)
    {
        // If search parameter is present, filter results by name or email containing the search term
        return $query->when($request->search, function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            });
        })
        // If class_id parameter is present, filter results by that class_id
        ->when($request->country, function ($query) use ($request) {
            $query->where('country', $request->country);
        })
        // If section_id parameter is present, filter results by that section_id
        ->when($request->city, function ($query) use ($request) {
            $query->where('city', $request->city);
        });
    }
}
