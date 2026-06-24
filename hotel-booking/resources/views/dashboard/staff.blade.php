<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Administration Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 min-h-screen p-6 text-white">

    <div class="max-w-7xl mx-auto space-y-8">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-amber-900/50 text-amber-500 rounded-full flex items-center justify-center text-xl border border-amber-800">
                    🛡️
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Staff Admin Portal</h1>
                    <p class="text-slate-400 text-sm">Logged in as {{ auth()->user()->name }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-amber-700 text-white rounded-lg px-4 py-2 text-sm font-semibold hover:bg-amber-600 transition">
                    End Shift (Log Out)
                </button>
            </form>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-emerald-800/50 border border-emerald-600 text-emerald-300 px-5 py-3 rounded-xl text-sm font-medium">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-800/50 border border-rose-600 text-rose-300 px-5 py-3 rounded-xl text-sm font-medium">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        {{-- Stats Row --}}
        @php
            $pending   = App\Models\Booking::where('status', 'pending')->count();
            $confirmed = App\Models\Booking::where('status', 'confirmed')->count();
            $rejected  = App\Models\Booking::where('status', 'rejected')->count();
        @endphp
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-slate-800 border border-yellow-700/50 rounded-2xl p-5 text-center">
                <p class="text-3xl font-bold text-yellow-400">{{ $pending }}</p>
                <p class="text-slate-400 text-xs mt-1 uppercase tracking-widest font-bold">Pending</p>
            </div>
            <div class="bg-slate-800 border border-emerald-700/50 rounded-2xl p-5 text-center">
                <p class="text-3xl font-bold text-emerald-400">{{ $confirmed }}</p>
                <p class="text-slate-400 text-xs mt-1 uppercase tracking-widest font-bold">Confirmed</p>
            </div>
            <div class="bg-slate-800 border border-rose-700/50 rounded-2xl p-5 text-center">
                <p class="text-3xl font-bold text-rose-400">{{ $rejected }}</p>
                <p class="text-slate-400 text-xs mt-1 uppercase tracking-widest font-bold">Rejected</p>
            </div>
        </div>

        {{-- Pending Bookings Table --}}
        <div class="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-700 flex items-center justify-between">
                <h2 class="text-sm font-bold tracking-widest uppercase text-slate-300">🕐 Pending Reservations</h2>
                <span class="bg-yellow-500/20 text-yellow-400 text-xs font-bold px-3 py-1 rounded-full border border-yellow-600/30">
                    {{ $pending }} awaiting action
                </span>
            </div>

            @php
                $pendingBookings = App\Models\Booking::with(['user', 'roomType'])
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->get();
            @endphp

            @if($pendingBookings->isEmpty())
                <div class="px-6 py-12 text-center text-slate-500 text-sm">
                    No pending reservations at this time.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-900/50 text-xs font-bold tracking-widest uppercase text-slate-400">
                            <tr>
                                <th class="px-6 py-3 text-left">Guest</th>
                                <th class="px-6 py-3 text-left">Room</th>
                                <th class="px-6 py-3 text-left">Check-In</th>
                                <th class="px-6 py-3 text-left">Check-Out</th>
                                <th class="px-6 py-3 text-left">Total</th>
                                <th class="px-6 py-3 text-left">Submitted</th>
                                <th class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($pendingBookings as $booking)
                                <tr class="hover:bg-slate-700/30 transition">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-white">{{ $booking->user->name }}</p>
                                        <p class="text-slate-400 text-xs">{{ $booking->user->email }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-slate-300">{{ $booking->roomType->name }}</td>
                                    <td class="px-6 py-4 text-slate-300">{{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-slate-300">{{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-amber-400 font-bold">${{ number_format($booking->total_price, 2) }}</td>
                                    <td class="px-6 py-4 text-slate-400 text-xs">{{ $booking->created_at->diffForHumans() }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <form method="POST" action="{{ route('bookings.approve', $booking) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold px-4 py-2 rounded-lg transition">
                                                    ✓ Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('bookings.reject', $booking) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-rose-800 hover:bg-rose-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition">
                                                    ✕ Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- All Bookings Table --}}
        <div class="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-700">
                <h2 class="text-sm font-bold tracking-widest uppercase text-slate-300">📋 All Reservations</h2>
            </div>

            @php
                $allBookings = App\Models\Booking::with(['user', 'roomType'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            @endphp

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-900/50 text-xs font-bold tracking-widest uppercase text-slate-400">
                        <tr>
                            <th class="px-6 py-3 text-left">#</th>
                            <th class="px-6 py-3 text-left">Guest</th>
                            <th class="px-6 py-3 text-left">Room</th>
                            <th class="px-6 py-3 text-left">Check-In</th>
                            <th class="px-6 py-3 text-left">Check-Out</th>
                            <th class="px-6 py-3 text-left">Total</th>
                            <th class="px-6 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @forelse($allBookings as $booking)
                            <tr class="hover:bg-slate-700/30 transition">
                                <td class="px-6 py-4 text-slate-500 text-xs">#{{ $booking->id }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-white">{{ $booking->user->name }}</p>
                                    <p class="text-slate-400 text-xs">{{ $booking->user->email }}</p>
                                </td>
                                <td class="px-6 py-4 text-slate-300">{{ $booking->roomType->name }}</td>
                                <td class="px-6 py-4 text-slate-300">{{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-slate-300">{{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-amber-400 font-bold">${{ number_format($booking->total_price, 2) }}</td>
                                <td class="px-6 py-4">
                                    @if($booking->status === 'pending')
                                        <span class="bg-yellow-500/20 text-yellow-400 text-xs font-bold px-3 py-1 rounded-full border border-yellow-600/30">Pending</span>
                                    @elseif($booking->status === 'confirmed')
                                        <span class="bg-emerald-500/20 text-emerald-400 text-xs font-bold px-3 py-1 rounded-full border border-emerald-600/30">Confirmed</span>
                                    @elseif($booking->status === 'rejected')
                                        <span class="bg-rose-500/20 text-rose-400 text-xs font-bold px-3 py-1 rounded-full border border-rose-600/30">Rejected</span>
                                    @else
                                        <span class="text-slate-500 text-xs">{{ $booking->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500 text-sm">No bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>