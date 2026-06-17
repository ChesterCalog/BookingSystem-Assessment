@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- ── Stat Cards ── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-8">

    @php
    $stats = [
        ['label' => 'Total Bookings', 'value' => number_format($totalBookings),   'icon' => 'fa-calendar-check',  'color' => 'text-purple-400',  'bg' => 'bg-purple-900/30'],
        ['label' => 'Pending',        'value' => number_format($pendingBookings),  'icon' => 'fa-clock',           'color' => 'text-yellow-400',  'bg' => 'bg-yellow-900/30'],
        ['label' => 'Total Users',    'value' => number_format($totalUsers),       'icon' => 'fa-users',           'color' => 'text-blue-400',    'bg' => 'bg-blue-900/30'],
        ['label' => 'Total Rooms',    'value' => number_format($totalRooms),       'icon' => 'fa-door-open',       'color' => 'text-emerald-400', 'bg' => 'bg-emerald-900/30'],
        ['label' => 'Revenue',        'value' => '₱'.number_format($totalRevenue), 'icon' => 'fa-peso-sign',       'color' => 'text-accent',      'bg' => 'bg-yellow-900/20'],
    ];
    @endphp

    @foreach($stats as $stat)
    <div class="stat-card p-5 flex items-center gap-4">
        <div class="w-12 h-12 {{ $stat['bg'] }} rounded-xl flex items-center justify-center shrink-0">
            <i class="fa-solid {{ $stat['icon'] }} {{ $stat['color'] }} text-xl"></i>
        </div>
        <div>
            <p class="text-slate-400 text-xs">{{ $stat['label'] }}</p>
            <p class="text-white font-bold text-2xl">{{ $stat['value'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Revenue Chart + Recent Bookings ── --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

    {{-- Revenue Chart --}}
    <div class="stat-card p-6 xl:col-span-2">
        <h2 class="font-semibold text-white mb-4">Monthly Revenue (Last 6 Months)</h2>
        <div class="relative h-52">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="stat-card p-6">
        <h2 class="font-semibold text-white mb-4">Quick Actions</h2>
        <div class="space-y-3">
            <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}"
               class="flex items-center gap-3 bg-yellow-900/20 hover:bg-yellow-900/40 border border-yellow-800/30 rounded-xl p-3 transition group">
                <i class="fa-solid fa-clock text-yellow-400"></i>
                <div>
                    <p class="text-white text-sm font-medium">Pending Approvals</p>
                    <p class="text-yellow-400 text-xs">{{ $pendingBookings }} awaiting review</p>
                </div>
                <i class="fa-solid fa-arrow-right text-slate-600 group-hover:text-white ml-auto transition"></i>
            </a>
            <a href="{{ route('admin.rooms.create') }}"
               class="flex items-center gap-3 bg-purple-900/20 hover:bg-purple-900/40 border border-purple-800/30 rounded-xl p-3 transition group">
                <i class="fa-solid fa-plus text-purple-400"></i>
                <div>
                    <p class="text-white text-sm font-medium">Add New Room</p>
                    <p class="text-purple-400 text-xs">Expand your inventory</p>
                </div>
                <i class="fa-solid fa-arrow-right text-slate-600 group-hover:text-white ml-auto transition"></i>
            </a>
            <a href="{{ route('admin.bookings.calendar') }}"
               class="flex items-center gap-3 bg-blue-900/20 hover:bg-blue-900/40 border border-blue-800/30 rounded-xl p-3 transition group">
                <i class="fa-solid fa-calendar-days text-blue-400"></i>
                <div>
                    <p class="text-white text-sm font-medium">View Calendar</p>
                    <p class="text-blue-400 text-xs">See all bookings at a glance</p>
                </div>
                <i class="fa-solid fa-arrow-right text-slate-600 group-hover:text-white ml-auto transition"></i>
            </a>
        </div>
    </div>
</div>

{{-- ── Recent Bookings ── --}}
<div class="stat-card p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold text-white">Recent Bookings</h2>
        <a href="{{ route('admin.bookings.index') }}" class="text-primary-light text-sm hover:text-white">View all →</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-slate-500 text-xs uppercase border-b border-purple-900/30">
                    <th class="text-left py-2 pr-4">Reference</th>
                    <th class="text-left py-2 pr-4">Guest Name</th>
                    <th class="text-left py-2 pr-4">Room</th>
                    <th class="text-left py-2 pr-4">Date</th>
                    <th class="text-right py-2 pr-4">Cost</th>
                    <th class="text-left py-2">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-purple-900/20">
                @forelse($recentBookings as $b)
                <tr class="hover:bg-purple-900/10 transition">
                    <td class="py-3 pr-4 font-mono text-accent text-xs">{{ $b['ref'] }}</td>
                    <td class="py-3 pr-4 text-white">{{ $b['name'] }}</td>
                    <td class="py-3 pr-4 text-slate-300">{{ $b['room'] }}</td>
                    <td class="py-3 pr-4 text-slate-400">{{ $b['date'] }}</td>
                    <td class="py-3 pr-4 text-right text-white font-medium">₱{{ number_format($b['cost']) }}</td>
                    <td class="py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            @if($b['status'] === 'approved')  bg-emerald-900/50 text-emerald-300
                            @elseif($b['status'] === 'pending') bg-yellow-900/50 text-yellow-300
                            @elseif($b['status'] === 'rejected') bg-red-900/50 text-red-300
                            @elseif($b['status'] === 'completed') bg-blue-900/50 text-blue-300
                            @else bg-slate-700 text-slate-300 @endif">
                            {{ ucfirst($b['status']) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">No bookings yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels  = @json(array_column($monthlyRevenue, 'month'));
const data    = @json(array_column($monthlyRevenue, 'revenue'));

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Revenue (₱)',
            data,
            backgroundColor: 'rgba(124,58,237,0.5)',
            borderColor: '#7c3aed',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(124,58,237,0.1)' } },
            x: { ticks: { color: '#94a3b8' }, grid: { display: false } },
        }
    }
});
</script>
@endpush
