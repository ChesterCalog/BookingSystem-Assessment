@extends('layouts.app')
@section('title', 'Booking Confirmed')

@section('content')
<div class="pt-24 pb-16 max-w-2xl mx-auto px-4 text-center">

    {{-- Success icon --}}
    <div class="w-24 h-24 bg-emerald-900/40 border-2 border-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fa-solid fa-circle-check text-emerald-400 text-5xl"></i>
    </div>

    <h1 class="font-display text-3xl font-bold text-white mb-2">Booking Submitted!</h1>
    <p class="text-slate-400 mb-8">
        Your booking request has been received and is <span class="text-yellow-400 font-medium">pending review</span>.
        A confirmation email has been sent to
        <span class="text-white">{{ $type === 'user' ? $booking->user->email : $booking->email }}</span>.
    </p>

    {{-- Booking details card --}}
    <div class="glass rounded-2xl p-6 text-left space-y-4 mb-8">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-white text-lg">Booking Details</h2>
            <span class="bg-yellow-900/40 text-yellow-300 border border-yellow-700/40 text-xs font-semibold px-3 py-1 rounded-full">
                Pending Approval
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Reference Number</p>
                <p class="text-accent font-bold text-base">{{ $booking->reference_number }}</p>
            </div>
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Room</p>
                <p class="text-white font-medium">{{ $booking->room->name }}</p>
            </div>
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Name</p>
                <p class="text-white">{{ $type === 'user' ? $booking->user->name : $booking->full_name }}</p>
            </div>
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Contact</p>
                <p class="text-white">{{ $type === 'user' ? $booking->user->phone ?? '—' : $booking->phone }}</p>
            </div>
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Date</p>
                <p class="text-white">{{ \Carbon\Carbon::parse($booking->booking_date)->format('F d, Y') }}</p>
            </div>
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Time</p>
                <p class="text-white">{{ $booking->start_time }} – {{ $booking->end_time }}</p>
            </div>
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Guests</p>
                <p class="text-white">{{ $booking->num_guests }} pax</p>
            </div>
            <div>
                <p class="text-slate-500 text-xs mb-0.5">Total Cost</p>
                <p class="text-accent font-bold">₱{{ number_format($booking->total_cost, 2) }}</p>
            </div>
        </div>

        @if($booking->special_requests)
        <div class="pt-3 border-t border-purple-900/30">
            <p class="text-slate-500 text-xs mb-1">Special Requests</p>
            <p class="text-slate-300 text-sm">{{ $booking->special_requests }}</p>
        </div>
        @endif
    </div>

    {{-- Info box --}}
    <div class="bg-blue-900/20 border border-blue-700/30 rounded-xl p-4 text-left mb-8">
        <p class="text-blue-300 text-sm font-medium mb-1"><i class="fa-solid fa-circle-info mr-2"></i>What happens next?</p>
        <ul class="text-slate-400 text-sm space-y-1 list-disc pl-5">
            <li>Our team will review your request within 1–2 hours.</li>
            <li>You'll receive an email once your booking is approved or if changes are needed.</li>
            <li>Payment is collected on-site at time of booking.</li>
            <li>Bring a valid ID and your reference number on your visit.</li>
        </ul>
    </div>

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('home') }}"
           class="bg-primary hover:bg-primary-dark text-white font-semibold px-8 py-3 rounded-xl transition">
            Back to Home
        </a>
        <a href="{{ route('rooms.index') }}"
           class="border border-purple-500/50 text-purple-300 hover:text-white font-semibold px-8 py-3 rounded-xl transition">
            Browse More Rooms
        </a>
    </div>
</div>
@endsection
