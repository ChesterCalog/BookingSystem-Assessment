<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class ManageAccountsController extends Controller
{
    // Roles considered "staff" or admin accounts for the staff section.
    protected array $staffRoles = ['admin', 'manager', 'front_desk', 'housekeeping', 'maintenance', 'staff'];

    public function index(Request $request)
    {
        $staffAccounts = User::whereIn('role', $this->staffRoles)
            ->orderBy('name')
            ->get();

        $customerAccounts = User::where('role', 'customer')
            ->withCount('bookings')
            ->with(['bookings' => function ($q) {
                $q->orderByDesc('check_in')->limit(1);
            }])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($user) {
                $lastBooking = $user->bookings->first();
                $user->last_booking_date = $lastBooking?->check_in;
                $user->membership_tier = $user->bookings_count > 0 ? 'member' : 'guest';
                return $user;
            });

        $memberCount = $customerAccounts->where('membership_tier', 'Member')->count();
        $guestCount = $customerAccounts->where('membership_tier', 'Guest')->count();

        return view('admin.manage-accounts', [
            'staffAccounts' => $staffAccounts,
            'customerAccounts' => $customerAccounts,
            'memberCount' => $memberCount,
            'guestCount' => $guestCount,
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string',
        ]);

        // Protect the primary admin account from being demoted by mistake
        if ($user->role === 'admin' && $request->user()->id === $user->id) {
            return back()->withErrors(['role' => 'You cannot change your own admin role.']);
        }

        $oldRole = $user->role;

        $user->update(['role' => $request->input('role')]);

        AuditLog::log(
            $request->user(),
            'staff',
            'Role Changed',
            $user->name,
            "Role updated from {$oldRole} to {$user->role}"
        );

        return back()->with('success', "Role updated for {$user->name}.");
    }

    public function toggleStatus(Request $request, User $user)
    {
        // Requires a `status` column (e.g. 'active' / 'inactive') if you want this to persist.
        // Schema you shared doesn't have one yet — see note below the code.
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', "Status updated for {$user->name}.");
    }
}
