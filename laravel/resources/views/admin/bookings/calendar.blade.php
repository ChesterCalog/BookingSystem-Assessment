@extends('layouts.admin')
@section('title', 'Booking Calendar')

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css">
@endpush

@section('content')

{{-- Legend --}}
<div class="flex flex-wrap gap-4 mb-4 text-sm">
    @foreach([
        ['bg-emerald-500','Approved (Member)'],
        ['bg-indigo-500','Approved (Guest)'],
        ['bg-yellow-500','Pending (Member)'],
        ['bg-orange-500','Pending (Guest)'],
    ] as [$color, $label])
    <div class="flex items-center gap-2">
        <span class="w-3 h-3 rounded-full {{ $color }}"></span>
        <span class="text-slate-400">{{ $label }}</span>
    </div>
    @endforeach
</div>

<div class="stat-card p-5 rounded-2xl">
    <div id="booking-calendar"></div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
const events = {!! $events !!};

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('booking-calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,timeGridWeek,timeGridDay',
        },
        events,
        eventClick: function (info) {
            // Parse type and id from the event id: "user-5" or "guest-3"
            const parts = info.event.id.split('-');
            const type  = parts[0];
            const id    = parts[1];
            window.location.href = `/admin/bookings/${type}/${id}`;
        },
        height: 'auto',
        themeSystem: 'standard',
    });
    calendar.render();
});
</script>

<style>
.fc { color: #e2e8f0; }
.fc .fc-toolbar-title { color: #fff; font-size: 1.1rem; }
.fc .fc-button { background: rgba(124,58,237,.4)!important; border-color: rgba(124,58,237,.5)!important; color: #c4b5fd!important; }
.fc .fc-button:hover { background: rgba(124,58,237,.7)!important; }
.fc .fc-button-active { background: #7c3aed!important; }
.fc-theme-standard td, .fc-theme-standard th { border-color: rgba(124,58,237,.2); }
.fc-theme-standard .fc-scrollgrid { border-color: rgba(124,58,237,.2); }
.fc .fc-daygrid-day-number { color: #94a3b8; }
.fc .fc-day-today { background: rgba(124,58,237,.08)!important; }
.fc .fc-event { font-size: 0.7rem; border-radius: 4px; cursor: pointer; }
</style>
@endpush
