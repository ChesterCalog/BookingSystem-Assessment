<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Public room listing with search and filter.
     */
    public function index(Request $request)
    {
        $query = Room::available();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by capacity
        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', (int) $request->capacity);
        }

        // Filter by max price
        if ($request->filled('max_price')) {
            $query->where('price_per_hour', '<=', (float) $request->max_price);
        }

        $rooms = $query->paginate(9)->withQueryString();

        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show a single room detail page.
     */
    public function show(Room $room)
    {
        return view('rooms.show', compact('room'));
    }
}
