<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use App\Http\Resources\Auth\UserResource;
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
    
    /**
     * Visszaadja a felhasználókat a keresési feltételek alapján.
     *
     * A keresési feltétel a nevben kereshető.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getUsers(Request $request): AnonymousResourceCollection
    {
        // A keresési feltétel alkalmazása a lekérdezéshez
        $userQuery = User::search($request);
        
        // A lekérdezés eredményének átalakítása erőforrásgyűjteménnyé
        $users = UserResource::collection($userQuery->get());
        
        // Visszaadás
        return $users;
    }
    
    public function getUser(Request $request)
    {
\Log::info('$user: ' . print_r($request->all(), true));
        $user = User::find($request->get('id'));
//\Log::info('$user: ' . print_r($user, true));
        return $user;
    }
    
    /**
     * Létrehoz egy új felhasználót az adatbázisban.
     *
     * A létrehozott felhasználó adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  Request  $request  A HTTP kérés objektum, amely tartalmazza a felhasználó új adatait.
     * @return \Illuminate\Http\JsonResponse  A létrehozott felhasználó adatait tartalmazó JSON-válasz.
     */
    public function createUser(Request $request): JsonResponse
    {
        // Létrehoz egy új felhasználót az adatbázisban
        $user = User::create($request->all());
        
        // A létrehozott felhasználó adatait tartalmazó JSON-válasz visszaadása
        return response()->json($user, Response::HTTP_OK);
    }
    
    /**
     * Frissít egy meglévő felhasználót az adatbázisban.
     *
     * A frissített felhasználó adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  Request  $request  A HTTP kérés objektum, amely tartalmazza a felhasználó új adatait.
     * @param  int  $id  A frissítendő felhasználó azonosítója.
     * @return \Illuminate\Http\JsonResponse  A frissített felhasználó adatait tartalmazó JSON-válasz.
     */
    public function updateUser(Request $request, int $id): JsonResponse
    {
        // Keresse meg a frissítendő felhasználót az azonosítója alapján
        $old_user = User::find( $id);
        
        // Frissítse a felhasználót a HTTP-kérés adataival
        $user = $old_user->update($request->all());
        
        // A frissített felhasználó adatait tartalmazó JSON-válasz visszaadása
        return response()->json($user, Response::HTTP_OK);
    }

    /**
     * Frissít egy felhasználó jelszavát az adatbázisban.
     *
     * A frissített felhasználó adatait tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  Request  $request  A HTTP kérés objektum, amely tartalmazza a felhasználó új jelszavát.
     * @param  int  $id  A frissítendő felhasználó azonosítója.
     * @return \Illuminate\Http\JsonResponse  A frissített felhasználó adatait tartalmazó JSON-válasz.
     */
    public function updatePassword(Request $request, int $id): JsonResponse
    {
        // Keresse meg a frissítendő felhasználót az azonosítója alapján
        $user = User::find( $id);

        // Frissítse a felhasználó jelszavát a HTTP-kérés adataival
        $user->update([
            'password' => bcrypt($request->password),
        ]);

        // A frissített felhasználó adatait tartalmazó JSON-válasz visszaadása
        return response()->json($user, Response::HTTP_OK);
    }
    
    /**
     * Törli a megadott azonosítójú felhasználót az adatbázisból.
     *
     * A törlés eredményét tartalmazó JSON-válasz kerül visszaadásra.
     *
     * @param  int  $id  A törlendő felhasználó azonosítója.
     * @return \Illuminate\Http\JsonResponse  A törlés eredményét tartalmazó JSON-válasz.
     */
    public function deleteUser(int $id): JsonResponse
    {
        // Keresse meg a törlendő felhasználót az azonosítója alapján
        $old_user = User::where('id', $id)->first();
        
        // Törli a felhasználót az adatbázisból
        $old_user->delete();
        
        // A törlés eredményét tartalmazó JSON-válasz visszaadása
        return response()->json($old_user, Response::HTTP_OK);
    }
    
    public function assignRoleToUser(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'role' => 'required|string|exists:role,name'
        ]);
        
        $user->assignRole($validated['role']);
        
        return response()->json(['user' => $user], Response::HTTP_OK);
    }
    
    public function assignPermissionToUser(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'role' => 'required|string|exists:permissions,name'
        ]);
        
        //$user->assignP
    }
}
