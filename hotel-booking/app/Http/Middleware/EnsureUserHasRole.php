<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if ($request->user() && ! in_array($request->user()->role, $roles, true)) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route($this->loginRouteFor($roles))->withErrors([
                'email' => 'This account cannot access that portal.',
            ]);
        }

        return $next($request);
    }

    /**
     * @param  array<int, string>  $roles
     */
    private function loginRouteFor(array $roles): string
    {
        return count(array_intersect($roles, ['staff', 'admin'])) > 0
            ? 'staff.login'
            : 'login';
    }
}
