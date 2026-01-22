<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Event') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-xl rounded-b-lg p-8">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Event Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 px-4 py-3 transition" required placeholder="Enter event title">
                @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 px-4 py-3 transition" required placeholder="Describe the event">{{ old('description', $event->description) }}</textarea>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="invitations" class="block text-sm font-semibold text-gray-700 mb-2">Invitations (Fill to make this a Big Event)</label>
                <textarea name="invitations" id="invitations" rows="2" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 px-4 py-3 transition" placeholder="Enter invitation details">{{ old('invitations', $event->invitations) }}</textarea>
                @error('invitations') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Event Image</label>
                @if($event->image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $event->image) }}" alt="Current Image" class="w-32 h-32 object-cover rounded-lg shadow-md">
                        <p class="text-sm text-gray-500 mt-2">Current image. Upload a new one to replace it.</p>
                    </div>
                @endif
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                <span>Upload a new image</span>
                                <input type="file" name="image" id="image" accept="image/*" class="sr-only">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                </div>
                @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-6">
                    <label for="date" class="block text-sm font-semibold text-gray-700 mb-2">Date & Time</label>
                    <input type="datetime-local" name="date" id="date" value="{{ old('date', $event->date->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 px-4 py-3 transition" required>
                    @error('date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 px-4 py-3 transition" required placeholder="Event location">
                    @error('location') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-6">
                    <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Price (RM)</label>
                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $event->price) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 px-4 py-3 transition" required placeholder="0.00">
                    @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="capacity" class="block text-sm font-semibold text-gray-700 mb-2">Capacity</label>
                    <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $event->capacity) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 px-4 py-3 transition" required placeholder="Number of attendees">
                    @error('capacity') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-black font-bold px-8 py-3 rounded-lg shadow-lg transition transform hover:scale-105">
                    Update Event
                </button>
                <a href="{{ route('admin.events.index') }}" class="text-gray-600 hover:text-gray-800 font-medium transition">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>