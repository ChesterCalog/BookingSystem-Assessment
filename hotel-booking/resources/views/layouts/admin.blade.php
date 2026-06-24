<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> Grand Horizon - @yield('title')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    
.admin-sidebar {
    width: 20rem;
    min-height: 100vh;
    background: #ede0d6;
    border-right: 1px solid #e6dacc;
    padding: 2rem 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.admin-sidebar-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 2rem;
    text-decoration: none;
}

.admin-brand {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.75rem;
    background: #f5f0e9;
    color: #5f5345;
    display: grid;
    place-items: center;
    font-weight: 700;
}

.admin-brand-text {
    color: #3f3f3f;
    font-size: 1rem;
    font-weight: 700;
}

.admin-sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.admin-sidebar-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 0.875rem;
    color: #5f5244;
    background: transparent;
    text-decoration: none;
    transition: background-color 0.2s ease, color 0.2s ease;
    font-size: 0.95rem;
}

.admin-sidebar-link:hover {
    background: rgba(255, 255, 255, 0.8);
    color: #3f3f3f;
}

.admin-sidebar-link.active {
    background: #ffffff;
    color: #1f2937;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
}

.admin-sidebar-link.placeholder {
    opacity: 0.8;
}

.admin-sidebar-link.placeholder:hover {
    background: rgba(255, 255, 255, 0.92);
}

.admin-sidebar-icon {
    width: 1.5rem;
    display: inline-flex;
    justify-content: center;
}

.admin-sidebar-footer {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 1rem;
    background: rgba(255, 255, 255, 0.92);
}

.admin-sidebar-logout {
    margin-top: 1rem;
}

.admin-logout-button {
    width: 100%;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    padding: 0.85rem 1rem;
    border-radius: 0.95rem;
    background: #7c5d45;
    color: #fff;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.1s ease;
}

.admin-logout-button:hover {
    background: #5f4a38;
    transform: translateY(-1px);
}

.admin-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 9999px;
    background: #d9c6af;
    color: #4b4034;
    display: grid;
    place-items: center;
    font-weight: 700;
}

.admin-avatar-name {
    font-size: 0.95rem;
    font-weight: 700;
    color: #3f3f3f;
}

.admin-avatar-role {
    font-size: 0.8rem;
    color: #7c6f61;
}

.admin-main {
    padding: 2rem;
}

.admin-main h1,
.admin-main p {
    margin: 0;
}
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="min-h-screen bg-slate-50 flex">
        <aside class="admin-sidebar">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-header">
                    <div class="admin-brand">GH</div>
                    <div class="admin-brand-text">Grand Horizon</div>
                </a>

                <nav class="admin-sidebar-nav">
                    <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="admin-sidebar-icon">🏠</span>
                        <span>Dashboard</span>
                    </a>

                    <a href="#" class="admin-sidebar-link placeholder">
                        <span class="admin-sidebar-icon">🗂️</span>
                        <span>Approvals</span>
                    </a>

                    <a href="{{ route('admin.accounts') }}" class="admin-sidebar-link {{ request()->routeIs('admin.accounts') ? 'active' : '' }}">
                        <span class="admin-sidebar-icon">👥</span>
                        <span>Manage Accounts</span>
                    </a>

                    <a href="#" class="admin-sidebar-link placeholder">
                        <span class="admin-sidebar-icon">🛠️</span>
                        <span>Maintenance</span>
                    </a>

                    <a href="{{ route('admin.audit-logs') }}" class="admin-sidebar-link {{ request()->routeIs('admin.audit-logs') ? 'active' : '' }}">
                        <span class="admin-sidebar-icon">📋</span>
                        <span>Audit Logs</span>
                    </a>

                    <a href="{{ route('admin.transaction-reports') }}" class="admin-sidebar-link {{ request()->routeIs('admin.transaction-reports') ? 'active' : '' }}">
                        <span class="admin-sidebar-icon">💳</span>
                        <span>Transaction Reports</span>
                    </a>
                </nav>
            </div>

            <div class="admin-sidebar-footer">
                <div class="admin-avatar">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</div>
                <div>
                    <div class="admin-avatar-name">{{ Auth::user()->name }}</div>
                    <div class="admin-avatar-role">Administrator</div>
                </div>
                <div><form method="POST" action="{{ route('logout') }}" class="admin-sidebar-logout">
                @csrf
                <button type="submit" class="admin-logout-button">Logout</button>
                </form></div>
            </div>

            
        </aside>

        <main class="admin-main flex-1 p-8">

            <div class="space-y-6">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
