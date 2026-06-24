<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Your Stay | Grand Horizon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:wght@200..800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FAF9F5] text-stone-800 antialiased min-h-screen flex flex-col">

    <nav class="bg-white border-b border-stone-200 px-6 py-4 flex justify-between items-center shadow-sm">
        <a href="{{ route('home') }}" class="flex items-center space-x-2">
            <span class="text-2xl text-amber-700">✨</span>
            <span class="serif text-xl font-bold tracking-widest uppercase text-stone-900">Grand Horizon</span>
        </a>
        <a href="{{ route('home') }}" class="text-xs font-bold tracking-widest uppercase text-stone-500 hover:text-stone-900 transition">
            ← Cancel & Return
        </a>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        <div class="lg:col-span-7 bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-stone-200/60">
            <h1 class="serif text-3xl font-bold text-stone-900 mb-2">Secure Your Reservation</h1>
            <p class="text-sm text-stone-500 font-light mb-8">Please select your preferred dates and accommodation tier.</p>

            <form action="#" method="POST" class="space-y-8">
                @csrf

                @auth
                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-5 flex items-center space-x-4">
                        <div class="h-10 w-10 bg-amber-800 text-white rounded-full flex items-center justify-center font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-stone-900">Booking as {{ auth()->user()->name }}</p>
                        </div>
                    </div>
                @endauth

                @guest
                    <div class="space-y-6">
                        <div class="pb-4 border-b border-stone-100">
                            <h3 class="text-xs font-bold tracking-widest uppercase text-stone-800 mb-4">Guest Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold tracking-widest uppercase text-stone-600 mb-2">Full Name</label>
                                    <input type="text" required class="block w-full rounded-xl border-0 py-3 text-stone-900 shadow-sm ring-1 ring-inset ring-stone-300 focus:ring-2 focus:ring-inset focus:ring-amber-800 sm:text-sm px-4">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold tracking-widest uppercase text-stone-600 mb-2">Email Address</label>
                                    <input type="email" required class="block w-full rounded-xl border-0 py-3 text-stone-900 shadow-sm ring-1 ring-inset ring-stone-300 focus:ring-2 focus:ring-inset focus:ring-amber-800 sm:text-sm px-4">
                                </div>
                            </div>
                        </div>
                    </div>
                @endguest

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold tracking-widest uppercase text-stone-600 mb-2">Check-in Date</label>
                        <input type="date" required class="block w-full rounded-xl border-0 py-3 text-stone-900 shadow-sm ring-1 ring-inset ring-stone-300 focus:ring-2 focus:ring-inset focus:ring-amber-800 sm:text-sm px-4">
                    </div>
                    <div>
                        <label class="block text-xs font-bold tracking-widest uppercase text-stone-600 mb-2">Check-out Date</label>
                        <input type="date" required class="block w-full rounded-xl border-0 py-3 text-stone-900 shadow-sm ring-1 ring-inset ring-stone-300 focus:ring-2 focus:ring-inset focus:ring-amber-800 sm:text-sm px-4">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-widest uppercase text-stone-600 mb-2">Select Accommodation</label>
                    <select id="room_select" name="room_id" required class="block w-full rounded-xl border-0 py-3.5 text-stone-900 shadow-sm ring-1 ring-inset ring-stone-300 focus:ring-2 focus:ring-inset focus:ring-amber-800 sm:text-sm px-4 bg-white cursor-pointer">
                        <option value="" disabled selected>-- Choose a room --</option>
                        @foreach($rooms as $room)
                    
                    <option value="{{ data_get($room, 'id') }}">
                        {{ data_get($room, 'name', 'Unnamed Room') }} - ${{ number_format(data_get($room, 'base_price', 0), 2) }}/night
    </option>
@endforeach
                    </select>
                </div>

                <button type="submit" class="w-full bg-stone-900 text-white py-4 rounded-xl text-xs font-bold tracking-widest uppercase hover:bg-amber-800 transition shadow-lg mt-4">
                    Confirm Reservation
                </button>
            </form>
        </div>

        <div class="lg:col-span-5 sticky top-6 hidden lg:block">
            <div id="room_preview_card" class="bg-white rounded-3xl shadow-md border border-stone-200/80 overflow-hidden transition-opacity duration-300 opacity-50">
                <div class="aspect-[4/3] bg-stone-200 relative">
                    <img id="preview_image" src="" alt="Room preview" class="w-full h-full object-cover filter grayscale transition duration-500">
                    <div id="preview_price_tag" class="absolute top-4 right-4 bg-stone-900 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-md hidden"></div>
                </div>
                <div class="p-8 space-y-6">
                    <div>
                        <div class="flex items-center space-x-2 text-xs font-bold tracking-widest uppercase text-amber-700 mb-2">
                            <span>📐 <span id="preview_size">-- sq. ft.</span></span>
                        </div>
                        <h3 id="preview_name" class="serif text-2xl font-bold text-stone-900">Select a Room</h3>
                        <p id="preview_desc" class="text-sm text-stone-500 mt-3 font-light leading-relaxed">Choose an accommodation from the dropdown menu.</p>
                    </div>
                    <div class="pt-4 border-t border-stone-100">
                        <h4 class="text-xs font-bold tracking-widest uppercase text-stone-800 mb-3">Included Amenities</h4>
                        <ul id="preview_amenities" class="grid grid-cols-2 gap-y-2 gap-x-4 text-xs text-stone-600 font-light list-disc pl-4">
                            <li>Waiting for selection...</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Converts the Laravel Database Collection to a JS Array
            const roomsData = @json($rooms);
            
            const selectElement = document.getElementById('room_select');
            const previewCard = document.getElementById('room_preview_card');
            const pImage = document.getElementById('preview_image');
            const pName = document.getElementById('preview_name');
            const pPriceTag = document.getElementById('preview_price_tag');
            const pSize = document.getElementById('preview_size');
            const pDesc = document.getElementById('preview_desc');
            const pAmenities = document.getElementById('preview_amenities');

            selectElement.addEventListener('change', function() {
                const selectedId = parseInt(this.value);
                const room = roomsData.find(r => r.id === selectedId);

                if (room) {
                    previewCard.classList.remove('opacity-50');
                    pImage.classList.remove('grayscale');

                    pImage.src = room.image;
                    pName.textContent = room.name;
                    pSize.textContent = room.size;
                    pDesc.textContent = room.description;
                    pPriceTag.textContent = '$' + parseFloat(room.base_price).toFixed(2) + ' / Night';
                    pPriceTag.classList.remove('hidden');

                    pAmenities.innerHTML = ''; 
                    // Room.amenities is now an array because of the model $casts
                    if (Array.isArray(room.amenities)) {
                        room.amenities.forEach(amenity => {
                            let li = document.createElement('li');
                            li.textContent = amenity;
                            pAmenities.appendChild(li);
                        });
                    }
                }
            });
        });
    </script>
</body>
</html>