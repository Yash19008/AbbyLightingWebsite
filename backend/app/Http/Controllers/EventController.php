<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $data = array('title' => "Fairs & Events");
        $data['events'] = Event::with('eventImages')->where('is_active', 'yes')->orderBy('id', 'DESC')->get();

        return view('pages.fair-events', $data);
    }

    public function show($slug)
    {
        $data['event'] = Event::with('eventImages')->where('slug', $slug)->first();

        return view('pages.event-detail', $data);
    }
}
