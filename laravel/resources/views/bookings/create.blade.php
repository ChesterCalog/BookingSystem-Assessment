@extends('layouts.app')
@section('title', 'Book a Room')

@section('content')
<div class="pt-24 pb-16 max-w-3xl mx-auto px-4">

    <div class="text-center mb-8">
        <h1 class="font-display text-3xl font-bold text-white mb-2">Book a <span class="gradient-text">Room</span></h1>
        <p class="text-slate-400">You're booking as a member. <a href="{{ route('auth.profile') }}" class="text-primary-light hover:text-white">View past bookings</a></p>
    </div>

    <div class="glass rounded-2xl p-8">
        <form method="POST" action="{{ route('bookings.store') }}" class="space-y-6" id="booking-form">
            @csrf

            {{-- Room selection --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Select Room <span class="text-red-400">*</span></label>
                <select name="room_id" id="room-select" required
                        class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-primary-light">
                    <option value="">— Choose a room —</option>
                    @foreach($rooms as $room)
                    <option value="{{ $room->id }}"
                            data-price="{{ $room->price_per_hour }}"
                            data-capacity="{{ $room->capacity }}"
                            @selected(old('room_id', $selectedRoom?->id) == $room->id)>
                        {{ $room->name }} ({{ ucfirst($room->type) }}, {{ $room->capacity }} pax) — ₱{{ number_format($room->price_per_hour) }}/hr
                    </option>
                    @endforeach
                </select>
                @error('room_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Date --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Booking Date <span class="text-red-400">*</span></label>
                <input type="date" name="booking_date" value="{{ old('booking_date') }}" min="{{ date('Y-m-d') }}" required
                       class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-primary-light" />
                @error('booking_date')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Time range --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Start Time <span class="text-red-400">*</span></label>
                    <input type="time" name="start_time" id="start-time" value="{{ old('start_time') }}" required
                           class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-primary-light" />
                    @error('start_time')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">End Time <span class="text-red-400">*</span></label>
                    <input type="time" name="end_time" id="end-time" value="{{ old('end_time') }}" required
                           class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-primary-light" />
                    @error('end_time')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Number of guests --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Number of Guests <span class="text-red-400">*</span>
                    <span id="capacity-hint" class="text-slate-500 font-normal"></span>
                </label>
                <input type="number" name="num_guests" value="{{ old('num_guests', 1) }}" min="1" required
                       class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-primary-light" />
                @error('num_guests')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Special requests --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Special Requests <span class="text-slate-500">(optional)</span></label>
                <textarea name="special_requests" rows="3" placeholder="Any dietary needs, song preferences, decorations, etc."
                          class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-primary-light placeholder-slate-600 resize-none">{{ old('special_requests') }}</textarea>
            </div>

            {{-- Cost summary --}}
            <div id="cost-summary" class="hidden bg-purple-900/20 border border-purple-700/30 rounded-xl p-4">
                <div class="flex justify-between text-sm text-slate-300 mb-1">
                    <span>Rate</span>
                    <span id="summary-rate">—</span>
                </div>
                <div class="flex justify-between text-sm text-slate-300 mb-1">
                    <span>Duration</span>
                    <span id="summary-duration">—</span>
                </div>
                <div class="flex justify-between text-white font-bold text-lg mt-2 pt-2 border-t border-purple-700/30">
                    <span>Total Cost</span>
                    <span id="summary-total" class="text-accent">₱0</span>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-4 rounded-xl text-lg transition shadow-lg shadow-purple-900/40">
                🎤 Confirm Booking
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function recalculate() {
    const select   = document.getElementById('room-select');
    const start    = document.getElementById('start-time').value;
    const end      = document.getElementById('end-time').value;
    const opt      = select.options[select.selectedIndex];
    const price    = parseFloat(opt?.dataset?.price || 0);
    const capacity = parseInt(opt?.dataset?.capacity || 0);

    if (capacity) {
        document.getElementById('capacity-hint').textContent = `(max ${capacity})`;
    }

    if (!price || !start || !end || end <= start) {
        document.getElementById('cost-summary').classList.add('hidden');
        return;
    }

    const [sh, sm] = start.split(':').map(Number);
    const [eh, em] = end.split(':').map(Number);
    const mins  = (eh * 60 + em) - (sh * 60 + sm);
    if (mins <= 0) return;

    const hours = mins / 60;
    const total = hours * price;

    document.getElementById('summary-rate').textContent     = `₱${price.toLocaleString()} /hr`;
    document.getElementById('summary-duration').textContent = `${hours.toFixed(1)} hr(s)`;
    document.getElementById('summary-total').textContent    = `₱${total.toLocaleString('en-PH', {minimumFractionDigits: 2})}`;
    document.getElementById('cost-summary').classList.remove('hidden');
}

document.getElementById('room-select').addEventListener('change', recalculate);
document.getElementById('start-time').addEventListener('change', recalculate);
document.getElementById('end-time').addEventListener('change', recalculate);
recalculate();
</script>
@endpush
