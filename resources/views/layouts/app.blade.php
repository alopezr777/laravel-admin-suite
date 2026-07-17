<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar" data-sidebar>
            <a class="brand" href="{{ route('dashboard') }}">
                <span class="brand-mark">LAS</span>
                <span>
                    <span class="brand-name">Admin Suite</span>
                    <span class="brand-caption">Operations workspace</span>
                </span>
            </a>

            <nav class="nav-section">
                <p class="nav-label">Workspace</p>
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">Projects</a>
                <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">Tasks</a>

                @if(auth()->user()->isAdmin())
                    <p class="nav-label" style="margin-top: 24px">Administration</p>
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">Team members</a>
                    <a class="nav-link {{ request()->routeIs('activity.*') ? 'active' : '' }}" href="{{ route('activity.index') }}">Activity log</a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <div class="user-summary">
                    <span class="avatar">{{ str(auth()->user()->name)->explode(' ')->map(fn($word) => str($word)->substr(0, 1))->take(2)->join('') }}</span>
                    <span style="min-width: 0">
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <span class="user-role">{{ auth()->user()->role }}</span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="logout-button" type="submit">Sign out</button>
                    </form>
                </div>
            </div>
        </aside>

        <button class="overlay" type="button" aria-label="Close menu" data-overlay></button>

        <main class="main">
            <header class="topbar">
                <button class="menu-button" type="button" aria-label="Open menu" data-menu-toggle>☰</button>
                <span class="topbar-title">@yield('title', 'Dashboard')</span>
                <span class="topbar-meta">{{ now()->format('l, F j, Y') }}</span>
            </header>

            <div class="content">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Please review the highlighted fields.</strong>
                        <ul>
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>

