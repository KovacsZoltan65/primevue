<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('name')->get()->toArray();
        $permissions = Permission::orderBy('name')->get()->toArray();
        
        return Inertia::render('Auth/Role/Index', [
            'users' => $users,
            'permissions' => $permissions,
            'search' => request('search'),
        ]);
    }
    
    public function applySearch(Builder $query, string $search)
    {
        return $query->when($search, function($query, string $search) {
            // A lekérdezéshez hozzáadja a keresési feltételt
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }
    
    public function getRoles(Request $request)
    {
        $roleQuery = Role::search($request);
        $roles = RoleResource::collection($roleQuery->get());
        
        return $roles;
    }
    
    public function createRole(Request $request)
    {
        $role = Role::create($request->all());
        
        return response()->json($role, Response::HTTP_OK);
    }
    
    public function updateRole(Request $request, int $id)
    {
        $old_role = Role::find($id);
        $success = $old_role->update($request->all());
        
        return response()->json(['success' => $success], Response::HTTP_OK);
    }
    
    public function deleteRole(int $id)
    {
        $old_role = Role::find($id);
        $success = $old_role->delete();
        
        return response()->json(['success' => $success], Response::HTTP_OK);
    }
}
