<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Represents adminPanel class from diagram

    public function index()
    {
        // Upcoming events (today or future)
        $events = Event::where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->paginate(10);
            
        $viewType = 'dashboard';
        return view('admin.dashboard', compact('events', 'viewType'));
    }

    public function history()
    {
        // Past events
        $events = Event::where('date', '<', now())
            ->orderBy('date', 'desc')
            ->paginate(10);
            
        $viewType = 'history';
        return view('admin.dashboard', compact('events', 'viewType'));
    }

    public function create()
    {
        // makeEvents (view)
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        // makeEvents (logic)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'invitations' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        Event::create($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        // editEvents (view)
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        // editEvents (logic) / modifyDetails
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'invitations' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        // deleteEvents
        $event->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Event deleted successfully.');
    }

    public function totalBooked()
    {
        // totalBooked logic
        // This could be a separate view or part of the dashboard
        return Event::withCount('bookings')->get();
    }
}
