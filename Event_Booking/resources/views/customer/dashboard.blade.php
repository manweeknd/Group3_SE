<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome To Event Booking System') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <!-- Search Bar moved below header -->
        <form action="{{ route('customer.dashboard') }}" method="GET" class="mb-12">
            <div class="relative max-w-xl mx-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events..." class="w-full border-gray-200 rounded-full shadow-sm focus:ring-purple-500 focus:border-purple-500 pl-4 py-3 bg-gray-50">
                <button type="submit" class="absolute right-2 top-2 bg-purple-600 hover:bg-purple-700 text-white rounded-full p-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </div>
        </form>

        <!-- Upcoming Events Section -->
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            @if(request('search'))
                Search Results
            @else
                Upcoming Events <span class="ml-2 text-gray-400">-></span>
            @endif
        </h3>
        <div class="flex space-x-12 overflow-x-auto pb-6">
            @foreach($events as $event)
                <a href="{{ route('events.show', $event) }}" class="flex-shrink-0 flex flex-col items-center w-20 group">
                    <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden mb-2 shadow-md border-2 border-white ring-2 ring-purple-100 group-hover:ring-purple-400 transition">
                        @if($event->image)
                             <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-full object-cover">
                        @else
                             <span class="text-xs text-gray-500 p-1 text-center leading-tight">{{ Str::limit($event->title, 10) }}</span>
                        @endif
                    </div>
                    <span class="text-xs text-center text-gray-700 font-medium truncate w-full px-1 group-hover:text-purple-700 transition">{{ $event->title }}</span>
                </a>
            @endforeach
        </div>

        <!-- Big Events Section -->
        @if(!request('search'))
        <div class="relative group mt-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                    Big Events <span class="ml-2 text-gray-400">-></span>
                </h3>
                
                <div class="flex space-x-2">
                    <button onclick="scrollEvents('left')" class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button onclick="scrollEvents('right')" class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>

            <div id="event-slider" class="flex space-x-6 overflow-x-auto pb-6 scroll-smooth snap-x no-scrollbar">
                @forelse($bigEvents as $event)
                    <a href="{{ route('events.show', $event) }}" class="flex-shrink-0 w-80 bg-gray-50 rounded-2xl p-4 transition hover:bg-white hover:shadow-lg group snap-start">
                        <div class="w-full aspect-[4/3] bg-gray-300 rounded-xl overflow-hidden mb-4 relative">
                            @if($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <span class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-full text-xs font-bold text-gray-800">
                                ${{ $event->price }}
                            </span>
                        </div>
                        <div class="h-28 flex flex-col justify-between"> <div>
                                <h4 class="font-bold text-lg text-gray-900 leading-tight group-hover:text-purple-700 transition line-clamp-2">
                                    {{ $event->title }}
                                </h4>
                                <p class="text-sm text-purple-600 font-medium mt-1">{{ $event->date->format('M d, Y') }}</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 truncate">{{ $event->location }}</p>
                            @if($event->invitations)
                                <p class="text-xs text-indigo-600 mt-1 font-semibold truncate">Guests: {{ $event->invitations }}</p>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="w-full text-center py-8 text-gray-500">No big events found</div>
                @endforelse
            </div>
        </div>
        @endif

<script>
    function scrollEvents(direction) {
        const slider = document.getElementById('event-slider');
        const scrollAmount = 340; // Card width (320px) + gap (24px)
        if (direction === 'left') {
            slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }
</script>

<style>
    /* Optional: Hide scrollbar but keep functionality */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
        </div>
    </div>
</x-app-layout>
