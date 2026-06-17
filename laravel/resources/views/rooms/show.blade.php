@extends('layouts.app')
@section('title', $room->name)

@section('content')
<div class="pt-24 pb-16 max-w-6xl mx-auto px-4">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-slate-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-white">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('rooms.index') }}" class="hover:text-white">Rooms</a>
        <span class="mx-2">/</span>
        <span class="text-white">{{ $room->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

        {{-- Left: Room details --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- Hero image --}}
            <div class="rounded-2xl overflow-hidden h-72 bg-gradient-to-br from-purple-900 to-dark">
                @if($room->image)
                    <img src="{{ $room->image_url }}" alt="{{ $room->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="text-8xl opacity-20">🎤</span>
                    </div>
                @endif
            </div>

            {{-- Room meta --}}
            <div class="glass rounded-2xl p-6">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                    <div>
                        <h1 class="font-display text-3xl font-bold text-white">{{ $room->name }}</h1>
                        <div class="flex gap-2 mt-2">
                            <span class="bg-primary/20 text-primary-light text-xs font-semibold px-3 py-1 rounded-full capitalize">{{ $room->type }}</span>
                            <span class="bg-dark/60 text-slate-300 text-xs px-3 py-1 rounded-full capitalize">{{ $room->size }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-3xl font-bold text-accent">₱{{ number_format($room->price_per_hour) }}</span>
                        <p class="text-slate-400 text-sm">per hour</p>
                    </div>
                </div>

                <p class="text-slate-300 leading-relaxed">{{ $room->description }}</p>

                {{-- Key info tiles --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-6">
                    <div class="bg-purple-900/20 rounded-xl p-3 text-center">
                        <i class="fa-solid fa-users text-primary-light text-xl mb-1"></i>
                        <p class="text-white font-semibold">{{ $room->capacity }}</p>
                        <p class="text-slate-400 text-xs">Max Guests</p>
                    </div>
                    <div class="bg-purple-900/20 rounded-xl p-3 text-center">
                        <i class="fa-solid fa-door-open text-primary-light text-xl mb-1"></i>
                        <p class="text-white font-semibold capitalize">{{ $room->type }}</p>
                        <p class="text-slate-400 text-xs">Room Type</p>
                    </div>
                    <div class="bg-purple-900/20 rounded-xl p-3 text-center">
                        <i class="fa-solid fa-ruler-combined text-primary-light text-xl mb-1"></i>
                        <p class="text-white font-semibold capitalize">{{ $room->size }}</p>
                        <p class="text-slate-400 text-xs">Room Size</p>
                    </div>
                </div>
            </div>

            {{-- Amenities --}}
            @if($room->amenities && count($room->amenities))
            <div class="glass rounded-2xl p-6">
                <h2 class="font-semibold text-white text-lg mb-4">Room Amenities</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($room->amenities as $amenity)
                    <div class="flex items-center gap-2 text-sm text-slate-300">
                        <i class="fa-solid fa-circle-check text-emerald-400 text-xs"></i>
                        {{ $amenity }}
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Right: Booking widget --}}
        <div class="lg:col-span-2">
            <div class="glass rounded-2xl p-6 sticky top-24">
                <h2 class="font-display text-xl font-bold text-white mb-1">Reserve This Room</h2>
                <p class="text-slate-400 text-sm mb-5">Fill in your preferred date & time to get a cost estimate.</p>

                {{-- Quick cost calculator --}}
                <div class="bg-dark/40 rounded-xl p-4 mb-5 space-y-3" id="cost-calc">
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Date</label>
                        <input type="date" id="calc-date" min="{{ date('Y-m-d') }}"
                               class="w-full bg-dark border border-purple-900/50 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-light" />
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">Start Time</label>
                            <input type="time" id="calc-start"
                                   class="w-full bg-dark border border-purple-900/50 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-light" />
                        </div>
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">End Time</label>
                            <input type="time" id="calc-end"
                                   class="w-full bg-dark border border-purple-900/50 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-light" />
                        </div>
                    </div>
                    <div id="calc-result" class="hidden bg-purple-900/30 rounded-lg p-3 text-center">
                        <p class="text-slate-400 text-xs">Estimated Total</p>
                        <p class="text-accent text-2xl font-bold" id="calc-amount">₱0</p>
                        <p class="text-slate-500 text-xs" id="calc-hours"></p>
                    </div>
                </div>

                {{-- CTA buttons --}}
                <div class="space-y-3">
                    @auth
                    <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}"
                       class="block text-center bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-xl transition">
                        🎤 Book Now (Member)
                    </a>
                    @endauth
                    <a href="{{ route('bookings.guestCreate', ['room_id' => $room->id]) }}"
                       class="block text-center border border-purple-500/50 text-purple-300 hover:text-white hover:border-purple-400 font-semibold py-3 rounded-xl transition">
                        Book as Guest
                    </a>
                    @guest
                    <p class="text-center text-slate-500 text-xs">
                        <a href="{{ route('auth.login') }}" class="text-primary-light hover:text-white">Sign in</a>
                        for member benefits
                    </p>
                    @endguest
                </div>

                {{-- Availability status --}}
                <div class="mt-4 flex items-center gap-2 text-sm">
                    @if($room->is_available)
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-emerald-300">Available for booking</span>
                    @else
                    <span class="w-2 h-2 rounded-full bg-red-400"></span>
                    <span class="text-red-300">Currently unavailable</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const pricePerHour = {{ $room->price_per_hour }};

function calcCost() {
    const start = document.getElementById('calc-start').value;
    const end   = document.getElementById('calc-end').value;
    if (!start || !end || end <= start) return;

    const [sh, sm] = start.split(':').map(Number);
    const [eh, em] = end.split(':').map(Number);
    const minutes = (eh * 60 + em) - (sh * 60 + sm);
    if (minutes <= 0) return;

    const hours = minutes / 60;
    const total = hours * pricePerHour;

    document.getElementById('calc-result').classList.remove('hidden');
    document.getElementById('calc-amount').textContent = '₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2 });
    document.getElementById('calc-hours').textContent  = hours.toFixed(1) + ' hr(s) × ₱' + pricePerHour.toLocaleString();
}

document.getElementById('calc-start').addEventListener('change', calcCost);
document.getElementById('calc-end').addEventListener('change', calcCost);
</script>
@endpush
