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
    
    public function getPermission(): JsonResponse
    {
        //
    }
    
    public function getPermissionByName(string $name): JsonResponse
    {
        //
    }
    
    public function createPermission(): JsonResponse
    {
        //
    }
    
    public function updatePermission(): JsonResponse
    {
        //
    }
    
    public function deletePermission(): JsonResponse
    {
        //
    }
    
    public function deletePermissions(): JsonResponse
    {
        //
    }
    
    public function restorePermission(): JsonResponse
    {
        //
    }
}
