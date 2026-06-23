@extends('layouts.admin')

@section('title', 'Audit Logs')

@section('content')
<div class="flex items-center justify-between mb-1">
    <p class="text-xs text-stone-500">Audit Logs</p>
    <p class="text-xs text-stone-500">{{ now()->format('D, M j, Y') }}</p>
</div>

<h1 class="text-3xl font-serif text-stone-800 mb-1">Audit Logs</h1>
<p class="text-stone-500 mb-6">System changes by staff and customer booking activity</p>

<div class="bg-white rounded-xl border border-stone-200 p-5">

    <div class="flex items-center justify-between mb-4">
        <div class="flex gap-2">
            <a href="{{ route('admin.audit-logs', ['filter' => 'all']) }}"
               class="px-3 py-1.5 rounded-md text-sm {{ $filter === 'all' ? 'bg-stone-800 text-white' : 'border border-stone-300 text-stone-600 hover:bg-stone-50' }}">
                All Changes
            </a>
            <a href="{{ route('admin.audit-logs', ['filter' => 'staff']) }}"
               class="px-3 py-1.5 rounded-md text-sm {{ $filter === 'staff' ? 'bg-stone-800 text-white' : 'border border-stone-300 text-stone-600 hover:bg-stone-50' }}">
                Staff Actions
            </a>
            <a href="{{ route('admin.audit-logs', ['filter' => 'customer']) }}"
               class="px-3 py-1.5 rounded-md text-sm {{ $filter === 'customer' ? 'bg-stone-800 text-white' : 'border border-stone-300 text-stone-600 hover:bg-stone-50' }}">
                Customer Activity
            </a>
        </div>
        <span class="text-xs text-stone-500">{{ $totalEntries }} entries</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase text-stone-500 border-b border-stone-200">
                    <th class="py-2 pr-4">Timestamp</th>
                    <th class="py-2 pr-4">Actor</th>
                    <th class="py-2 pr-4">Name</th>
                    <th class="py-2 pr-4">Action</th>
                    <th class="py-2 pr-4">Target</th>
                    <th class="py-2 pr-4">Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    @php
                        $actionColors = [
                            'Booking Approved' => 'text-emerald-600',
                            'Booking Submitted' => 'text-blue-600',
                            'Booking Rejected' => 'text-red-600',
                            'Booking Cancelled' => 'text-red-600',
                            'Booking Modified' => 'text-amber-600',
                            'Role Changed' => 'text-purple-600',
                            'Room Status Changed' => 'text-amber-600',
                            'Account Deactivated' => 'text-red-600',
                        ];
                        $colorClass = $actionColors[$log->action] ?? 'text-stone-700';
                    @endphp
                    <tr class="border-b border-stone-100">
                        <td class="py-3 pr-4 text-stone-600 whitespace-nowrap">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="py-3 pr-4">
                            <span class="px-2 py-0.5 rounded-md text-xs border
                                {{ $log->actor_type === 'staff' ? 'bg-stone-100 text-stone-600 border-stone-200' : 'bg-pink-50 text-pink-600 border-pink-200' }}">
                                {{ ucfirst($log->actor_type) }}
                            </span>
                        </td>
                        <td class="py-3 pr-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-stone-200 text-stone-600 text-[10px] flex items-center justify-center font-semibold">
                                {{ strtoupper(substr($log->user->name ?? '?', 0, 2)) }}
                            </span>
                            <span class="font-medium text-stone-800">{{ $log->user->name ?? 'Unknown' }}</span>
                        </td>
                        <td class="py-3 pr-4 font-medium {{ $colorClass }}">{{ $log->action }}</td>
                        <td class="py-3 pr-4 text-amber-700">{{ $log->target ?? '—' }}</td>
                        <td class="py-3 pr-4 text-stone-600">{{ $log->details ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-stone-400">No audit log entries yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
