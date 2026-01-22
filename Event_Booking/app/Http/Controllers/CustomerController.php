<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Represents custPanel class from diagram

    public function index(Request $request)
    {
        // showCustPanel / searchEvents
        $query = Event::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
        }

        // Upcoming events
        $events = $query->where('date', '>=', now())
                        ->orderBy('date')
                        ->get();

        if ($request->has('search')) {
            $bigEvents = collect();
        } else {
            $bigEvents = Event::where('date', '>=', now())
                            ->whereNotNull('invitations')
                            ->where('invitations', '!=', '')
                            ->orderBy('capacity', 'desc')
                            ->get();
        }

        $myBookings = auth()->check() 
            ? auth()->user()->bookings()->whereHas('event', function($q) {
                $q->where('date', '>=', now());
            })->with('event')->get()
            : collect();

        return view('customer.dashboard', compact('events', 'bigEvents', 'myBookings'));
    }

    public function bookValues(Event $event)
    {
         // bookEvents (view/logic preparation)
         return view('customer.events.show', compact('event'));
    }

    public function book(Request $request, Event $event)
    {
        // bookEvents (action)
        // Check availability
        if ($event->availableTickets() < 1) {
            return back()->with('error', 'Event is fully booked.');
        }

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'status' => 'pending', // Pending payment
            'quantity' => 1, // Default to 1 for now
            'total_price' => $event->price,
        ]);

        // Redirect to payment page
        return view('customer.payment', compact('booking'));
    }

    public function show(Event $event)
    {
        // View event details
        return view('customer.events.show', compact('event'));
    }

    public function bookings()
    {
         // bookingHistory
         $bookings = auth()->user()->bookings()->with('event')->get();
         return view('customer.bookings', compact('bookings'));
    }

    public function payBooking(Booking $booking)
    {
        // payBooking logic
        // Redirect to payment gateway or show payment panel
        return view('customer.payment', compact('booking'));
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'booking_id' => 'required|exists:bookings,id',
            'quantity' => 'nullable|integer|min:1' // Accept quantity from frontend
        ]);

        $voucher = \App\Models\Voucher::where('code', $request->voucher_code)->first();
        $booking = Booking::find($request->booking_id);

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Invalid voucher code.']);
        }

        // Use frontend quantity if provided, else fall back to booking->quantity
        $qty = $request->input('quantity', $booking->quantity);

        // Calculate discount
        $originalPrice = $booking->event->price * $qty;
        $discountCallback = 0;

        if ($voucher->type == 'fixed') {
            $discountCallback = $voucher->value;
        } elseif ($voucher->type == 'percent') {
            $discountCallback = $originalPrice * ($voucher->value / 100);
        }

        $newTotal = max(0, $originalPrice - $discountCallback);
        
        return response()->json([
            'success' => true,
            'message' => 'Voucher applied successfully!',
            'discount' => $discountCallback,
            'new_total' => $newTotal,
            'voucher_code' => $voucher->code,
            'voucher_type' => $voucher->type,
            'voucher_value' => $voucher->value
        ]);
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required_unless:total_amount,0',
            'total_amount' => 'required|numeric'
        ]);

        $booking = Booking::find($request->booking_id);
        
        // In a real app, verify the total amount matches server calculations + voucher
        // For this demo, we trust the flow for simplicity or re-verify if needed

        // Update booking status
        $booking->status = 'confirmed';
        $booking->save();

        // Mock sending email
        // Mail::to(auth()->user())->send(new BookingConfirmed($booking));

        return redirect()->route('customer.bookings')->with('payment_success', 'Payment Confirmed, Confirmation email will be sent!. Thank You');
    }
}
