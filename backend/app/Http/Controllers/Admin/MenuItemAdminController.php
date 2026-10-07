<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuItemAdminController extends Controller
{
    private $locations = [
        'header_mega' => 'Header Mega Menu',
        'footer_col_1' => 'Footer: Column 1',
        'footer_col_2' => 'Footer: Column 2',
        'footer_col_3' => 'Footer: Column 3',
    ];

    public function index()
    {
        $allItems = \App\Models\MenuItem::with('children')->whereNull('parent_id')->ordered()->get();
        $groupedItems = $allItems->groupBy('location');
        
        return view('admin.menu-items.index', [
            'groupedItems' => $groupedItems, 
            'locations' => $this->locations
        ]);
    }

    public function create()
    {
        return view('admin.menu-items.create', ['locations' => $this->locations]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'location' => 'required|string',
            'order' => 'required|integer',
            'is_active' => 'boolean',
            'type' => 'required|string|in:link,group',
            'parent_id' => 'nullable|exists:menu_items,id'
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['parent_id'] = !empty($data['parent_id']) ? (int)$data['parent_id'] : null;
        \App\Models\MenuItem::create($data);

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu Item created successfully');
    }

    public function edit(string $id)
    {
        $item = \App\Models\MenuItem::findOrFail($id);
        return view('admin.menu-items.edit', ['item' => $item, 'locations' => $this->locations]);
    }

    public function update(Request $request, string $id)
    {
        $item = \App\Models\MenuItem::findOrFail($id);
        
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'location' => 'required|string',
            'order' => 'required|integer',
            'is_active' => 'boolean',
            'type' => 'required|string|in:link,group',
            'parent_id' => 'nullable|exists:menu_items,id'
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['parent_id'] = !empty($data['parent_id']) ? (int)$data['parent_id'] : null;
        $item->update($data);

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu Item updated successfully');
    }

    public function destroy(string $id)
    {
        $item = \App\Models\MenuItem::findOrFail($id);
        $item->delete();
        return redirect()->route('admin.menu-items.index')->with('success', 'Menu Item deleted successfully');
    }

    public function reorder(Request $request)
    {
        $orders = $request->input('orders', []);
        
        foreach ($orders as $orderData) {
            \App\Models\MenuItem::where('id', $orderData['id'])->update([
                'order' => $orderData['order'],
                'parent_id' => $orderData['parent_id'] ?? null
            ]);
        }

        return response()->json(['success' => true]);
    }
}
