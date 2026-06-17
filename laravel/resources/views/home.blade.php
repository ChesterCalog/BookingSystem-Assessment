@extends('layouts.app')

@section('title', 'KaraokeZone — Sing Your Heart Out')

@section('content')

{{-- ============================================================
     HERO SECTION
     ============================================================ --}}
<section class="hero-bg min-h-screen flex items-center justify-center relative overflow-hidden pt-16">
    {{-- Decorative circles --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-yellow-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-purple-700/5 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-5xl mx-auto px-4 text-center relative z-10">
        <div class="inline-flex items-center gap-2 bg-purple-900/40 border border-purple-500/30 rounded-full px-4 py-2 text-sm text-purple-300 mb-6">
            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
            Now Open — Book Your Room Today!
        </div>
        <h1 class="font-display text-5xl md:text-7xl font-extrabold text-white leading-tight mb-6">
            Unleash Your Inner<br>
            <span class="gradient-text">Superstar</span>
        </h1>
        <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto mb-10">
            Premium private karaoke rooms for every occasion. Book online in minutes and get ready for an unforgettable night of singing, laughter, and fun.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('bookings.guestCreate') }}"
               class="bg-primary hover:bg-primary-dark text-white font-semibold px-8 py-4 rounded-xl text-lg transition shadow-lg shadow-purple-900/50 hover:shadow-purple-700/50">
                🎤 Book a Room Now
            </a>
            <a href="{{ route('rooms.index') }}"
               class="border border-purple-500/50 text-purple-300 hover:text-white hover:border-purple-400 font-semibold px-8 py-4 rounded-xl text-lg transition">
                View All Rooms
            </a>
        </div>

        {{-- Quick stats --}}
        <div class="mt-16 grid grid-cols-3 gap-6 max-w-xl mx-auto">
            @foreach([['50+', 'Songs Available'], ['10', 'Private Rooms'], ['10K+', 'Happy Singers']] as $stat)
            <div class="glass rounded-xl p-4">
                <p class="font-display text-3xl font-bold text-white">{{ $stat[0] }}</p>
                <p class="text-slate-400 text-xs mt-1">{{ $stat[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     WHY CHOOSE US
     ============================================================ --}}
<section class="py-20 bg-dark-card/30">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="font-display text-4xl font-bold text-white mb-4">Why Choose <span class="gradient-text">KaraokeZone?</span></h2>
            <p class="text-slate-400 max-w-xl mx-auto">We go beyond just karaoke. Every detail is designed for your perfect night out.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $reasons = [
                ['fa-shield-halved',    'Premium Sound System',      'Studio-grade audio equipment with crystal-clear mics and powerful speakers for an immersive experience.'],
                ['fa-lock',             'Private Rooms',              'Enjoy karaoke with complete privacy. Perfect for intimate gatherings, birthdays, or a night with friends.'],
                ['fa-music',            '50,000+ Songs',              'Massive catalog spanning all genres, languages, and eras. New songs added every week.'],
                ['fa-clock',            'Flexible Hours',             'Open until late (and sometimes all night on weekends). Book for as little as 1 hour.'],
                ['fa-utensils',         'Food & Beverage Service',    'Enjoy snacks, cocktails, and soft drinks delivered right to your room.'],
                ['fa-calendar-check',   'Easy Online Booking',        'Book your room in under 2 minutes. No account required for guest bookings.'],
            ];
            @endphp
            @foreach($reasons as [$icon, $title, $desc])
            <div class="glass rounded-2xl p-6 card-hover">
                <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center mb-4">
                    <i class="fa-solid {{ $icon }} text-primary-light text-xl"></i>
                </div>
                <h3 class="font-semibold text-white text-lg mb-2">{{ $title }}</h3>
                <p class="text-slate-400 text-sm">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     FEATURED ROOMS
     ============================================================ --}}
