<?php

namespace App\Http\Controllers;

use App\User;
use App\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function create(Request $request)
    {
        $event = new Event();

        $event->user_id = Auth::id();
        $event->name = $request->name;
        $event->color = $request->color;
        $event->details = $request->details;
        $event->start = $request->start;
        $event->end = $request->end;

        Auth::user()->events()->save($event);

        return response($event, 201);
    }

    public function index()
    {
        $events = Event::all();

        return $events;
    }

    public function delete(int $id)
    {
        $event = Event::where('id', $id)->first();
        $event->delete();

        $events = Event::all();

        return $events;
    }

    public function update(Request $request, int $id)
    {
        $event = Event::find($id);
        $event->details = $request->details;

        Auth::user()->events()->save($event);

        return $event;
    }
}
