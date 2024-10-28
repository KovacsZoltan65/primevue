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
        $menuItems = MenuItem::with('children')->whereNull('parent_id')->get();
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
            'icon' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'default_weight' => 'nullable|integer',
            'parent_id' => 'nullable|exists:menu_items,id'
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
        return response()->noContent();
    }
    
    public function getMenu()
    {
        $menuItems = MenuItem::whereNull('parent_id')->with('children')->get();
        
        return response()->json($menuItems);
    }
    
    public function getSortedMenuItems()
    {
        $menuItems = MenuItem::whereNull('parent_id')
            ->with(['children'])
            ->orderBy('default_weight', 'asc')
            ->get();
        
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
