@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="min-h-screen pt-24 pb-16 flex items-center justify-center hero-bg">
    <div class="w-full max-w-md px-4">
        <div class="glass rounded-2xl p-8">
            <div class="text-center mb-8">
                <span class="text-4xl">🎤</span>
                <h1 class="font-display text-2xl font-bold text-white mt-2">Welcome Back</h1>
                <p class="text-slate-400 text-sm mt-1">Sign in to manage your bookings</p>
            </div>

            <form method="POST" action="{{ route('auth.login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-primary-light focus:ring-1 focus:ring-primary-light"
                           placeholder="you@example.com" />
                    @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                    <input type="password" name="password" required
                           class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-primary-light focus:ring-1 focus:ring-primary-light"
                           placeholder="••••••••" />
                    @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-slate-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-purple-700 bg-dark text-primary"> Remember me
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-xl transition shadow-lg shadow-purple-900/40">
                    Sign In
                </button>
            </form>

            <p class="text-center text-slate-400 text-sm mt-6">
                Don't have an account?
                <a href="{{ route('auth.register') }}" class="text-primary-light hover:text-white transition">Create one</a>
            </p>
            <p class="text-center text-slate-500 text-xs mt-2">
                Or <a href="{{ route('bookings.guestCreate') }}" class="text-accent hover:text-white transition">book as a guest</a> without an account
            </p>
        </div>
    </div>
</div>
@endsection
