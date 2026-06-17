<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'KaraokeZone') — Sing Your Heart Out</title>

    {{-- Tailwind CSS CDN (replace with compiled assets in production) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:  { DEFAULT: '#7c3aed', light: '#a78bfa', dark: '#5b21b6' },
                        accent:   { DEFAULT: '#f59e0b', light: '#fcd34d' },
                        dark:     { DEFAULT: '#0f0a1e', card: '#1a1033' },
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                        display: ['Poppins', 'ui-sans-serif'],
                    },
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        body { background: #0f0a1e; color: #e2e8f0; font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Poppins', sans-serif; }
        .glass { background: rgba(124,58,237,0.08); backdrop-filter: blur(12px); border: 1px solid rgba(124,58,237,0.2); }
        .gradient-text { background: linear-gradient(135deg, #a78bfa, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hero-bg { background: radial-gradient(ellipse at 60% 40%, rgba(124,58,237,0.3) 0%, transparent 60%), radial-gradient(ellipse at 20% 80%, rgba(245,158,11,0.15) 0%, transparent 50%), #0f0a1e; }
        .card-hover { transition: transform .2s, box-shadow .2s; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(124,58,237,0.25); }
    </style>

    @stack('head')
</head>
<body class="min-h-screen">

    {{-- Navbar --}}
    <nav class="fixed top-0 inset-x-0 z-50 glass">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="text-2xl">🎤</span>
                    <span class="font-display text-xl font-bold gradient-text">KaraokeZone</span>
                </a>

                {{-- Desktop nav --}}
                <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                    <a href="{{ route('home') }}"           class="text-slate-300 hover:text-white transition">Home</a>
                    <a href="{{ route('rooms.index') }}"    class="text-slate-300 hover:text-white transition">Rooms</a>
                    <a href="{{ route('bookings.guestCreate') }}" class="text-slate-300 hover:text-white transition">Book as Guest</a>

                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-yellow-400 hover:text-yellow-300 transition">
                                <i class="fa-solid fa-gauge-high mr-1"></i>Admin
                            </a>
                        @else
                            <a href="{{ route('bookings.create') }}"  class="text-slate-300 hover:text-white transition">Book Now</a>
                            <a href="{{ route('auth.profile') }}"     class="text-slate-300 hover:text-white transition">My Bookings</a>
                        @endif
                        <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-400 hover:text-red-300 transition">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('auth.login') }}"    class="text-slate-300 hover:text-white transition">Login</a>
                        <a href="{{ route('auth.register') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition">Sign Up</a>
                    @endauth
                </div>

                {{-- Mobile hamburger --}}
                <button id="mobile-menu-btn" class="md:hidden text-slate-300 hover:text-white">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu" class="md:hidden hidden px-4 pb-4 space-y-2 text-sm">
            <a href="{{ route('home') }}"                        class="block py-2 text-slate-300">Home</a>
            <a href="{{ route('rooms.index') }}"                 class="block py-2 text-slate-300">Rooms</a>
            <a href="{{ route('bookings.guestCreate') }}"        class="block py-2 text-slate-300">Book as Guest</a>
            @auth
                <a href="{{ route('auth.profile') }}"            class="block py-2 text-slate-300">My Bookings</a>
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button class="block py-2 text-red-400 w-full text-left">Logout</button>
                </form>
            @else
                <a href="{{ route('auth.login') }}"              class="block py-2 text-slate-300">Login</a>
                <a href="{{ route('auth.register') }}"           class="block py-2 text-primary-light">Sign Up</a>
            @endauth
        </div>
    </nav>

    {{-- Flash messages --}}
    <div class="fixed top-20 right-4 z-50 space-y-2 w-80" id="flash-container">
        @if(session('success'))
        <div class="flex items-start gap-3 bg-emerald-900/80 border border-emerald-500 text-emerald-100 rounded-lg p-4 shadow-lg" x-data="{show:true}" x-show="show">
            <i class="fa-solid fa-circle-check mt-0.5 text-emerald-400"></i>
            <p class="text-sm flex-1">{{ session('success') }}</p>
            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white">&times;</button>
        </div>
        @endif
        @if($errors->any())
        <div class="bg-red-900/80 border border-red-500 text-red-100 rounded-lg p-4 shadow-lg">
            <div class="flex items-center gap-2 mb-2"><i class="fa-solid fa-triangle-exclamation text-red-400"></i><strong class="text-sm">Please fix the errors below.</strong></div>
            <ul class="text-sm space-y-1 list-disc pl-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    {{-- Main content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-dark-card border-t border-purple-900/30 py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-2xl">🎤</span>
                    <span class="font-display text-lg font-bold gradient-text">KaraokeZone</span>
                </div>
                <p class="text-slate-400 text-sm">Your premier karaoke destination. Sing, celebrate, and create unforgettable memories.</p>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-3">Quick Links</h4>
                <ul class="space-y-2 text-slate-400 text-sm">
                    <li><a href="{{ route('home') }}"             class="hover:text-white">Home</a></li>
                    <li><a href="{{ route('rooms.index') }}"      class="hover:text-white">Rooms</a></li>
                    <li><a href="{{ route('bookings.guestCreate') }}" class="hover:text-white">Book a Room</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-3">Contact Us</h4>
                <ul class="space-y-2 text-slate-400 text-sm">
                    <li><i class="fa-solid fa-location-dot mr-2 text-primary-light"></i>123 Karaoke St., Manila</li>
                    <li><i class="fa-solid fa-phone mr-2 text-primary-light"></i>+63 912 345 6789</li>
                    <li><i class="fa-solid fa-envelope mr-2 text-primary-light"></i>hello@karaokeZone.com</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-3">Operating Hours</h4>
                <ul class="text-slate-400 text-sm space-y-1">
                    <li>Mon – Thu: 2PM – 12AM</li>
                    <li>Fri – Sat: 12PM – 3AM</li>
                    <li>Sunday: 12PM – 12AM</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 mt-8 pt-6 border-t border-purple-900/20 text-center text-slate-500 text-sm">
            &copy; {{ date('Y') }} KaraokeZone. All rights reserved.
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
        // Auto-dismiss flash messages after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('#flash-container > div').forEach(el => el.remove());
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
