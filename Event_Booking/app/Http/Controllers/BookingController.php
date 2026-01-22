<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()->bookings()->with('event')->get();
        return view('bookings.index', compact('bookings'));
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $request->quantity;

        if ($event->availableTickets() < $quantity) {
            return back()->withErrors(['quantity' => 'Not enough tickets available.']);
        }

        Booking::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'quantity' => $quantity,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully.');
    }

    public function update(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $request->quantity;

        if ($booking->event->availableTickets() + $booking->quantity < $quantity) {
            return back()->withErrors(['quantity' => 'Not enough tickets available.']);
        }

        $booking->update(['quantity' => $quantity]);

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Booking cancelled successfully.');
    }
}
