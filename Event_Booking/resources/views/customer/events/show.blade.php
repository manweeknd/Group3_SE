<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <!-- Image Section -->
                    <!-- Image Section -->
                    <div class="bg-gray-200 h-64 md:h-full relative overflow-hidden">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-500 bg-gray-300">
                                <span>No Image Available</span>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur rounded-full px-4 py-1 text-sm font-bold shadow-sm">
                            ${{ $event->price }}
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div class="p-8 flex flex-col justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $event->title }}</h1>
                            <div class="flex items-center text-sm text-gray-500 mb-6">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $event->location }}
                                <span class="mx-2">|</span>
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $event->date->format('F j, Y g:i A') }}
                            </div>

                                <h3 class="text-lg font-semibold text-gray-800 mb-2">About this Event</h3>
                                <p>{{ $event->description }}</p>

                                <div class="mt-4">
                                    <h4 class="text-md font-semibold text-gray-800">Invitations</h4>
                                    <p class="text-sm {{ $event->invitations ? 'text-indigo-600' : 'text-gray-400 italic' }}">
                                        {{ $event->invitations ? $event->invitations : 'No Invitations' }}
                                    </p>
                                </div>

                            <div class="text-sm text-gray-500 mb-8">
                                <span class="font-semibold text-gray-900">Capacity:</span> {{ $event->capacity }} people
                                <br>
                                <span class="font-semibold text-gray-900">Remaining Tickets:</span> {{ $event->availableTickets() }}
                                <br>
                                <span class="font-semibold text-gray-900">Price:</span> RM {{ number_format($event->price, 2) }}
                            </div>
                        </div>

                        <div>
                            <form action="{{ route('events.book', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-black hover:bg-gray-800 text-white font-bold py-4 rounded-lg shadow-lg transition transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed" style="background-color: black !important;" {{ $event->availableTickets() < 1 ? 'disabled' : '' }}>
                                    {{ $event->availableTickets() > 0 ? 'Book Ticket' : 'Sold Out' }}
                                </button>
                            </form>
                            @if(session('success'))
                                <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="mt-4 p-4 bg-red-100 text-red-700 rounded-lg">
                                    {{ session('error') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
