<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Events') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6 rounded-lg shadow-lg mb-8">
            <h1 class="text-3xl font-bold text-center">Discover Amazing Events</h1>
            <p class="text-blue-100 text-center mt-2">Find and book tickets for the best events in your area</p>
        </div>

        <form action="{{ route('events.index') }}" method="GET" class="mb-8 bg-white p-6 rounded-lg shadow-md">
            <div class="flex flex-col md:flex-row gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events by title, description, or location..." class="flex-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-3 transition">
                <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold px-8 py-3 rounded-lg shadow-lg transition transform hover:scale-105">
                    Search Events
                </button>
            </div>
        </form>

        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($events as $event)
                    <div class="bg-white shadow-xl rounded-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-1">
                        @if($event->image)
                            <div class="h-48 bg-gradient-to-r from-gray-200 to-gray-300 relative overflow-hidden">
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                <div class="absolute top-4 right-4 bg-white bg-opacity-90 px-3 py-1 rounded-full text-sm font-semibold text-gray-800">
                                    ${{ number_format($event->price, 2) }}
                                </div>
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                <div class="text-white text-center">
                                    <svg class="mx-auto h-16 w-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"></path>
                                    </svg>
                                    <p class="text-lg font-bold">${{ number_format($event->price, 2) }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">{{ $event->title }}</h2>
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($event->description, 120) }}</p>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"></path>
                                    </svg>
                                    {{ $event->date->format('M j, Y \a\t g:i A') }}
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $event->location }}
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $event->availableTickets() }} tickets available
                                </div>
                            </div>

                            @if($event->date->isPast())
                                <span class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded-lg shadow-lg text-center block cursor-not-allowed">
                                    Booking Closed
                                </span>
                            @else
                                <a href="{{ route('events.show', $event) }}" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition transform hover:scale-105 text-center block">
                                    View Details & Book
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $events->links() }}
            </div>
        @else
            <div class="bg-white p-12 rounded-lg shadow-md text-center">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-.98-5.5-2.5m0 0V14a2 2 0 002 2h6a2 2 0 002-2v-2.5"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No events found</h3>
                <p class="text-gray-500">Try adjusting your search criteria or check back later for new events.</p>
            </div>
        @endif
    </div>
</x-app-layout>