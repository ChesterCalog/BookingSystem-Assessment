@extends('layouts.admin')

@section('title', 'Manage Accounts')

@section('content')
<div class="flex items-center justify-between mb-1">
    <p class="text-xs text-stone-500">Manage Accounts</p>
    <p class="text-xs text-stone-500">{{ now()->format('D, M j, Y') }}</p>
</div>

<h1 class="text-3xl font-serif text-stone-800 mb-1">Manage Accounts</h1>
<p class="text-stone-500 mb-6">Staff accounts and customer profiles</p>

@if(session('success'))
    <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-2">
        {{ session('success') }}
    </div>
@endif
@error('role')
    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-2">
        {{ $message }}
    </div>
@enderror

{{-- Staff Accounts --}}
<div class="bg-white rounded-xl border border-stone-200 p-5 mb-6">
    <h2 class="text-lg font-semibold text-stone-800 mb-4">Staff Accounts</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase text-stone-500 border-b border-stone-200">
                    <th class="py-2 pr-4">Name</th>
                    <th class="py-2 pr-4">Email</th>
                    <th class="py-2 pr-4">Role</th>
                    <th class="py-2 pr-4">Status</th>
                    <th class="py-2 pr-4">Last Login</th>
                    <th class="py-2 pr-4 text-right">Edit Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staffAccounts as $staff)
                    <tr class="border-b border-stone-100">
                        <td class="py-3 pr-4 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-stone-200 text-stone-600 text-xs flex items-center justify-center font-semibold">
                                {{ strtoupper(substr($staff->name, 0, 1)) }}{{ strtoupper(substr(strrchr($staff->name, ' ') ?: '', 1, 1)) }}
                            </span>
                            <span class="font-medium text-stone-800">{{ $staff->name }}</span>
                        </td>
                        <td class="py-3 pr-4 text-stone-600">{{ $staff->email }}</td>
                        <td class="py-3 pr-4">
                            <span class="px-2 py-0.5 rounded-md text-xs border
                                @if($staff->role === 'admin') bg-purple-50 text-purple-700 border-purple-200
                                @elseif($staff->role === 'manager') bg-blue-50 text-blue-700 border-blue-200
                                @elseif($staff->role === 'front_desk') bg-amber-50 text-amber-700 border-amber-200
                                @elseif($staff->role === 'housekeeping') bg-teal-50 text-teal-700 border-teal-200
                                @else bg-stone-100 text-stone-600 border-stone-200
                                @endif">
                                {{ \Illuminate\Support\Str::headline($staff->role) }}
                            </span>
                            @if($staff->role === 'admin')
                                <div class="text-xs text-stone-400 mt-0.5">Protected</div>
                            @endif
                        </td>
                        <td class="py-3 pr-4">
                            <span class="inline-flex items-center gap-1 text-stone-600">
                                <span class="w-2 h-2 rounded-full {{ ($staff->status ?? 'active') === 'active' ? 'bg-emerald-500' : 'bg-stone-400' }}"></span>
                                {{ $staff->status ?? 'active' }}
                            </span>
                        </td>
                        <td class="py-3 pr-4 text-stone-600">{{ optional($staff->updated_at)->format('Y-m-d H:i') ?? '—' }}</td>
                        <td class="py-3 pr-4 text-right">
                            @if($staff->role === 'admin')
                                <span class="text-stone-300 text-sm">Edit</span>
                            @else
                                <button type="button"
                                    onclick="document.getElementById('edit-role-{{ $staff->id }}').classList.toggle('hidden')"
                                    class="text-sm border border-stone-300 rounded-md px-3 py-1 hover:bg-stone-50">
                                    Edit
                                </button>
                            @endif
                        </td>
                    </tr>
                    @if($staff->role !== 'admin')
                        <tr id="edit-role-{{ $staff->id }}" class="hidden bg-stone-50">
                            <td colspan="6" class="py-3 px-4">
                                <form action="{{ route('admin.accounts.update-role', $staff) }}" method="POST" class="flex items-center gap-3">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="border border-stone-300 rounded-md text-sm px-2 py-1">
                                        @foreach(['manager', 'front_desk', 'housekeeping', 'maintenance'] as $r)
                                            <option value="{{ $r }}" {{ $staff->role === $r ? 'selected' : '' }}>
                                                {{ \Illuminate\Support\Str::headline($r) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="text-sm bg-stone-800 text-white rounded-md px-3 py-1 hover:bg-stone-700">
                                        Save
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Customer Accounts --}}
<div class="bg-white rounded-xl border border-stone-200 p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-stone-800">Customer Accounts</h2>
        <div class="flex items-center gap-4 text-xs text-stone-500">
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Member — {{ $memberCount }}</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-stone-300"></span> Guest — {{ $guestCount }}</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase text-stone-500 border-b border-stone-200">
                    <th class="py-2 pr-4">Name</th>
                    <th class="py-2 pr-4">Email</th>
                    <th class="py-2 pr-4">Phone</th>
                    <th class="py-2 pr-4">Membership</th>
                    <th class="py-2 pr-4">Bookings</th>
                    <th class="py-2 pr-4">Joined</th>
                    <th class="py-2 pr-4">Last Booking</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customerAccounts as $customer)
                    <tr class="border-b border-stone-100">
                        <td class="py-3 pr-4 font-medium text-stone-800">{{ $customer->name }}</td>
                        <td class="py-3 pr-4 text-stone-600">{{ $customer->email }}</td>
                        <td class="py-3 pr-4 text-stone-600">{{ $customer->phone ?? '—' }}</td>
                        <td class="py-3 pr-4">
                            <span class="px-2 py-0.5 rounded-md text-xs border
                                {{ $customer->membership_tier === 'Member' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-stone-100 text-stone-600 border-stone-200' }}">
                                {{ $customer->membership_tier }}
                            </span>
                        </td>
                        <td class="py-3 pr-4 text-stone-600">{{ $customer->bookings_count }}</td>
                        <td class="py-3 pr-4 text-stone-600">{{ optional($customer->created_at)->format('Y-m-d') }}</td>
                        <td class="py-3 pr-4 text-stone-600">
                            {{ $customer->last_booking_date ? \Carbon\Carbon::parse($customer->last_booking_date)->format('Y-m-d') : '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-stone-400">No customer accounts yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
