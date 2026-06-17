@extends('layouts.admin')
@section('title', 'Booking Detail')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.bookings.index') }}" class="text-slate-400 hover:text-white text-sm mb-6 inline-flex items-center gap-1">
        <i class="fa-solid fa-arrow-left"></i> Back to Bookings
    </a>

    <div class="stat-card p-6 space-y-5">

        {{-- Header --}}
        <div class="flex items-start justify-between">
            <div>
                <p class="text-slate-400 text-xs mb-0.5">Reference Number</p>
                <p class="text-accent font-bold text-xl font-mono">{{ $booking->reference_number }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                @if($booking->status === 'approved')  bg-emerald-900/50 text-emerald-300
                @elseif($booking->status === 'pending') bg-yellow-900/50 text-yellow-300
                @elseif($booking->status === 'rejected') bg-red-900/50 text-red-300
                @elseif($booking->status === 'completed') bg-blue-900/50 text-blue-300
                @else bg-slate-700 text-slate-300 @endif">
                {{ ucfirst($booking->status) }}
            </span>
        </div>

        <hr class="border-purple-900/30">

        {{-- Details grid --}}
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Guest Name</p>
                <p class="text-white">{{ $type === 'user' ? $booking->user->name : $booking->full_name }}</p>
            </div>
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Email</p>
                <p class="text-white">{{ $type === 'user' ? $booking->user->email : $booking->email }}</p>
            </div>
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Phone</p>
                <p class="text-white">{{ $type === 'user' ? ($booking->user->phone ?? '—') : $booking->phone }}</p>
            </div>
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Booking Type</p>
                <p class="text-white capitalize">{{ $type }}</p>
            </div>
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Room</p>
                <p class="text-white">{{ $booking->room->name }}</p>
                <p class="text-slate-400 text-xs capitalize">{{ $booking->room->type }} / {{ $booking->room->size }}</p>
            </div>
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Guests</p>
                <p class="text-white">{{ $booking->num_guests }} pax</p>
            </div>
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Date</p>
                <p class="text-white">{{ \Carbon\Carbon::parse($booking->booking_date)->format('F d, Y') }}</p>
            </div>
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Time</p>
                <p class="text-white">{{ $booking->start_time }} – {{ $booking->end_time }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-slate-500 text-xs mb-0.5">Total Cost</p>
                <p class="text-accent font-bold text-2xl">₱{{ number_format($booking->total_cost, 2) }}</p>
            </div>
        </div>

        @if($booking->special_requests)
        <div class="bg-dark/40 rounded-xl p-4">
            <p class="text-slate-500 text-xs mb-1">Special Requests</p>
            <p class="text-slate-200 text-sm">{{ $booking->special_requests }}</p>
        </div>
        @endif

        <hr class="border-purple-900/30">

        {{-- Admin actions --}}
        @if($booking->status === 'pending')
        <div class="flex gap-3">
            <form method="POST" action="{{ route('admin.bookings.approve', [$type, $booking->id]) }}" class="flex-1">
                @csrf @method('PATCH')
                <button class="w-full bg-emerald-700 hover:bg-emerald-600 text-white font-semibold py-2.5 rounded-xl transition">
                    <i class="fa-solid fa-check mr-1"></i> Approve Booking
                </button>
            </form>
            <form method="POST" action="{{ route('admin.bookings.reject', [$type, $booking->id]) }}" class="flex-1">
                @csrf @method('PATCH')
                <button class="w-full bg-red-800 hover:bg-red-700 text-white font-semibold py-2.5 rounded-xl transition">
                    <i class="fa-solid fa-xmark mr-1"></i> Reject Booking
                </button>
            </form>
        </div>
        @elseif($booking->status === 'approved')
        <form method="POST" action="{{ route('admin.bookings.complete', [$type, $booking->id]) }}">
            @csrf @method('PATCH')
            <button class="w-full bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2.5 rounded-xl transition">
                <i class="fa-solid fa-flag-checkered mr-1"></i> Mark as Completed
            </button>
        </form>
        @endif

        <p class="text-slate-500 text-xs text-right">Booked on {{ $booking->created_at->format('M d, Y g:i A') }}</p>
    </div>
</div>
@endsection
