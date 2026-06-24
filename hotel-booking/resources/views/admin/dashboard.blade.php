@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')


<h1 class="text-3xl font-serif text-stone-800 mb-1">Dashboard</h1>
<div class="flex items-center justify-between mb-1">
    <p class="text-stone-500 mb-6">Grand Palace Hotel — Overview for {{ now()->format('F Y') }}</p>
    <p class="text-xs text-stone-500">{{ now()->format('D, M j, Y') }}</p>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs tracking-wide text-stone-500 uppercase">Total Bookings</span>
            <span class="w-8 h-8 rounded-lg bg-stone-100 flex items-center justify-center">📅</span>
        </div>
        <div class="text-3xl font-semibold text-stone-800">{{ number_format($totalBookings) }}</div>
        <p class="text-xs text-stone-500 mt-1">+{{ $bookingsThisWeek }} this week</p>
    </div>

    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs tracking-wide text-stone-500 uppercase">Pending Approvals</span>
            <span class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">✅</span>
        </div>
        <div class="text-3xl font-semibold text-stone-800">{{ $pendingApprovals }}</div>
        <p class="text-xs text-stone-500 mt-1">Requires action</p>
    </div>

    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs tracking-wide text-stone-500 uppercase">Available Rooms</span>
            <span class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center">🛏️</span>
        </div>
        <div class="text-3xl font-semibold text-stone-800">{{ number_format($availableRoomsToday) }}</div>
        <p class="text-xs text-stone-500 mt-1">{{ $occupiedToday }} occupied · {{ number_format($totalInventory) }} total inventory</p>
    </div>

    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs tracking-wide text-stone-500 uppercase">Monthly Revenue</span>
            <span class="w-8 h-8 rounded-lg bg-stone-100 flex items-center justify-center">$</span>
        </div>
        <div class="text-3xl font-semibold text-stone-800">${{ number_format($monthlyRevenue, 0) }}</div>
        @if(!is_null($revenueChangePercent))
            <p class="text-xs mt-1 {{ $revenueChangePercent >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                {{ $revenueChangePercent >= 0 ? '+' : '' }}{{ $revenueChangePercent }}% vs last month
            </p>
        @endif
    </div>
</div>

{{-- Recent pending approvals --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    <div class="lg:col-span-2 bg-white rounded-xl border border-stone-200 p-5">
        <h2 class="text-lg font-semibold text-stone-800 mb-4">Recent Pending Approvals</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase text-stone-500 border-b border-stone-200">
                        <th class="py-2 pr-4">Booking ID</th>
                        <th class="py-2 pr-4">Guest</th>
                        <th class="py-2 pr-4">Room</th>
                        <th class="py-2 pr-4">Check-in</th>
                        <th class="py-2 pr-4 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPendingApprovals as $booking)
                        <tr class="border-b border-stone-100">
                            <td class="py-3 pr-4 text-amber-700">BK-{{ $booking->id }}</td>
                            <td class="py-3 pr-4 font-medium text-stone-800">{{ $booking->user->name ?? '—' }}</td>
                            <td class="py-3 pr-4 text-amber-700">{{ $booking->roomType->name ?? '—' }}</td>
                            <td class="py-3 pr-4 text-stone-600">{{ \Carbon\Carbon::parse($booking->check_in)->format('Y-m-d') }}</td>
                            <td class="py-3 pr-4 text-right font-semibold text-stone-800">${{ number_format($booking->total_price, 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-stone-400">No pending approvals.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <h2 class="text-lg font-semibold text-stone-800 mb-4">Room Status Summary</h2>

        @php
            $availablePct = $totalInventory > 0 ? round(($availableRoomsToday / $totalInventory) * 100) : 0;
            $occupiedPct = $totalInventory > 0 ? round(($occupiedToday / $totalInventory) * 100) : 0;
        @endphp

        <div class="mb-4">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-stone-700">Available</span>
                <span class="text-stone-500">{{ $availableRoomsToday }} ({{ $availablePct }}%)</span>
            </div>
            <div class="h-2 bg-stone-100 rounded-full overflow-hidden">
                <div class="h-2 bg-emerald-500 rounded-full" style="width: {{ $availablePct }}%"></div>
            </div>
        </div>

        <div class="mb-4">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-stone-700">Occupied</span>
                <span class="text-stone-500">{{ $occupiedToday }} ({{ $occupiedPct }}%)</span>
            </div>
            <div class="h-2 bg-stone-100 rounded-full overflow-hidden">
                <div class="h-2 bg-amber-500 rounded-full" style="width: {{ $occupiedPct }}%"></div>
            </div>
        </div>

        <div class="pt-3 border-t border-stone-200 flex justify-between text-sm">
            <span class="text-stone-500">Total Inventory</span>
            <span class="font-semibold text-stone-800">{{ number_format($totalInventory) }}</span>
        </div>
    </div>
</div>
@endsection
