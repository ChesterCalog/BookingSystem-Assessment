<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Admin — @yield('title', 'Dashboard') | KaraokeZone</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#7c3aed', light: '#a78bfa', dark: '#5b21b6' },
                        accent:  { DEFAULT: '#f59e0b' },
                        dark:    { DEFAULT: '#0f0a1e', card: '#1a1033', sidebar: '#130d2a' },
                    },
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body { background: #0f0a1e; font-family: 'Inter', sans-serif; }
        .sidebar-link { display: flex; align-items: center; gap: 10px; padding: 10px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; color: #94a3b8; transition: all .2s; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(124,58,237,.2); color: #a78bfa; }
        .stat-card { background: rgba(124,58,237,.08); border: 1px solid rgba(124,58,237,.2); border-radius: 12px; }
    </style>
    @stack('head')
</head>
<body class="text-slate-200">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-dark-sidebar border-r border-purple-900/30 flex flex-col fixed h-full z-40">
        <div class="p-6 border-b border-purple-900/30">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="text-2xl">🎤</span>
                <span style="font-family:Poppins" class="font-bold text-lg" style="background:linear-gradient(135deg,#a78bfa,#f59e0b);-webkit-background-clip:text;-webkit-text-fill-color:transparent">KaraokeZone</span>
            </a>
            <p class="text-xs text-slate-500 mt-1">Admin Panel</p>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"       class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high w-5"></i> Dashboard
            </a>
            <a href="{{ route('admin.rooms.index') }}"     class="sidebar-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                <i class="fa-solid fa-door-open w-5"></i> Rooms
            </a>
            <a href="{{ route('admin.bookings.index') }}"  class="sidebar-link {{ request()->routeIs('admin.bookings.index') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check w-5"></i> Bookings
            </a>
            <a href="{{ route('admin.bookings.calendar') }}" class="sidebar-link {{ request()->routeIs('admin.bookings.calendar') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-days w-5"></i> Calendar
            </a>
        </nav>

        <div class="p-4 border-t border-purple-900/30">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">Administrator</p>
                </div>
            </div>
            <a href="{{ route('home') }}" class="sidebar-link text-xs">
                <i class="fa-solid fa-arrow-left w-5"></i> Back to Site
            </a>
            <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button class="sidebar-link w-full text-red-400 hover:text-red-300 text-left">
                    <i class="fa-solid fa-right-from-bracket w-5"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 ml-64">
        {{-- Top bar --}}
        <header class="bg-dark-card/80 border-b border-purple-900/30 sticky top-0 z-30 px-6 py-4 flex items-center justify-between">
            <h1 class="text-lg font-semibold text-white">@yield('title', 'Dashboard')</h1>
            <div class="text-sm text-slate-400">{{ now()->format('l, F j, Y') }}</div>
        </header>

        {{-- Flash --}}
        @if(session('success'))
        <div class="mx-6 mt-4 flex items-center gap-3 bg-emerald-900/60 border border-emerald-500 text-emerald-100 rounded-lg p-3 text-sm">
            <i class="fa-solid fa-circle-check text-emerald-400"></i>{{ session('success') }}
        </div>
        @endif
        @if($errors->any())
        <div class="mx-6 mt-4 bg-red-900/60 border border-red-500 text-red-100 rounded-lg p-3 text-sm">
            <p class="font-medium mb-1">Please fix the following errors:</p>
            <ul class="list-disc pl-4 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="p-6">
            @yield('content')
        </div>
    </div>
</div>

@stack('scripts')
</body>
</html>
