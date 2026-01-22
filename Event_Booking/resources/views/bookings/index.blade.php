@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">My Bookings</h1>
    @if($bookings->isEmpty())
    <p>You have no bookings yet.</p>
    @else
    <div class="space-y-4">
        @foreach($bookings as $booking)
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-bold mb-2">{{ $booking->event->title }}</h2>
            <p><strong>Date:</strong> {{ $booking->event->date->format('Y-m-d H:i') }}</p>
            <p><strong>Location:</strong> {{ $booking->event->location }}</p>
            <p><strong>Quantity:</strong> {{ $booking->quantity }}</p>
            <p><strong>Total Price:</strong> ${{ $booking->quantity * $booking->event->price }}</p>
            <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
            @if($booking->status === 'confirmed')
            <form action="{{ route('bookings.update', $booking) }}" method="POST" class="mt-4">
                @csrf
                @method('PUT')
                <label for="quantity" class="block text-sm font-medium text-gray-700">Update Quantity</label>
                <input type="number" name="quantity" value="{{ $booking->quantity }}" min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Update</button>
            </form>
            <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="mt-4">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('Are you sure?')">Cancel Booking</button>
            </form>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection