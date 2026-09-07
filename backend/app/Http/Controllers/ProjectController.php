<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{

    public function __construct()
    {
    }

    public function index()
    {
        $data = array('title' => "Our Projects");
        $data['projects'] = Project::with('projectImages')->where('is_active', 'yes')->orderBy('sequence', 'ASC')->get();
        foreach ($data['projects'] as $project) {
            $project['typeSlug'] = '--' . str_replace(" ", "-", strtolower($project->type));
        }
        $data['default_data_filter'] = '';
        return view('pages.projects', $data);
    }

    public function projectsByFilter(Request $request, $filter = null) {
        $data = array('title' => "Our Projects");
        $data['projects'] = Project::with('projectImages')->where('is_active', 'yes')->orderBy('sequence', 'ASC')->get();
        foreach ($data['projects'] as $project) {
            $project['typeSlug'] = '--' . str_replace(" ", "-", strtolower($project->type));
        }
        $data['default_data_filter'] = '';
        if ($filter !== null && $filter !== 'null') {
            foreach ($data['projects'] as $project) {
                if ($this->getSlugWithout($project->type) == $this->getSlugWithout($filter)) {
                    $data['default_data_filter'] = $filter;
                }
            }
        }
        return view('pages.projects', $data);
    }

    function getSlugWithout($name) {
        $name = str_replace("-", "", $name);
        $name = str_replace(" ", "", $name);
        $name = strtolower($name);
        $name = preg_replace('/[^A-Za-z0-9\-]/', '', $name); // Removes special chars.
        return $name;
    }

    public function show($slug)
    {
        $data['project'] = Project::with('projectImages')->with(['projectSubTags.sub_tag' => function ($query) {
            $query->latest()->limit(20);
        }])->where('slug', $slug)->first();
        $id = $data['project']->id;

        $prev = null;
        $next = null;
        $arr = DB::select("(select *  from projects WHERE id > " . $id . " AND deleted_at IS NULL ORDER BY id ASC LIMIT 1) UNION (select *  from projects WHERE id < " . $id . " AND deleted_at IS NULL ORDER BY id DESC LIMIT 1);");

        if (isset($arr[0])) {
            if ($arr[0]->id < $data['project']->id) {
                $prev = $arr[0]->slug;
            } else {
                $next = $arr[0]->slug;
            }
        }

        if (isset($arr[1])) {
            if ($arr[1]->id < $data['project']->id) {
                $prev = $arr[1]->slug;
            } else {
                $next = $arr[1]->slug;
            }
        }

        $data['prev'] = $prev;
        $data['next'] = $next;
       // dd($data);
        return view('pages.project-detail', $data);
    }
}