<section class="py-20">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="font-display text-4xl font-bold text-white mb-4">Our <span class="gradient-text">Rooms</span></h2>
            <p class="text-slate-400">From cozy duet rooms to massive party halls — we have a space for every occasion.</p>
        </div>

        @if($featuredRooms->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredRooms as $room)
            <div class="glass rounded-2xl overflow-hidden card-hover group">
                <div class="relative h-48 bg-gradient-to-br from-purple-900 to-dark overflow-hidden">
                    @if($room->image)
                        <img src="{{ $room->image_url }}" alt="{{ $room->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-6xl opacity-30">🎤</span>
                        </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="bg-primary text-white text-xs font-semibold px-2 py-1 rounded-full capitalize">{{ $room->type }}</span>
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="bg-dark/80 text-white text-xs px-2 py-1 rounded-full">
                            <i class="fa-solid fa-users mr-1"></i>{{ $room->capacity }} pax
                        </span>
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="font-semibold text-white text-lg mb-1">{{ $room->name }}</h3>
                    <p class="text-slate-400 text-sm mb-3 line-clamp-2">{{ $room->description }}</p>

                    @if($room->amenities)
                    <div class="flex flex-wrap gap-1 mb-4">
                        @foreach(array_slice($room->amenities, 0, 4) as $amenity)
                        <span class="text-xs bg-purple-900/40 text-purple-300 px-2 py-0.5 rounded">{{ $amenity }}</span>
                        @endforeach
                    </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-bold text-accent">₱{{ number_format($room->price_per_hour) }}</span>
                            <span class="text-slate-400 text-sm">/hour</span>
                        </div>
                        <a href="{{ route('rooms.show', $room) }}"
                           class="bg-primary hover:bg-primary-dark text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                            Book Room
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center text-slate-400 py-12">
            <p>No rooms available right now. Check back soon!</p>
        </div>
        @endif

        <div class="text-center mt-10">
            <a href="{{ route('rooms.index') }}" class="border border-purple-500/50 text-purple-300 hover:text-white hover:border-purple-400 font-medium px-8 py-3 rounded-xl transition">
                View All Rooms <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

{{-- ============================================================
     TESTIMONIALS
     ============================================================ --}}
<section class="py-20 bg-dark-card/30">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="font-display text-4xl font-bold text-white mb-4">What Our <span class="gradient-text">Customers Say</span></h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($testimonials as $t)
            <div class="glass rounded-2xl p-6">
                <div class="flex mb-3">
                    @for($i = 0; $i < $t['rating']; $i++)
                    <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                    @endfor
                </div>
                <p class="text-slate-300 text-sm mb-4 italic">"{{ $t['comment'] }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-sm font-bold">
                        {{ strtoupper(substr($t['name'], 0, 1)) }}
                    </div>
                    <span class="text-white text-sm font-medium">{{ $t['name'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     FAQ
     ============================================================ --}}
<section class="py-20">
    <div class="max-w-3xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="font-display text-4xl font-bold text-white mb-4">Frequently Asked <span class="gradient-text">Questions</span></h2>
        </div>
        <div class="space-y-3" id="faq-accordion">
            @foreach($faqs as $i => $faq)
            <div class="glass rounded-xl overflow-hidden">
                <button onclick="toggleFaq({{ $i }})"
                        class="w-full flex items-center justify-between p-5 text-left hover:bg-purple-900/10 transition">
                    <span class="font-medium text-white text-sm">{{ $faq['q'] }}</span>
                    <i id="faq-icon-{{ $i }}" class="fa-solid fa-chevron-down text-purple-400 transition-transform"></i>
                </button>
                <div id="faq-body-{{ $i }}" class="hidden px-5 pb-5">
                    <p class="text-slate-400 text-sm">{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     CTA BANNER
     ============================================================ --}}
<section class="py-20 bg-gradient-to-r from-purple-900/40 to-dark-card/60 border-y border-purple-900/30">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="font-display text-4xl font-bold text-white mb-4">Ready to Sing? 🎤</h2>
        <p class="text-slate-300 text-lg mb-8">Book your karaoke room now and start your night of fun. No account required!</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('bookings.guestCreate') }}"
               class="bg-primary hover:bg-primary-dark text-white font-semibold px-10 py-4 rounded-xl text-lg transition shadow-lg shadow-purple-900/50">
                Book as Guest
            </a>
            <a href="{{ route('auth.register') }}"
               class="border border-accent/50 text-accent hover:bg-accent hover:text-dark font-semibold px-10 py-4 rounded-xl text-lg transition">
                Create an Account
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function toggleFaq(index) {
    const body = document.getElementById('faq-body-' + index);
    const icon = document.getElementById('faq-icon-' + index);
    body.classList.toggle('hidden');
    icon.style.transform = body.classList.contains('hidden') ? '' : 'rotate(180deg)';
}
</script>
@endpush
