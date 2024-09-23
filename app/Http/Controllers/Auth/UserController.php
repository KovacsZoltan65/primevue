<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use App\Http\Resources\UserResource;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * A felhasználókezelési oldal megjelenítése.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request): InertiaResponse
    {
        // Jelenítse meg a felhasználókezelő oldalt
        return Inertia::render('Auth/User/Index');
    }
    
    /**
     * A felhasználók lekérdezésénél alkalmazza a keresési feltételt.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function ($query, string $search) {
            // A névben keressük a keresési feltételt
            $query->where('name', 'like', "%{$search}%");
        });
    }
    
    public function getUsers(Request $request)
    {
        $userQuery = User::search($request);
        
        $users = UserResource::collection($userQuery->get());
        
        return $users;
    }
    
    public function createUser(Request $request)
    {
        $user = User::create($request->all());
        
        return response()->json($user, Response::HTTP_OK);
    }
    
    public function updateUser(Request $request, int $id)
    {
        $old_user = User::where('id', $id)->first();
        
        $user = $old_user->update($request->all());
        
        return response()->json($user, Response::HTTP_OK);
    }
    
    public function deleteUser(int $id)
    {
        $old_user = User::where('id', $id)->first();
        
        $old_user->delete();
        
        return response()->json($old_user, Response::HTTP_OK);
    }
}
