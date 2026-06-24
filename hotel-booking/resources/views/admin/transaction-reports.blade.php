@extends('layouts.admin')

@section('title', 'Transaction Reports')

@section('content')
<div class="flex items-center justify-between mb-1">
    <p class="text-xs text-stone-500">Transaction Reports</p>
    <p class="text-xs text-stone-500">{{ now()->format('D, M j, Y') }}</p>
</div>

<h1 class="text-3xl font-serif text-stone-800 mb-1">Transaction Reports</h1>
<p class="text-stone-500 mb-6">Daily booking transactions and revenue — last 10 days</p>

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <div class="flex items-center gap-3 mb-2">
            <span class="w-9 h-9 rounded-lg bg-stone-100 flex items-center justify-center">📈</span>
            <span class="text-xs uppercase text-stone-500">Total Revenue (10d)</span>
        </div>
        <div class="text-2xl font-semibold text-stone-800">${{ number_format($totalRevenue, 0) }}</div>
    </div>

    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <div class="flex items-center gap-3 mb-2">
            <span class="w-9 h-9 rounded-lg bg-stone-100 flex items-center justify-center">✅</span>
            <span class="text-xs uppercase text-stone-500">Total Transactions</span>
        </div>
        <div class="text-2xl font-semibold text-stone-800">{{ number_format($totalTransactions) }}</div>
    </div>

    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <div class="flex items-center gap-3 mb-2">
            <span class="w-9 h-9 rounded-lg bg-stone-100 flex items-center justify-center">$</span>
            <span class="text-xs uppercase text-stone-500">Avg Daily Revenue</span>
        </div>
        <div class="text-2xl font-semibold text-stone-800">${{ number_format($avgDailyRevenue, 0) }}</div>
    </div>
</div>

{{-- Chart --}}
<div class="bg-white rounded-xl border border-stone-200 p-5 mb-6">
    <h2 class="text-lg font-semibold text-stone-800 mb-4">Daily Transactions &amp; Revenue</h2>

    @php
        $maxTx = max(1, $dailySeries->max('transactions'));
    @endphp

    <div class="flex items-end gap-3 h-56 border-b border-stone-200 pb-1">
        @foreach($dailySeries as $day)
            @php $heightPct = round(($day['transactions'] / $maxTx) * 100); @endphp
            <div class="flex-1 flex flex-col items-center justify-end h-full group relative">
                <div class="text-[10px] text-stone-400 mb-1 opacity-0 group-hover:opacity-100 transition absolute -top-6 bg-stone-800 text-white px-2 py-1 rounded whitespace-nowrap">
                    {{ $day['date'] }} — {{ $day['transactions'] }} tx, ${{ number_format($day['revenue'], 0) }}
                </div>
                <div class="w-full max-w-[36px] bg-amber-300 hover:bg-amber-400 rounded-t-sm transition-all" style="height: {{ max($heightPct, 2) }}%"></div>
            </div>
        @endforeach
    </div>
    <div class="flex gap-3 mt-2">
        @foreach($dailySeries as $day)
            <div class="flex-1 text-center text-xs text-stone-500">{{ $day['date'] }}</div>
        @endforeach
    </div>
    <div class="flex items-center gap-2 mt-4 text-xs text-stone-500">
        <span class="w-3 h-3 bg-amber-300 rounded-sm"></span> Transactions
    </div>
</div>

{{-- Transaction details --}}
<div class="bg-white rounded-xl border border-stone-200 p-5">
    <h2 class="text-lg font-semibold text-stone-800 mb-4">Transaction Details</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase text-stone-500 border-b border-stone-200">
                    <th class="py-2 pr-4">TX ID</th>
                    <th class="py-2 pr-4">Date</th>
                    <th class="py-2 pr-4">Booking</th>
                    <th class="py-2 pr-4">Guest</th>
                    <th class="py-2 pr-4">Room</th>
                    <th class="py-2 pr-4 text-right">Amount</th>
                    <th class="py-2 pr-4">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactionDetails as $tx)
                    <tr class="border-b border-stone-100">
                        <td class="py-3 pr-4 text-stone-700">{{ $tx['tx_id'] }}</td>
                        <td class="py-3 pr-4 text-stone-600">{{ $tx['date'] }}</td>
                        <td class="py-3 pr-4 text-amber-700">{{ $tx['booking_id'] }}</td>
                        <td class="py-3 pr-4 font-medium text-stone-800">{{ $tx['guest'] }}</td>
                        <td class="py-3 pr-4 text-stone-600">{{ $tx['room'] }}</td>
                        <td class="py-3 pr-4 text-right font-semibold text-stone-800">${{ number_format($tx['amount'], 0) }}</td>
                        <td class="py-3 pr-4">
                            <span class="px-2 py-0.5 rounded-md text-xs bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ $tx['status'] }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-stone-400">No transactions in this period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
