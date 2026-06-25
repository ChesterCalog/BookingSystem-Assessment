@php
    $layout = Auth::user()->isAdmin() ? 'layouts.admin' : 'layouts.staff';
@endphp

@extends($layout)

@section('title', 'Approvals')

@section('content')
<h1 class="text-3xl font-serif text-stone-800 mb-1">Approvals</h1>
<div class="flex items-center justify-between mb-1">
    <p class="text-stone-500 mb-6">Review and manage booking ticket submissions</p>
    <p class="text-xs text-stone-500">{{ now()->format('D, M j, Y') }}</p>
</div>

<div class="bg-white rounded-xl border border-stone-200 p-5">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase text-stone-500 border-b border-stone-200">
                    <th class="py-2 pr-4">User ID</th>
                    <th class="py-2 pr-4">Room Type ID</th>
                    <th class="py-2 pr-4">Check-In</th>
                    <th class="py-2 pr-4">Check-out</th>
                    <th class="py-2 pr-4 text-right">Price</th>
                    <th class="py-2 pr-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingBookings as $booking)
                    <tr class="border-b border-stone-100">
                        <td class="py-3 pr-4 font-medium text-stone-800">{{ $booking->user_id }}</td>
                        <td class="py-3 pr-4 text-stone-700">{{ $booking->room_type_id }}</td>
                        <td class="py-3 pr-4 text-stone-600">{{ $booking->check_in->format('Y-m-d') }}</td>
                        <td class="py-3 pr-4 text-stone-600">{{ $booking->check_out->format('Y-m-d') }}</td>
                        <td class="py-3 pr-4 text-right font-semibold text-stone-800">${{ number_format($booking->total_price, 0) }}</td>
                        <td class="py-3 pr-4 text-right whitespace-nowrap">
                            <form action="{{ route('staff.bookings.approve', $booking) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="inline-flex items-center rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                                    ✓ Accept
                                </button>
                            </form>
                            <form action="{{ route('staff.bookings.reject', $booking) }}" method="POST" class="inline-block ml-2">
                                @csrf
                                <button type="submit" class="inline-flex items-center rounded-md bg-red-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-600">
                                    × Reject
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-stone-400">No pending bookings.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection