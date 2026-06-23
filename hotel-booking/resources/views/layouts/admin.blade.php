<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="min-h-screen bg-slate-50">
        <div class="border-b border-slate-200 bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between gap-4">
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="font-semibold text-slate-900 text-lg">{{ config('app.name', 'Laravel') }} Admin</a>
                </div>
                <nav class="flex flex-wrap items-center gap-3 text-sm text-slate-600">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-900 {{ request()->routeIs('admin.dashboard') ? 'text-slate-900 font-semibold' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.accounts') }}" class="hover:text-slate-900 {{ request()->routeIs('admin.accounts') ? 'text-slate-900 font-semibold' : '' }}">Manage Accounts</a>
                    <a href="{{ route('admin.audit-logs') }}" class="hover:text-slate-900 {{ request()->routeIs('admin.audit-logs') ? 'text-slate-900 font-semibold' : '' }}">Audit Logs</a>
                    <a href="{{ route('admin.transaction-reports') }}" class="hover:text-slate-900 {{ request()->routeIs('admin.transaction-reports') ? 'text-slate-900 font-semibold' : '' }}">Reports</a>
                </nav>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-slate-500">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-slate-700 bg-slate-100 px-3 py-1 rounded-md hover:bg-slate-200">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-slate-900">@yield('title')</h1>
                <p class="text-sm text-slate-500">@yield('subtitle', '')</p>
            </div>

            <div class="space-y-6">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
