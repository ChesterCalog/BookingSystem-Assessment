<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the dedicated membership login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming membership authentication request.
     */
    public function storeMember(LoginRequest $request): RedirectResponse
    {
        $this->authenticateForRoles($request, ['customer'], 'Only member accounts can access the member portal.');

        return redirect()->intended(route('member.dashboard', absolute: false));
    }

    /**
     * Handle an incoming staff authentication request.
     */
    public function storeStaff(LoginRequest $request): RedirectResponse
    {
        $this->authenticateForRoles($request, ['staff', 'admin'], 'Only staff accounts can access the staff portal.');

        return redirect()->intended(route('staff.portal', absolute: false));
    }

    /**
     * Handle an incoming authentication request and redirect based on role.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();

        if ($user->role === 'staff' || $user->role === 'admin') {
            return redirect()->intended(route('staff.portal', absolute: false));
        }

        return redirect()->intended(route('member.dashboard', absolute: false));
    }

    /**
     * Authenticate the user, then reject valid credentials for the wrong portal.
     *
     * @param  array<int, string>  $roles
     *
     * @throws ValidationException
     */
    private function authenticateForRoles(LoginRequest $request, array $roles, string $message): void
    {
        $request->authenticate();

        if (! in_array($request->user()->role, $roles, true)) {
            Auth::guard('web')->logout();

            throw ValidationException::withMessages([
                'email' => $message,
            ]);
        }

        $request->session()->regenerate();
    }

    /**
     * Destroy an authenticated session and return to home.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
