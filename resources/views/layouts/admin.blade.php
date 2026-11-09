<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Admin')) | PowerStat BD</title>
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
</head>
<body class="min-vh-100 bg-body-tertiary text-dark">
    <header class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand fw-bold">
                PowerStat <span class="text-success">{{ __('Admin') }}</span>
            </a>
            <div class="d-flex flex-wrap align-items-center gap-3 small">
                <span class="text-white-50">{{ auth()->user()->name }}</span>
                <a class="btn btn-outline-light btn-sm" href="{{ route('admin.profile.edit') }}">{{ __('My account') }}</a>
                <form method="POST" action="{{ route('language.update') }}">
                    @csrf
                    <input type="hidden" name="locale" value="{{ app()->getLocale() === 'bn' ? 'en' : 'bn' }}">
                    <button class="btn btn-outline-light btn-sm" type="submit" aria-label="{{ __('Switch language') }}">{{ app()->getLocale() === 'bn' ? 'English' : 'বাংলা' }}</button>
                </form>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="btn btn-outline-light btn-sm" type="submit">{{ __('Sign out') }}</button>
                </form>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row min-vh-100">
            <aside class="col-12 col-md-3 col-xl-2 border-end bg-white p-3">
                <nav aria-label="{{ __('Admin navigation') }}" class="nav nav-pills flex-column gap-1">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-dark' }}" href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    <a class="nav-link {{ request()->routeIs('admin.zones.*') ? 'active' : 'text-dark' }}" href="{{ route('admin.zones.index') }}">{{ __('Zones & Areas') }}</a>
                    <a class="nav-link {{ request()->routeIs('admin.schedules.*') ? 'active' : 'text-dark' }}" href="{{ route('admin.schedules.index') }}">{{ __('Load-shedding schedules') }}</a>
                    <a class="nav-link {{ request()->routeIs('admin.outages.index', 'admin.outages.create', 'admin.outages.show', 'admin.outages.edit') ? 'active' : 'text-dark' }}" href="{{ route('admin.outages.index') }}">{{ __('Outage Management') }}</a>
                    <a class="nav-link {{ request()->routeIs('admin.reasons.*') ? 'active' : 'text-dark' }}" href="{{ route('admin.reasons.index') }}">{{ __('Outage Reasons') }}</a>
                    <a class="nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : 'text-dark' }}" href="{{ route('admin.announcements.index') }}">{{ __('Announcements') }}</a>
                    <a class="nav-link {{ request()->routeIs('admin.outages.history') ? 'active' : 'text-dark' }}" href="{{ route('admin.outages.history') }}">{{ __('Outage History') }}</a>
                    <a class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : 'text-dark' }}" href="{{ route('admin.profile.edit') }}">{{ __('Admin Account') }}</a>
                </nav>
            </aside>
            <main class="col-12 col-md-9 col-xl-10 p-3 p-lg-4">
        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <p class="fw-bold">{{ __('Please review the following:') }}</p>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
