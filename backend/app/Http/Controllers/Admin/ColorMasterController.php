<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ColorMaster;
use App\Helpers\Common_function;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ColorMasterController extends Controller
{
    /**
     * Display a listing of color masters.
     */
    public function index()
    {
        $colors = ColorMaster::ordered()->get();
        $title = "Color Masters";
        $main_module = 'Color Masters';
        $tbl = \App\Helpers\Common_function::encrypt('color_masters');
        return view('admin.color-masters.index', compact('colors', 'title', 'main_module', 'tbl'));
    }

    /**
     * Show the form for creating a new color.
     */
    public function add()
    {
        return view('admin.color-masters.add');
    }

    /**
     * Store a newly created color in storage.
     */
    public function insert(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:color_masters,code',
            'type' => 'required|in:solid,gradient',
            'hex_code' => 'nullable|required_if:type,solid|string|max:7',
            'gradient_start' => 'nullable|required_if:type,gradient|string|max:7',
            'gradient_end' => 'nullable|required_if:type,gradient|string|max:7',
            'gradient_type' => 'nullable|in:linear,radial',
            'gradient_direction' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Auto-generate code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = Str::slug($validated['name']);
        }

        // Set default gradient values if gradient type
        if ($validated['type'] === 'gradient') {
            $validated['gradient_type'] = $validated['gradient_type'] ?? 'linear';
            $validated['gradient_direction'] = $validated['gradient_direction'] ?? 'to right';
            $validated['hex_code'] = null; // Clear hex code for gradients
        } else {
            // Clear gradient fields for solid colors
            $validated['gradient_start'] = null;
            $validated['gradient_end'] = null;
            $validated['gradient_type'] = 'linear';
            $validated['gradient_direction'] = 'to right';
        }

        $validated['is_active'] = $request->has('is_active');

        ColorMaster::create($validated);

        return redirect()
            ->route('color_master_admin')
            ->with('success', 'Color created successfully!');
    }

    /**
     * Show the form for editing the specified color.
     */
    public function edit($id)
    {
        $color = ColorMaster::findOrFail($id);
        return view('admin.color-masters.edit', compact('color'));
    }

    /**
     * Update the specified color in storage.
     */
    public function update(Request $request, $id)
    {
        $color = ColorMaster::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:color_masters,code,' . $id,
            'type' => 'required|in:solid,gradient',
            'hex_code' => 'nullable|required_if:type,solid|string|max:7',
            'gradient_start' => 'nullable|required_if:type,gradient|string|max:7',
            'gradient_end' => 'nullable|required_if:type,gradient|string|max:7',
            'gradient_type' => 'nullable|in:linear,radial',
            'gradient_direction' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Set default gradient values if gradient type
        if ($validated['type'] === 'gradient') {
            $validated['gradient_type'] = $validated['gradient_type'] ?? 'linear';
            $validated['gradient_direction'] = $validated['gradient_direction'] ?? 'to right';
            $validated['hex_code'] = null; // Clear hex code for gradients
        } else {
            // Clear gradient fields for solid colors
            $validated['gradient_start'] = null;
            $validated['gradient_end'] = null;
            $validated['gradient_type'] = 'linear';
            $validated['gradient_direction'] = 'to right';
        }

        $validated['is_active'] = $request->has('is_active');

        $color->update($validated);

        return redirect()
            ->route('color_master_admin')
            ->with('success', 'Color updated successfully!');
    }

    /**
     * Remove the specified color from storage.
     */
    public function delete($id)
    {
        $color = ColorMaster::findOrFail($id);
        $color->delete();

        return redirect()
            ->route('color_master_admin')
            ->with('success', 'Color deleted successfully!');
    }

    /**
     * Display information about the specified color.
     */
    public function information($id)
    {
        $color = ColorMaster::findOrFail($id);
        return view('admin.color-masters.information', compact('color'));
    }
}
