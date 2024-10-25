<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\MenuItemUsage;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // Listázás adminisztrációs nézethez
    public function index()
    {
        $menuItems = MenuItem::all();
        return response()->json($menuItems);
    }

    // Egy menüpont megtekintése
    public function show(MenuItem $menuItem)
    {
        return response()->json($menuItem->load('children'));
    }
    
    // Új menüpont létrehozása
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'default_weight' => 'required|integer',
        ]);

        $menuItem = MenuItem::create($validated);
        return response()->json($menuItem, 201);
    }
    
    // Menüpont frissítése
    public function update(Request $request, MenuItem $menuItem)
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
    
    // Menüpont törlése
    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();
        return response()->json(null, 204);
    }
    
    public function getMenu()
    {
        $menuItems = MenuItem::whereNull('parent_id')->with('children')->get();
        
        return response()->json($menuItems);
    }
    
    public function getSortedMenuItems()
    {
\DB::enableQueryLog(); // Enable query log
        // Lekéri a menüpontokat, és azok hierarchiáját rendezett sorrendben
        $menuItems = MenuItem::whereNull('parent_id')
            ->with(['children'])
            ->orderBy('default_weight', 'asc')
            ->get();
        /*
        $menuItems = MenuItem::with(['children' => function ($query) {
                $query->withCount(['usages as total_usage' => function($query) {
                    $query->where('user_id', auth()->id());
                }])->orderByDesc('total_usage')
                  ->orderBy('default_weight');
            }])
            ->whereNull('parent_id') // Csak a főmenüpontokat kérdezi le, amelyeknek nincs szülőjük
            ->withCount(['usages as total_usage' => function($query) {
                $query->where('user_id', auth()->id());
            }])
            ->orderByDesc('total_usage')
            ->orderBy('default_weight')
            ->get();
        */
\Log::info( print_r(\DB::getQueryLog(), true)); // Show results of log
\DB::disableQueryLog();
\Log::info( print_r( json_encode($menuItems) , true) );

        //return $menuItems;
        return response()->json($menuItems);
    }
    
    public function updateMenuUsage($menuItemId)
    {
        $usage = MenuItemUsage::firstOrCreate([
            'menu_item_id' => $menuItemId,
            'user_id' => auth()->id()
        ]);

        $usage->increment('usage_count');
    }
    
    public function updateUsage(Request $request)
    {
        $menuItemId = $request->get('menu_item_id');
        $this->updateMenuUsage($menuItemId);

        return response()->json(['message' => 'Usage updated successfully.']);
    }

}
