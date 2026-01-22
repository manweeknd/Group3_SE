<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $event->title }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            @if($event->image)
                <div class="relative h-64 md:h-80 bg-gradient-to-r from-blue-500 to-purple-600">
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-end">
                        <div class="p-6 text-white">
                            <h1 class="text-4xl font-bold mb-2">{{ $event->title }}</h1>
                            <p class="text-xl text-blue-100">{{ $event->location }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6">
                    <h1 class="text-4xl font-bold text-white mb-2">{{ $event->title }}</h1>
                    <p class="text-xl text-blue-100">{{ $event->location }}</p>
                </div>
            @endif

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700 mb-2">Date & Time</h3>
                        <p class="text-lg text-gray-900">{{ $event->date->format('M j, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700 mb-2">Price</h3>
                        <p class="text-lg text-gray-900">${{ number_format($event->price, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-700 mb-2">Available Tickets</h3>
                        <p class="text-lg text-gray-900">{{ $event->availableTickets() }}</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">About This Event</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $event->description }}</p>
                </div>

                @if($event->date->isPast())
                    <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                        <p class="text-red-600 font-semibold">Booking Closed</p>
                    </div>
                @elseif($event->availableTickets() > 0)
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 p-6 rounded-lg border border-green-200">
                        <form action="{{ route('events.book', $event) }}" method="POST" class="flex flex-col md:flex-row items-end gap-4">
                            @csrf
                            <div class="flex-1">
                                <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">Number of Tickets</label>
                                <input type="number" name="quantity" id="quantity" min="1" max="{{ $event->availableTickets() }}" value="1" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 px-4 py-3 transition" required>
                                @error('quantity') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-black font-bold px-8 py-3 rounded-lg shadow-lg transition transform hover:scale-105">
                                Book Tickets
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                        <p class="text-red-600 font-semibold">Sorry, this event is sold out!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>