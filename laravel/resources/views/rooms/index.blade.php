@extends('layouts.app')
@section('title', 'Browse Rooms')

@section('content')
<div class="pt-24 pb-16">

    {{-- Page Header --}}
    <div class="bg-gradient-to-b from-purple-900/20 to-transparent py-12 text-center">
        <h1 class="font-display text-4xl md:text-5xl font-bold text-white mb-3">Our <span class="gradient-text">Karaoke Rooms</span></h1>
        <p class="text-slate-400 max-w-xl mx-auto">Find the perfect room for your group. Filter by type, capacity, or price.</p>
    </div>

    <div class="max-w-7xl mx-auto px-4">

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('rooms.index') }}"
              class="glass rounded-2xl p-5 mb-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Room Type</label>
                <select name="type"
                        class="w-full bg-dark/60 border border-purple-900/50 rounded-lg px-3 py-2.5 text-white text-sm focus:outline-none focus:border-primary-light">
                    <option value="">All Types</option>
                    @foreach(['standard','deluxe','vip','party'] as $type)
                    <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Min. Capacity</label>
                <input type="number" name="capacity" value="{{ request('capacity') }}" min="1" placeholder="e.g. 6"
                       class="w-full bg-dark/60 border border-purple-900/50 rounded-lg px-3 py-2.5 text-white text-sm focus:outline-none focus:border-primary-light placeholder-slate-600" />
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Max Price/Hour (₱)</label>
                <input type="number" name="max_price" value="{{ request('max_price') }}" min="0" placeholder="e.g. 500"
                       class="w-full bg-dark/60 border border-purple-900/50 rounded-lg px-3 py-2.5 text-white text-sm focus:outline-none focus:border-primary-light placeholder-slate-600" />
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 bg-primary hover:bg-primary-dark text-white text-sm font-semibold py-2.5 rounded-lg transition">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('rooms.index') }}"
                   class="px-3 py-2.5 border border-purple-700 text-slate-400 hover:text-white rounded-lg transition text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>
        </form>

        {{-- Results count --}}
        <p class="text-slate-400 text-sm mb-6">
            Showing <span class="text-white font-medium">{{ $rooms->total() }}</span> room{{ $rooms->total() !== 1 ? 's' : '' }}
            @if(request()->hasAny(['type','capacity','max_price']))
            (filtered)
            @endif
        </p>

        {{-- Room Grid --}}
        @if($rooms->isEmpty())
        <div class="glass rounded-2xl p-16 text-center">
            <span class="text-6xl opacity-20">🎤</span>
            <p class="text-slate-400 mt-4 text-lg">No rooms match your search.</p>
            <a href="{{ route('rooms.index') }}" class="text-primary-light hover:text-white text-sm mt-2 inline-block">Clear filters</a>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($rooms as $room)
            <div class="glass rounded-2xl overflow-hidden card-hover group flex flex-col">

                {{-- Room image --}}
                <div class="relative h-52 bg-gradient-to-br from-purple-900 to-dark overflow-hidden">
                    @if($room->image)
                        <img src="{{ $room->image_url }}" alt="{{ $room->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-7xl opacity-20">🎤</span>
                        </div>
                    @endif

                    {{-- Badges --}}
                    <div class="absolute top-3 left-3 flex gap-2">
                        <span class="bg-primary text-white text-xs font-semibold px-2.5 py-1 rounded-full capitalize">{{ $room->type }}</span>
                        <span class="bg-dark/70 text-white text-xs px-2.5 py-1 rounded-full capitalize">{{ $room->size }}</span>
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="bg-dark/70 text-white text-xs px-2.5 py-1 rounded-full">
                            <i class="fa-solid fa-users mr-1"></i>{{ $room->capacity }} pax
                        </span>
                    </div>
                </div>

                {{-- Room info --}}
                <div class="p-5 flex flex-col flex-1">
                    <h3 class="font-semibold text-white text-xl mb-1">{{ $room->name }}</h3>
                    <p class="text-slate-400 text-sm mb-4 flex-1 line-clamp-2">{{ $room->description }}</p>

                    {{-- Amenities --}}
                    @if($room->amenities && count($room->amenities))
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        @foreach(array_slice($room->amenities, 0, 5) as $amenity)
                        <span class="text-xs bg-purple-900/40 text-purple-300 border border-purple-800/40 px-2 py-0.5 rounded-full">
                            {{ $amenity }}
                        </span>
                        @endforeach
                        @if(count($room->amenities) > 5)
                        <span class="text-xs text-slate-500">+{{ count($room->amenities) - 5 }} more</span>
                        @endif
                    </div>
                    @endif

                    {{-- Price + CTA --}}
                    <div class="flex items-center justify-between pt-3 border-t border-purple-900/30">
                        <div>
                            <span class="text-2xl font-bold text-accent">₱{{ number_format($room->price_per_hour) }}</span>
                            <span class="text-slate-400 text-sm"> /hour</span>
                        </div>
                        <a href="{{ route('rooms.show', $room) }}"
                           class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">{{ $rooms->links() }}</div>
        @endif
    </div>
</div>
@endsection
