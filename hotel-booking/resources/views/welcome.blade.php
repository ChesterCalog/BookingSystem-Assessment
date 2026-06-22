<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grand Horizon Resort</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#fcfbf7] min-h-screen text-slate-800 font-sans">

    <nav class="bg-white border-b border-stone-200 px-8 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center space-x-2">
            <span class="text-2xl">👑</span>
            <span class="text-xl font-serif font-semibold tracking-wide text-amber-900">Grand Horizon Resort</span>
        </div>
        <div class="space-x-4 text-sm font-medium">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-stone-600 hover:text-amber-700 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-stone-600 hover:text-amber-700 transition">Staff Portal</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-amber-800 text-white px-4 py-2 rounded-lg hover:bg-amber-900 transition shadow-sm">Join Membership</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-8 space-y-8">
        
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg text-emerald-800 font-medium shadow-sm animate-pulse">
                ✨ {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-5 h-fit">
                <div>
                    <h2 class="text-xl font-serif font-bold text-stone-800">Instant Reservation</h2>
                    <p class="text-xs text-stone-500 mt-1">No account creation required. Simply provide details below.</p>
                </div>
                
                <form action="{{ route('booking.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="space-y-3 bg-stone-50 p-4 rounded-xl border border-stone-100">
                        <span class="text-xs font-bold tracking-wider text-stone-400 uppercase">Guest Information</span>
                        <div>
                            <label class="block text-xs font-semibold text-stone-600 mb-1">Full Name</label>
                            <input type="text" name="guest_name" required placeholder="John Doe" class="w-full p-2.5 bg-white border border-stone-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-600 mb-1">Email Address</label>
                            <input type="email" name="guest_email" required placeholder="john@example.com" class="w-full p-2.5 bg-white border border-stone-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-600 mb-1">Contact Number</label>
                            <input type="text" name="guest_phone" required placeholder="+63 912 345 6789" class="w-full p-2.5 bg-white border border-stone-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-600">
                        </div>
                    </div>

                    <div class="space-y-3 p-1">
                        <span class="text-xs font-bold tracking-wider text-stone-400 uppercase">Stay Preferences</span>
                        <div>
                            <label class="block text-xs font-semibold text-stone-600 mb-1">Accomodation Option</label>
                            <select name="room_type_id" class="w-full p-2.5 border border-stone-200 bg-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-600">
                                @foreach($roomTypes as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }} (${{ number_format($room->base_price, 2) }} / night)</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-stone-600 mb-1">Check-In</label>
                                <input type="date" name="check_in" required class="w-full p-2.5 border border-stone-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-600">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-stone-600 mb-1">Check-Out</label>
                                <input type="date" name="check_out" required class="w-full p-2.5 border border-stone-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-600">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-amber-800 text-white p-3 rounded-xl font-bold tracking-wide text-sm hover:bg-amber-900 transition shadow-md">
                        Confirm Booking
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm lg:col-span-2">
                <h2 class="text-xl font-serif font-bold text-stone-800 mb-4">📋 Dynamic Reservations Log</h2>
                
                @if($bookings->isEmpty())
                    <p class="text-stone-400 italic text-sm">No reservations tracked yet. Use the system form to process bookings.</p>
                @else
                    <div class="overflow-x-auto rounded-xl border border-stone-100">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-stone-50 border-b border-stone-200 text-stone-500 font-semibold">
                                    <th class="p-3">ID</th>
                                    <th class="p-3">Primary Guest</th>
                                    <th class="p-3">Room Type</th>
                                    <th class="p-3">Timeline</th>
                                    <th class="p-3">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                    <tr class="border-b border-stone-100 hover:bg-stone-50/50 transition text-stone-700">
                                        <td class="p-3 font-mono text-xs text-stone-400">#{{ $booking->id }}</td>
                                        <td class="p-3">
                                            <div class="font-semibold text-stone-900">{{ $booking->user->name }}</div>
                                            <div class="text-xs text-stone-400">{{ $booking->user->phone ?? $booking->user->email }}</div>
                                        </td>
                                        <td class="p-3 font-medium">{{ $booking->roomType->name }}</td>
                                        <td class="p-3 text-xs space-y-0.5">
                                            <div><span class="text-stone-400">In:</span> {{ $booking->check_in }}</div>
                                            <div><span class="text-stone-400">Out:</span> {{ $booking->check_out }}</div>
                                        </td>
                                        <td class="p-3 font-bold text-amber-800">${{ number_format($booking->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

</body>
</html>