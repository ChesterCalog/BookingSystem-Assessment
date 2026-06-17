@extends('layouts.admin')
@section('title', 'Manage Rooms')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-slate-400 text-sm">{{ $rooms->total() }} room(s) total</p>
    <a href="{{ route('admin.rooms.create') }}"
       class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
        <i class="fa-solid fa-plus mr-1"></i> Add Room
    </a>
</div>

<div class="stat-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-slate-500 text-xs uppercase border-b border-purple-900/30">
                    <th class="text-left p-4">Room</th>
                    <th class="text-left p-4">Type / Size</th>
                    <th class="text-left p-4">Capacity</th>
                    <th class="text-left p-4">Price/hr</th>
                    <th class="text-left p-4">Status</th>
                    <th class="text-left p-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-purple-900/20">
                @forelse($rooms as $room)
                <tr class="hover:bg-purple-900/10 transition">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-purple-900/30 shrink-0">
                                @if($room->image)
                                    <img src="{{ $room->image_url }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xl">🎤</div>
                                @endif
                            </div>
                            <div>
                                <p class="text-white font-medium">{{ $room->name }}</p>
                                <p class="text-slate-500 text-xs line-clamp-1">{{ Str::limit($room->description, 50) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="capitalize text-purple-300">{{ $room->type }}</span>
                        <span class="text-slate-500"> / </span>
                        <span class="capitalize text-slate-400">{{ $room->size }}</span>
                    </td>
                    <td class="p-4 text-slate-300">{{ $room->capacity }} pax</td>
                    <td class="p-4 text-accent font-medium">₱{{ number_format($room->price_per_hour) }}</td>
                    <td class="p-4">
                        @if($room->is_available)
                        <span class="inline-flex items-center gap-1.5 bg-emerald-900/40 text-emerald-300 text-xs px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Available
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 bg-red-900/40 text-red-300 text-xs px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Unavailable
                        </span>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.rooms.edit', $room) }}"
                               class="bg-blue-900/30 hover:bg-blue-900/60 text-blue-300 hover:text-white text-xs px-3 py-1.5 rounded-lg transition">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}"
                                  onsubmit="return confirm('Delete this room? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button class="bg-red-900/30 hover:bg-red-900/60 text-red-300 hover:text-white text-xs px-3 py-1.5 rounded-lg transition">
                                    <i class="fa-solid fa-trash mr-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-10 text-center text-slate-500">
                        No rooms found.
                        <a href="{{ route('admin.rooms.create') }}" class="text-primary-light hover:text-white">Add your first room</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-purple-900/30">{{ $rooms->links() }}</div>
</div>
@endsection
