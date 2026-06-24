<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Booking Engine | Grand Horizon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:wght@200..800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FAF9F5] min-h-screen text-stone-800 antialiased flex flex-col justify-between">

    <header class="bg-white border-b border-stone-200 px-8 py-4 flex justify-between items-center shadow-sm">
        <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
            <span class="text-stone-400 group-hover:text-amber-800 transition">←</span>
            <span class="text-xs font-bold tracking-widest uppercase text-stone-500 group-hover:text-stone-800 transition">Exit To Main Site</span>
        </a>
        <div class="flex items-center space-x-2">
            <span class="serif text-sm font-bold tracking-widest uppercase text-stone-900">Grand Horizon Reservation Engine</span>
        </div>
    </header>

    <main class="max-w-7xl mx-auto my-12 w-full px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-5 bg-white p-8 rounded-3xl border border-stone-200 shadow-xl space-y-6">
                
                <div class="text-center space-y-1">
                    <span class="text-3xl">🛎️</span>
                    <h1 class="serif text-2xl font-bold text-stone-900">Instant Check-In</h1>
                    <p class="text-xs text-stone-400">Complete your details below to lock in your inventory block.</p>
                </div>

                @if(session('error'))
                    <div class="bg-rose-50 border border-rose-200 p-4 rounded-xl text-rose-800 text-xs font-medium flex items-center space-x-2">
                        <span>⚠️</span> <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('booking.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="space-y-3 bg-stone-50 p-4 rounded-2xl border border-stone-100">
                        <span class="text-[10px] font-bold tracking-wider text-stone-400 uppercase block">1. Guest Contact Details</span>
                        
                        <div>
                            <label class="block text-xs font-semibold text-stone-600 mb-1">Full Legal Name</label>
                            <input type="text" name="guest_name" required placeholder="John Doe" class="w-full p-3 bg-white border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-600 transition">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-stone-600 mb-1">Email Address</label>
                            <input type="email" name="guest_email" required placeholder="johndoe@example.com" class="w-full p-3 bg-white border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-600 transition">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-stone-600 mb-1">Contact Phone</label>
                            <input type="text" name="guest_phone" required placeholder="+63 912 345 6789" class="w-full p-3 bg-white border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-600 transition">
                        </div>
                    </div>

                    <div class="space-y-4 p-1">
                        <span class="text-[10px] font-bold tracking-wider text-stone-400 uppercase block">2. Accommodation Logistics</span>
                        
                        <div>
                            <label class="block text-xs font-semibold text-stone-600 mb-1">Selected Product Tier</label>
                            <select id="room-selector" name="room_type_id" class="w-full p-3 border border-stone-200 bg-white rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-600 transition cursor-pointer">
                                @foreach($roomTypes as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }} (${{ number_format($room->base_price, 2) }} / night)</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-stone-600 mb-1">Check-In Date</label>
                                <input type="date" name="check_in" required class="w-full p-3 border border-stone-200 bg-white rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-amber-600 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-stone-600 mb-1">Check-Out Date</label>
                                <input type="date" name="check_out" required class="w-full p-3 border border-stone-200 bg-white rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-amber-600 transition">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-amber-800 text-white p-4 rounded-xl font-bold tracking-widest uppercase text-xs hover:bg-amber-900 transition shadow-lg pt-4">
                        Confirm Secure Reservation
                    </button>
                </form>
            </div>

            <div class="lg:col-span-7 space-y-6">
                <div class="border-b border-stone-200 pb-4">
                    <span class="text-xs font-bold tracking-widest uppercase text-amber-800">Live Item Summary</span>
                    <h2 class="serif text-2xl font-bold text-stone-900">Selected Product Tier</h2>
                    <p class="text-xs text-stone-500 font-light mt-1">Reviewing your active luxury space profile configuration parameters.</p>
                </div>

                <div class="relative w-full">
                    @foreach($roomTypes as $room)
                        <div data-room-id="{{ $room->id }}" class="room-card hidden bg-white rounded-3xl overflow-hidden shadow-md border border-stone-200 flex flex-col justify-between transition-all duration-300">
                            <div class="relative overflow-hidden aspect-[16/9] bg-stone-200">
                                @if(str_contains(strtolower($room->name), 'deluxe'))
                                    <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1200&q=80" alt="Deluxe Suite" class="w-full h-full object-cover">
                                @elseif(str_contains(strtolower($room->name), 'standard'))
                                    <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1200&q=80" alt="Standard Room" class="w-full h-full object-cover">
                                @elseif(str_contains(strtolower($room->name), 'compact') || str_contains(strtolower($room->name), 'studio'))
                                    <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80" alt="Compact Studio" class="w-full h-full object-cover">
                                @else
                                    <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1200&q=80" alt="Executive Suite" class="w-full h-full object-cover">
                                @endif
                                <div class="absolute bottom-4 right-4 bg-stone-900 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg">
                                    ${{ number_format($room->base_price, 2) }} / Night Allocation
                                </div>
                            </div>

                            <div class="p-8 space-y-4 flex-grow flex flex-col justify-between">
                                <div class="space-y-2">
                                    <h3 class="serif text-2xl font-bold text-stone-900">{{ $room->name }}</h3>
                                    
                                    @if($room->base_price >= 200)
                                        <p class="text-sm text-stone-500 leading-relaxed font-light">An engineering masterpiece featuring soundproofed architectural glass, custom native timber trim framing, and dedicated private high-speed cloud connections.</p>
                                    @elseif($room->base_price >= 100)
                                        <p class="text-sm text-stone-500 leading-relaxed font-light">Features premium orthopedic sleep setups, work-focused ergonomics, dynamic natural lighting, and deep acoustic structural soundproofing buffers.</p>
                                    @else
                                        <p class="text-sm text-stone-500 leading-relaxed font-light">Provides hyper-efficient smart layouts, keyless proximity automation access, secure dynamic personal lockers, and integrated focus zones.</p>
                                    @endif
                                </div>

                                <div class="pt-4 border-t border-stone-100 grid grid-cols-2 gap-4 text-xs font-medium text-stone-500">
                                    <span class="flex items-center space-x-2"><span>✨</span> <span>{{ $room->base_price <= 50 ? 'Smart Bed' : 'King Bed' }}</span></span>
                                    <span class="flex items-center space-x-2"><span>🌇</span> <span>{{ $room->base_price <= 100 ? 'City View' : 'Ocean View Balcony' }}</span></span>
                                    <span class="flex items-center space-x-2"><span>🚿</span> <span>{{ $room->base_price <= 50 ? 'Premium Shared Bath' : 'Private Luxury Spa' }}</span></span>
                                    <span class="flex items-center space-x-2"><span>📶</span> <span>High-Speed Secure Cloud Connection</span></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </main>

    <footer class="text-stone-400 text-[10px] py-6 text-center border-t border-stone-100">
        Secure Transaction Engine Connection Verified &bull; &copy; 2026 Grand Horizon
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selector = document.getElementById('room-selector');
            const cards = document.querySelectorAll('.room-card');

            function updateVisibleCard() {
                const selectedId = selector.value;
                
                cards.forEach(card => {
                    if (card.getAttribute('data-room-id') === selectedId) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            }

            // Fire on initial page render to capture default sorted top value ($250 Deluxe)
            updateVisibleCard();

            // Fire whenever user shifts selection inputs
            selector.addEventListener('change', updateVisibleCard);
        });
    </script>

</body>
</html>