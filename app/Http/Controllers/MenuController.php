<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\MenuItemUsage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    /**
     * Megjeleníti a legfelső szintű menüelemek listáját.
     *
     * Ez a módszer lekéri az összes olyan menüelemet, amelynek nincs szülője,
     * gyermekeikkel együtt, és visszaküldi őket JSON-válaszként.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $menuItems = MenuItem::with('children')->whereNull('parent_id')->get();
        return response()->json($menuItems);
    }

    /**
     * Egy menüpont megjelenítése.
     *
     * Egyetlen menüpontot jelenít meg, a gyermekeivel együtt.
     *
     * @param \App\Models\MenuItem $menuItem A megjelenítendő menüpont.
     * @return \Illuminate\Http\JsonResponse A menüpont JSON-válasza.
     */
    public function show(MenuItem $menuItem): JsonResponse
    {
        return response()->json($menuItem->load('children'));
    }
    
    /**
     * Létrehoz egy új menüpontot.
     *
     * A menüpontot a $request-ben kapott adatokkal hozza létre, és a létrehozott
     * menüpontot visszaküldi JSON-válaszként.
     *
     * @param \Illuminate\Http\Request $request A menüpont adatait tartalmazó HTTP kérési objektum.
     * @return \Illuminate\Http\JsonResponse A létrehozott menüpont JSON-válasza.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'default_weight' => 'nullable|integer',
            'parent_id' => 'nullable|exists:menu_items,id'
        ]);

        $menuItem = MenuItem::create($validated);
        return response()->json($menuItem, 201);
    }
    
    /**
     * Frissít egy meglévő menüpontot.
     *
     * A menüpontot a $request-ben kapott adatokkal frissíti, és a frissített
     * menüpontot visszaküldi JSON-válaszként.
     *
     * @param \Illuminate\Http\Request $request A menüpont adatait tartalmazó HTTP kérési objektum.
     * @param \App\Models\MenuItem $menuItem A frissítendő menüpont.
     * @return \Illuminate\Http\JsonResponse A frissített menüpont JSON-válasza.
     */
    public function update(Request $request, MenuItem $menuItem): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'default_weight' => 'sometimes|required|integer',
        ]);

        $menuItem->update($validated);
        return response()->json($menuItem);
    }
    
    /**
     * Törli a megadott menüpontot.
     *
     * Törli a megadott menüpontot, és a HTTP válaszban a 204-es státuszkódot
     * adja vissza, jelezve, hogy a művelet sikeres volt.
     *
     * @param \App\Models\MenuItem $menuItem A törlendő menüpont.
     * @return \Illuminate\Http\Response A HTTP válasz.
     */
    public function destroy(MenuItem $menuItem): Response
    {
        $menuItem->delete();
        return response()->noContent();
    }
    
    /**
     * A legfelső szint menüpontjainak lekérese.
     *
     * Egy JSON-válaszban visszaküldi a legfelső szint menüpontjait, gyermekeikkel együtt.
     *
     * @return \Illuminate\Http\JsonResponse A menüpontok JSON-válasza.
     */
    public function getMenu(): JsonResponse
    {
        $menuItems = MenuItem::whereNull('parent_id')->with('children')->get();
        
        return response()->json($menuItems);
    }
    
    /**
     * A legfelső szint menüpontjainak lekérese, a default_weight mező alapján rendezve.
     *
     * Egy JSON-válaszban visszaküldi a legfelső szint menüpontjait, gyermekeikkel együtt.
     * A menüpontokat a default_weight mező alapján rendezze.
     *
     * @return \Illuminate\Http\JsonResponse A menüpontok JSON-válasza.
     */
    public function getSortedMenuItems(): JsonResponse
    {
        $menuItems = MenuItem::whereNull('parent_id')
            ->with(['children'])
            ->orderBy('default_weight', 'asc')
            ->get();
        
        return response()->json($menuItems);
    }
    
    /**
     * A menüpont használatának számlálása.
     *
     * Ha a felhasználó egy menüpontot használ, akkor a menüpont használatát számlálja.
     *
     * @param int $menuItemId A menüpont azonosítója.
     * @return void
     */
    public function updateMenuUsage($menuItemId): void
    {
        $usage = MenuItemUsage::firstOrCreate([
            'menu_item_id' => $menuItemId,
            'user_id' => auth()->id()
        ]);

        $usage->increment('usage_count');
    }
    
    /**
     * Frissíti a menüpont használatát.
     *
     * Egy HTTP kérésben megadott menüpont azonosító alapján frissíti a menüpont
     * használatát, és egy JSON-válaszban visszaadja a művelet sikerességéről
     * szóló üzenetet.
     *
     * @param \Illuminate\Http\Request $request A HTTP kérés objektum.
     * @return \Illuminate\Http\JsonResponse A JSON-válasz.
     */
    public function updateUsage(Request $request): JsonResponse
    {
        $menuItemId = $request->get('menu_item_id');
        $this->updateMenuUsage($menuItemId);

        return response()->json(['message' => 'Usage updated successfully.']);
    }

}
