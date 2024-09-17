<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Auth/Permission/Index');
    }
    
    public function applySearch(Builder $query, string $search)
    {
        //
    }
}
