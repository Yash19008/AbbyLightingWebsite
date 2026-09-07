<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DecorativeAttribute;
use DataTables;
use Illuminate\Support\Str;

class DecorativeAttributeController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->main_module = 'Decorative Product';
    }

    public function index()
    {
        $data = [
            'title' => "Decorative Attributes",
            'main_module' => $this->main_module
        ];
        return view('admin.decorative-attributes.index', $data);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = DecorativeAttribute::withCount('values')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('slug', function ($row) {
                    return $row->slug;
                })
                ->addColumn('values_count', function ($row) {
                    return '<span class="badge badge-info">' . $row->values_count . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $actions = '<div class="text-center list-action actBtn-td">
                        <a href="' . route('decorative_attribute_admin.edit', $row->id) . '" class="mx-1" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3 mr-2"></i></a>
                        <a href="' . route('decorative_attribute_admin.delete', $row->id) . '" onclick="return confirm(\'Are you sure you want to delete this attribute? All its values will be deleted as well.\')" class="mx-1" data-toggle="tooltip" title="Delete"><i class="ft-trash-2 font-medium-3 text-danger"></i></a>
                    </div>';
                    return $actions;
                })
                ->rawColumns(['values_count', 'action'])
                ->make(true);
        }
    }

    public function add()
    {
        $data = [
            'title' => "Add Decorative Attribute",
            'main_module' => $this->main_module,
            'method' => 'Add',
            'action' => route('decorative_attribute_admin.insert')
        ];
        return view('admin.decorative-attributes.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $slug = Str::slug($request->name);
        $count = DecorativeAttribute::withTrashed()->where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . uniqid();
        }

        $attribute = DecorativeAttribute::create([
            'name' => $request->name,
            'slug' => $slug
        ]);

        $this->saveValues($request, $attribute);

        return redirect()->route('decorative_attribute_admin')->with('success', 'Attribute added successfully.');
    }

    public function edit($id)
    {
        $attribute = DecorativeAttribute::with('values')->findOrFail($id);
        $data = [
            'title' => "Edit Decorative Attribute",
            'main_module' => $this->main_module,
            'method' => 'Edit',
            'action' => route('decorative_attribute_admin.update', $id),
            'attribute' => $attribute
        ];
        return view('admin.decorative-attributes.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $attribute = DecorativeAttribute::findOrFail($id);
        
        $slug = Str::slug($request->name);
        $count = DecorativeAttribute::withTrashed()->where('slug', $slug)->where('id', '!=', $id)->count();
        if ($count > 0) {
            $slug = $slug . '-' . uniqid();
        }

        $attribute->update([
            'name' => $request->name,
            'slug' => $slug
        ]);

        $this->saveValues($request, $attribute);

        return redirect()->route('decorative_attribute_admin')->with('success', 'Attribute updated successfully.');
    }

    public function delete($id)
    {
        $attribute = DecorativeAttribute::findOrFail($id);
        $attribute->values()->delete();
        $attribute->delete();

        return redirect()->route('decorative_attribute_admin')->with('success', 'Attribute deleted successfully.');
    }

    private function saveValues(Request $request, DecorativeAttribute $attribute)
    {
        $submittedIds = [];

        if ($request->has('values') && is_array($request->values)) {
            foreach ($request->values as $val) {
                if (!isset($val['name']) || empty($val['name'])) {
                    continue;
                }

                $hexCode = isset($val['hex_code']) && $val['hex_code'] !== '' ? $val['hex_code'] : null;

                // Existing value — update by ID
                if (!empty($val['id'])) {
                    $value = \App\Models\DecorativeAttributeValue::withTrashed()
                        ->where('id', $val['id'])
                        ->where('attribute_id', $attribute->id)
                        ->first();

                    if ($value) {
                        if ($value->trashed()) {
                            $value->restore();
                        }
                        $value->update([
                            'name'     => $val['name'],
                            'slug'     => Str::slug($val['name']),
                            'hex_code' => $hexCode,
                        ]);
                        $submittedIds[] = $value->id;
                        continue;
                    }
                }

                // New value — create it
                $slug = Str::slug($val['name']);
                // Avoid slug collision within same attribute
                $existingSlug = \App\Models\DecorativeAttributeValue::withTrashed()
                    ->where('attribute_id', $attribute->id)
                    ->where('slug', $slug)
                    ->first();
                if ($existingSlug) {
                    $slug = $slug . '-' . uniqid();
                }

                $newVal = $attribute->values()->create([
                    'name'     => $val['name'],
                    'slug'     => $slug,
                    'hex_code' => $hexCode,
                ]);
                $submittedIds[] = $newVal->id;
            }
        }

        // Delete any values not in this submission
        $attribute->values()->whereNotIn('id', $submittedIds)->delete();
    }
}
