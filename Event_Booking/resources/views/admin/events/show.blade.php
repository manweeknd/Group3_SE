<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover rounded-lg mb-4">
                        @endif
                        <h3 class="text-lg font-semibold mb-2">Description</h3>
                        <p class="text-gray-700 mb-4">{{ $event->description }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="font-semibold text-gray-900">Event Details</h4>
                            <div class="mt-2 space-y-2">
                                {{-- Added a check for date to prevent errors if it's a string --}}
                                <p><strong>Date:</strong> {{ $event->date instanceof \DateTime ? $event->date->format('M j, Y \a\t g:i A') : $event->date }}</p>
                                <p><strong>Location:</strong> {{ $event->location }}</p>
                                <p><strong>Price:</strong> RM{{ number_format($event->price, 2) }}</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900">Capacity & Availability</h4>
                            <div class="mt-2 space-y-2">
                                <p><strong>Total Capacity:</strong> {{ $event->capacity }}</p>
                                <p><strong>Available Tickets:</strong> {{ $event->availableTickets() }}</p>
                                <p><strong>Booked Tickets:</strong> {{ $event->capacity - $event->availableTickets() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('admin.events.edit', $event) }}" class="bg-blue-500 hover:bg-blue-600 text-black px-4 py-2 rounded transition">
                            Edit Event
                        </a>
                        <a href="{{ route('admin.events.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>