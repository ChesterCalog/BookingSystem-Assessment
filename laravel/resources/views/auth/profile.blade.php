@extends('layouts.app')
@section('title', 'My Profile & Bookings')

@section('content')
<div class="pt-24 pb-16 max-w-6xl mx-auto px-4">

    <h1 class="font-display text-3xl font-bold text-white mb-8">My Profile</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Profile details --}}
        <div class="lg:col-span-1">
            <div class="glass rounded-2xl p-6 space-y-5">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-white font-semibold text-lg">{{ $user->name }}</p>
                        <p class="text-slate-400 text-sm">{{ $user->email }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('auth.profile.update') }}" class="space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full bg-dark/60 border border-purple-900/50 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-light">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full bg-dark/60 border border-purple-900/50 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-light">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full bg-dark/60 border border-purple-900/50 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-light">
                    </div>

                    <hr class="border-purple-900/30">
                    <p class="text-xs text-slate-500">Leave password fields blank to keep current password.</p>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Current Password</label>
                        <input type="password" name="current_password"
                               class="w-full bg-dark/60 border border-purple-900/50 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-light">
                        @error('current_password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">New Password</label>
                        <input type="password" name="password"
                               class="w-full bg-dark/60 border border-purple-900/50 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-light">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation"
                               class="w-full bg-dark/60 border border-purple-900/50 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-light">
                    </div>

                    <button type="submit"
                            class="w-full bg-primary hover:bg-primary-dark text-white text-sm font-semibold py-2.5 rounded-lg transition">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>

        {{-- Booking history --}}
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-white text-xl">Booking History</h2>
                <a href="{{ route('bookings.create') }}"
                   class="bg-primary text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-primary-dark transition">
                    + New Booking
                </a>
            </div>

            @if($bookings->isEmpty())
            <div class="glass rounded-2xl p-12 text-center">
                <span class="text-5xl opacity-30">📋</span>
                <p class="text-slate-400 mt-4">You don't have any bookings yet.</p>
                <a href="{{ route('bookings.create') }}" class="text-primary-light hover:text-white text-sm mt-2 inline-block">Book a room now →</a>
            </div>
            @else
            <div class="space-y-3">
                @foreach($bookings as $booking)
                <div class="glass rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-primary/20 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-microphone text-primary-light"></i>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm">{{ $booking->room->name }}</p>
                            <p class="text-slate-400 text-xs">{{ $booking->booking_date->format('M d, Y') }} | {{ $booking->start_time }} – {{ $booking->end_time }}</p>
                            <p class="text-slate-500 text-xs">Ref: {{ $booking->reference_number }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 sm:flex-col sm:items-end">
                        <span class="text-accent font-semibold text-sm">₱{{ number_format($booking->total_cost) }}</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($booking->status === 'approved')  bg-emerald-900/50 text-emerald-300
                            @elseif($booking->status === 'pending') bg-yellow-900/50 text-yellow-300
                            @elseif($booking->status === 'rejected') bg-red-900/50 text-red-300
                            @elseif($booking->status === 'completed') bg-blue-900/50 text-blue-300
                            @else bg-slate-700 text-slate-300 @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                        @if(in_array($booking->status, ['pending', 'approved']))
                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Cancel this booking?')">
                            @csrf @method('PATCH')
                            <button class="text-xs text-red-400 hover:text-red-300">Cancel</button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $bookings->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
