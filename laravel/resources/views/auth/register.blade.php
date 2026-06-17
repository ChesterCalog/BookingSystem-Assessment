@extends('layouts.app')
@section('title', 'Create Account')

@section('content')
<div class="min-h-screen pt-24 pb-16 flex items-center justify-center hero-bg">
    <div class="w-full max-w-md px-4">
        <div class="glass rounded-2xl p-8">
            <div class="text-center mb-8">
                <span class="text-4xl">🎤</span>
                <h1 class="font-display text-2xl font-bold text-white mt-2">Join KaraokeZone</h1>
                <p class="text-slate-400 text-sm mt-1">Create an account to manage bookings & history</p>
            </div>

            <form method="POST" action="{{ route('auth.register') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-primary-light focus:ring-1 focus:ring-primary-light"
                           placeholder="Juan dela Cruz" />
                    @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-primary-light focus:ring-1 focus:ring-primary-light"
                           placeholder="you@example.com" />
                    @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Phone Number <span class="text-slate-500">(optional)</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-primary-light focus:ring-1 focus:ring-primary-light"
                           placeholder="+63 912 345 6789" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                    <input type="password" name="password" required
                           class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-primary-light focus:ring-1 focus:ring-primary-light"
                           placeholder="At least 8 characters" />
                    @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-primary-light focus:ring-1 focus:ring-primary-light"
                           placeholder="Re-enter password" />
                </div>

                <button type="submit"
                        class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-xl transition shadow-lg shadow-purple-900/40">
                    Create Account
                </button>
            </form>

            <p class="text-center text-slate-400 text-sm mt-6">
                Already have an account?
                <a href="{{ route('auth.login') }}" class="text-primary-light hover:text-white transition">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection
