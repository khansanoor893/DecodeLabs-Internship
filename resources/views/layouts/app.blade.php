<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>document.documentElement.dataset.theme = localStorage.getItem('decodelabs-theme-v2') || 'light';</script>

    <title>@yield('title', 'Dashboard') | DecodeLabs</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>
<body>

<div class="app-wrapper">

    {{-- ============ SIDEBAR ============ --}}
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <i class="bi bi-code-slash"></i>
            </div>
            <div>
                Decode<span>Labs</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') && !request()->routeIs('users.create') ? 'active' : '' }}">
                <i class="bi bi-people"></i> <span>Users</span>
            </a>
            <a href="{{ route('users.create') }}" class="{{ request()->routeIs('users.create') ? 'active' : '' }}">
                <i class="bi bi-person-plus"></i> <span>Add User</span>
            </a>
        </nav>

        <div class="sidebar-promo">
            <div class="promo-icon">
                <i class="bi bi-code-slash"></i>
            </div>
            <h6>Build. Learn. Grow.</h6>
            <p>Decode your future with real-world projects.</p>
        </div>

        <div class="sidebar-footer">
            © {{ date('Y') }} DecodeLabs
        </div>
    </aside>

    {{-- ============ MAIN AREA ============ --}}
    <div class="main-area">

        {{-- ============ TOPBAR ============ --}}
        <header class="topbar">
            <form action="{{ route('users.index') }}" method="GET" class="topbar-search">
                <i class="bi bi-search"></i>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search users by name or email..." aria-label="Search users">
            </form>

            <div class="topbar-right">
                <button class="theme-toggle" id="theme-toggle" type="button" aria-label="Switch to light theme" title="Switch theme">
                    <i class="bi bi-sun-fill" aria-hidden="true"></i><span>Light mode</span>
                </button>
                <div class="user-chip">
                    <div class="avatar">K</div>
                    <div>
                        <strong>Khansa</strong>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </div>
            </div>
        </header>

        {{-- ============ CONTENT ============ --}}
        <main class="content-wrap">

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div><i class="bi bi-exclamation-triangle"></i> {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @yield('content')

        </main>
    </div>
</div>

<script>
    (() => {
        const toggle = document.getElementById('theme-toggle');
        const icon = toggle.querySelector('i');
        const label = toggle.querySelector('span');
        const syncThemeButton = () => {
            const dark = document.documentElement.dataset.theme === 'dark';
            toggle.setAttribute('aria-label', dark ? 'Switch to light theme' : 'Switch to dark theme');
            icon.className = dark ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
            label.textContent = dark ? 'Light mode' : 'Dark mode';
        };
        syncThemeButton();
        toggle.addEventListener('click', () => {
            const next = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
            document.documentElement.dataset.theme = next;
            localStorage.setItem('decodelabs-theme-v2', next);
            syncThemeButton();
        });
    })();
</script>
</body>
</html>
