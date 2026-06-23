<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard with booking tickets.
     */
    public function index()
    {
        $user = Auth::user();

        $bookings = Booking::with('roomType')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('user', 'bookings'));
    }

    /**
     * Update the user's selected avatar.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'string', 'in:male_1,male_2,male_3,female_1,female_2,female_3'],
        ]);

        $user = Auth::user();

        // Only allow avatar changes for non-guest accounts
        if ($user->role === 'guest') {
            return back()->with('error', 'Guests cannot change their profile avatar.');
        }

        $user->avatar = $request->avatar;
        $user->save();

        return back()->with('success', 'Profile avatar updated successfully.');
    }
}
