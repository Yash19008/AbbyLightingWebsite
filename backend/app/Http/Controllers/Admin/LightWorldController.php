<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\LightWorld;
use App\Helpers\Common_function;
use Carbon\Carbon;
use Storage;

class LightWorldController extends Controller
{
    protected $main_module;
    protected $currentDateTime;

    public function __construct()
    {
        $this->main_module = 'Settings';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }

    public function index(Request $request)
    {
        $data = array('title' => "Worlds of Light", 'main_module' => $this->main_module);
        $data['search'] = $request->input('search');
        $data['results'] = new LightWorld;

        if ($data['search'] != '') {
            $data['results'] = $data['results']->where('name', 'LIKE', '%' . $data['search'] . '%');
        }

        $data['tbl'] = Common_function::encrypt('light_worlds');
        $data['results'] = $data['results']->orderBy('sort_order', 'ASC')->get();

        return view('admin.light_worlds.index', $data);
    }

    public function add()
    {
        $data = array(
            'title' => "Add World of Light",
            'main_module' => $this->main_module,
            'method' => 'Add',
            'action' => route('light_worlds_admin.insert'),
            'frn_id' => 'light_world_form'
        );
        return view('admin.light_worlds.edit', $data);
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
            'light_of_image' => 'nullable|image|max:4096',
            'light_on_image' => 'nullable|image|max:4096',
            'sort_order' => 'required|integer',
        ]);

        $data = [
            'name' => $request->name,
            'link' => $request->link,
            'sort_order' => $request->sort_order,
            'created_at' => $this->currentDateTime,
            'updated_at' => $this->currentDateTime,
        ];

        if ($request->hasFile('light_of_image')) {
            $data['light_of_image'] = $request->light_of_image->store('/uploads/light_worlds', 'public');
        }

        if ($request->hasFile('light_on_image')) {
            $data['light_on_image'] = $request->light_on_image->store('/uploads/light_worlds', 'public');
        }

        LightWorld::create($data);

        return redirect()->route('light_worlds_admin')->withSuccess('World of light has been added successfully.');
    }

    public function edit($id)
    {
        $data = array(
            'title' => "Edit World of Light",
            'main_module' => $this->main_module,
            'method' => 'Edit',
            'action' => route('light_worlds_admin.update', $id),
            'frn_id' => 'light_world_form_edit'
        );
        $data['world'] = LightWorld::findOrFail($id);
        return view('admin.light_worlds.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $world = LightWorld::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
            'light_of_image' => 'nullable|image|max:4096',
            'light_on_image' => 'nullable|image|max:4096',
            'sort_order' => 'required|integer',
        ]);

        $data = [
            'name' => $request->name,
            'link' => $request->link,
            'sort_order' => $request->sort_order,
            'updated_at' => $this->currentDateTime,
        ];

        if ($request->hasFile('light_of_image')) {
            if ($world->light_of_image && !str_starts_with($world->light_of_image, 'images/') && !str_starts_with($world->light_of_image, '/images/')) {
                Storage::disk('public')->delete($world->light_of_image);
            }
            $data['light_of_image'] = $request->light_of_image->store('/uploads/light_worlds', 'public');
        }

        if ($request->hasFile('light_on_image')) {
            if ($world->light_on_image && !str_starts_with($world->light_on_image, 'images/') && !str_starts_with($world->light_on_image, '/images/')) {
                Storage::disk('public')->delete($world->light_on_image);
            }
            $data['light_on_image'] = $request->light_on_image->store('/uploads/light_worlds', 'public');
        }

        $world->update($data);

        return redirect()->route('light_worlds_admin')->withSuccess('World of light has been updated successfully.');
    }

    public function delete($id)
    {
        $world = LightWorld::findOrFail($id);

        if ($world->light_of_image && !str_starts_with($world->light_of_image, 'images/') && !str_starts_with($world->light_of_image, '/images/')) {
            Storage::disk('public')->delete($world->light_of_image);
        }
        if ($world->light_on_image && !str_starts_with($world->light_on_image, 'images/') && !str_starts_with($world->light_on_image, '/images/')) {
            Storage::disk('public')->delete($world->light_on_image);
        }

        $world->delete();

        return redirect()->route('light_worlds_admin')->withSuccess('World of light has been deleted successfully.');
    }
}
