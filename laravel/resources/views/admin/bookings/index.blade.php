@extends('layouts.admin')
@section('title', 'Manage Bookings')

@section('content')

{{-- Search & Filter bar --}}
<form method="GET" action="{{ route('admin.bookings.index') }}"
      class="stat-card p-4 mb-6 grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">

    <div>
        <label class="block text-xs text-slate-400 mb-1">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, or ref…"
               class="w-full bg-dark/60 border border-purple-900/50 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-light placeholder-slate-600" />
    </div>

    <div>
        <label class="block text-xs text-slate-400 mb-1">Status</label>
        <select name="status"
                class="w-full bg-dark/60 border border-purple-900/50 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-light">
            <option value="">All Statuses</option>
            @foreach(['pending','approved','rejected','completed','cancelled'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs text-slate-400 mb-1">Date</label>
        <input type="date" name="date" value="{{ request('date') }}"
               class="w-full bg-dark/60 border border-purple-900/50 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-light" />
    </div>

    <div class="flex gap-2">
        <button type="submit"
                class="flex-1 bg-primary hover:bg-primary-dark text-white text-sm font-semibold py-2 rounded-lg transition">
            <i class="fa-solid fa-filter mr-1"></i> Filter
        </button>
        <a href="{{ route('admin.bookings.index') }}"
           class="px-3 py-2 border border-purple-700 text-slate-400 hover:text-white rounded-lg transition text-sm">
            <i class="fa-solid fa-xmark"></i>
        </a>
    </div>
</form>

{{-- Bookings table --}}
<div class="stat-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-slate-500 text-xs uppercase border-b border-purple-900/30">
                    <th class="text-left p-4">Ref #</th>
                    <th class="text-left p-4">Type</th>
                    <th class="text-left p-4">Name / Email</th>
                    <th class="text-left p-4">Room</th>
                    <th class="text-left p-4">Date & Time</th>
                    <th class="text-right p-4">Cost</th>
                    <th class="text-left p-4">Status</th>
                    <th class="text-left p-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-purple-900/20">
                @forelse($bookings as $b)
                @php
                    $type = $b['booking_category'];
                    $id   = $b['id'];
                @endphp
                <tr class="hover:bg-purple-900/10 transition">
                    <td class="p-4 font-mono text-accent text-xs">{{ $b['reference_number'] }}</td>
                    <td class="p-4">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $type === 'user' ? 'bg-blue-900/40 text-blue-300' : 'bg-slate-700 text-slate-300' }}">
                            {{ ucfirst($type) }}
                        </span>
                    </td>
                    <td class="p-4">
                        <p class="text-white">{{ $type === 'user' ? ($b['user']['name'] ?? '—') : $b['full_name'] }}</p>
                        <p class="text-slate-500 text-xs">{{ $b['email'] ?? ($b['user']['email'] ?? '—') }}</p>
                    </td>
                    <td class="p-4 text-slate-300">{{ $b['room']['name'] ?? '—' }}</td>
                    <td class="p-4">
                        <p class="text-white">{{ \Carbon\Carbon::parse($b['booking_date'])->format('M d, Y') }}</p>
                        <p class="text-slate-400 text-xs">{{ $b['start_time'] }} – {{ $b['end_time'] }}</p>
                    </td>
                    <td class="p-4 text-right text-accent font-medium">₱{{ number_format($b['total_cost']) }}</td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            @if($b['status'] === 'approved')  bg-emerald-900/50 text-emerald-300
                            @elseif($b['status'] === 'pending') bg-yellow-900/50 text-yellow-300
                            @elseif($b['status'] === 'rejected') bg-red-900/50 text-red-300
                            @elseif($b['status'] === 'completed') bg-blue-900/50 text-blue-300
                            @else bg-slate-700 text-slate-300 @endif">
                            {{ ucfirst($b['status']) }}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.bookings.show', [$type, $id]) }}"
                               class="bg-slate-700 hover:bg-slate-600 text-white text-xs px-2.5 py-1.5 rounded-lg transition" title="View">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if($b['status'] === 'pending')
                            <form method="POST" action="{{ route('admin.bookings.approve', [$type, $id]) }}">
                                @csrf @method('PATCH')
                                <button class="bg-emerald-900/50 hover:bg-emerald-700 text-emerald-300 hover:text-white text-xs px-2.5 py-1.5 rounded-lg transition" title="Approve">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.bookings.reject', [$type, $id]) }}">
                                @csrf @method('PATCH')
                                <button class="bg-red-900/50 hover:bg-red-700 text-red-300 hover:text-white text-xs px-2.5 py-1.5 rounded-lg transition" title="Reject">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                            @elseif($b['status'] === 'approved')
                            <form method="POST" action="{{ route('admin.bookings.complete', [$type, $id]) }}">
                                @csrf @method('PATCH')
                                <button class="bg-blue-900/50 hover:bg-blue-700 text-blue-300 hover:text-white text-xs px-2.5 py-1.5 rounded-lg transition" title="Mark Complete">
                                    <i class="fa-solid fa-flag-checkered"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-10 text-center text-slate-500">No bookings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
