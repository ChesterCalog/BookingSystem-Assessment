@extends('layouts.staff')

@section('title', 'Maintenance')

@section('content')
<h1 class="text-3xl font-serif text-stone-800 mb-1">Maintenance</h1>
<div class="flex items-center justify-between mb-1">
    <p class="text-stone-500 mb-6">Room status tracking for rooms 101 to 105</p>
    <p class="text-xs text-stone-500">{{ now()->format('D, M j, Y') }}</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <div class="text-xs uppercase text-stone-500 mb-2">Available Rooms</div>
        <div class="text-3xl font-semibold text-stone-800">{{ number_format($availableRooms) }}</div>
    </div>

    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <div class="text-xs uppercase text-stone-500 mb-2">Occupied Rooms</div>
        <div class="text-3xl font-semibold text-stone-800">{{ number_format($occupiedRooms) }}</div>
    </div>
</div>

<div class="bg-white rounded-xl border border-stone-200 p-5">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase text-stone-500 border-b border-stone-200">
                    <th class="py-2 pr-4">Room</th>
                    <th class="py-2 pr-4">Room Type</th>
                    <th class="py-2 pr-4">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roomUnits as $roomUnit)
                    <tr class="border-b border-stone-100">
                        <td class="py-3 pr-4 font-medium text-stone-800">{{ $roomUnit->room_number }}</td>
                        <td class="py-3 pr-4 text-stone-600">{{ $roomUnit->roomType->name ?? '—' }}</td>
                        <td class="py-3 pr-4">
                            <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs {{ $roomUnit->status === 'occupied' ? 'border-amber-200 bg-amber-50 text-amber-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }}">
                                {{ $roomUnit->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-6 text-center text-stone-400">No room units found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection