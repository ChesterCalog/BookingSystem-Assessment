<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminRoomController extends Controller
{
    public function index()
    {
        $rooms = Room::latest()->paginate(15);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'type'          => ['required', 'in:standard,deluxe,vip,party'],
            'size'          => ['required', 'in:small,medium,large,xlarge'],
            'capacity'      => ['required', 'integer', 'min:1'],
            'price_per_hour'=> ['required', 'numeric', 'min:0'],
            'description'   => ['nullable', 'string'],
            'amenities'     => ['nullable', 'array'],
            'amenities.*'   => ['string'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_available'  => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        $data['is_available'] = $request->boolean('is_available', true);

        Room::create($data);

        return redirect()->route('admin.rooms.index')->with('success', 'Room created successfully.');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'type'          => ['required', 'in:standard,deluxe,vip,party'],
            'size'          => ['required', 'in:small,medium,large,xlarge'],
            'capacity'      => ['required', 'integer', 'min:1'],
            'price_per_hour'=> ['required', 'numeric', 'min:0'],
            'description'   => ['nullable', 'string'],
            'amenities'     => ['nullable', 'array'],
            'amenities.*'   => ['string'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_available'  => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            // Remove old image
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        $data['is_available'] = $request->boolean('is_available');

        $room->update($data);

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        if ($room->image) {
            Storage::disk('public')->delete($room->image);
        }
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted.');
    }
}
