<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $users = \App\Models\User::orderBy('name')->get()->toArray();
        $permissions = Permission::orderBy('name')->get()->toArray();
        
        return Inertia::render('Auth/Role/Index', [
            'users' => $users,
            'permissions' => $permissions,
            'search' => request('search'),
        ]);
    }
    
    public function applySearch(Builder $query, string $search)
    {
        //
    }
}
