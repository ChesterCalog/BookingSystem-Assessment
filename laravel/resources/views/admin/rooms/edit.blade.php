@extends('layouts.admin')
@section('title', 'Edit Room')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('admin.rooms.index') }}" class="text-slate-400 hover:text-white text-sm mb-6 inline-flex items-center gap-1">
        <i class="fa-solid fa-arrow-left"></i> Back to Rooms
    </a>

    <div class="stat-card p-8">
        <form method="POST" action="{{ route('admin.rooms.update', $room) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PUT')

            @include('admin.rooms._form')

            <button type="submit"
                    class="bg-primary hover:bg-primary-dark text-white font-semibold px-8 py-3 rounded-xl transition">
                <i class="fa-solid fa-floppy-disk mr-1"></i> Save Changes
            </button>
        </form>
    </div>
</div>
@endsection
