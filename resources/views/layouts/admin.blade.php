<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Admin')) | PowerStat BD</title>
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
</head>
<body class="admin-app">
    <header class="admin-topbar">
        <div class="container-fluid admin-topbar__inner">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="admin-menu-toggle btn d-md-none" data-admin-menu-toggle aria-expanded="false" aria-controls="admin-sidebar" aria-label="{{ __('Toggle admin navigation') }}">
                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </button>
                <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                    <span class="admin-brand__mark" aria-hidden="true">PS</span>
                    <span class="admin-brand__name">PowerStat <span>BD</span></span>
                    <span class="admin-brand__badge">{{ __('Admin') }}</span>
                </a>
            </div>

            <div class="admin-topbar__actions">
                <span class="admin-user-name">{{ auth()->user()->name }}</span>
                <a class="btn admin-topbar__button" href="{{ route('admin.profile.edit') }}">{{ __('My account') }}</a>
                <form method="POST" action="{{ route('language.update') }}">
                    @csrf
                    <input type="hidden" name="locale" value="{{ app()->getLocale() === 'bn' ? 'en' : 'bn' }}">
                    <button class="btn admin-topbar__button" type="submit" aria-label="{{ __('Switch language') }}">{{ app()->getLocale() === 'bn' ? 'English' : 'বাংলা' }}</button>
                </form>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="btn admin-topbar__signout" type="submit">{{ __('Sign out') }}</button>
                </form>
            </div>
        </div>
    </header>

    <div class="admin-layout">
        <aside id="admin-sidebar" class="admin-sidebar d-none d-md-block">
            <nav aria-label="{{ __('Admin navigation') }}" class="admin-sidebar__nav">
                <a class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                <a class="admin-nav-link {{ request()->routeIs('admin.zones.*') ? 'active' : '' }}" @if (request()->routeIs('admin.zones.*')) aria-current="page" @endif href="{{ route('admin.zones.index') }}">{{ __('Areas') }}</a>
                <a class="admin-nav-link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}" @if (request()->routeIs('admin.schedules.*')) aria-current="page" @endif href="{{ route('admin.schedules.index') }}">{{ __('Load-shedding schedules') }}</a>
                <a class="admin-nav-link {{ request()->routeIs('admin.outages.index', 'admin.outages.create', 'admin.outages.show', 'admin.outages.edit') ? 'active' : '' }}" @if (request()->routeIs('admin.outages.index', 'admin.outages.create', 'admin.outages.show', 'admin.outages.edit')) aria-current="page" @endif href="{{ route('admin.outages.index') }}">{{ __('Outage Management') }}</a>
                <a class="admin-nav-link {{ request()->routeIs('admin.reasons.*') ? 'active' : '' }}" @if (request()->routeIs('admin.reasons.*')) aria-current="page" @endif href="{{ route('admin.reasons.index') }}">{{ __('Outage Reasons') }}</a>
                <a class="admin-nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}" @if (request()->routeIs('admin.announcements.*')) aria-current="page" @endif href="{{ route('admin.announcements.index') }}">{{ __('Announcements') }}</a>
                <a class="admin-nav-link {{ request()->routeIs('admin.outages.history') ? 'active' : '' }}" @if (request()->routeIs('admin.outages.history')) aria-current="page" @endif href="{{ route('admin.outages.history') }}">{{ __('Outage History') }}</a>
                <a class="admin-nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" @if (request()->routeIs('admin.profile.*')) aria-current="page" @endif href="{{ route('admin.profile.edit') }}">{{ __('Admin Account') }}</a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-main__content">
                @if (session('success'))
                    <div class="alert alert-success admin-alert" role="status">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger admin-alert" role="alert">
                        <p class="fw-bold">{{ __('Please review the following:') }}</p>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
                <footer class="admin-footer">PowerStat BD · {{ __('Admin') }}</footer>
            </div>
        </main>
    </div>
    <dialog data-confirmation-dialog class="m-auto w-[calc(100%-2rem)] max-w-md rounded-2xl border-0 p-0 shadow-2xl backdrop:bg-slate-950/50">
        <div class="p-6">
            <h2 class="text-lg font-black text-slate-950">{{ __('Please confirm') }}</h2>
            <p data-confirmation-message class="mt-3 text-sm leading-6 text-slate-600"></p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" data-confirmation-cancel class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700">{{ __('Cancel') }}</button>
                <button type="button" data-confirmation-confirm class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-bold text-white">{{ __('Confirm') }}</button>
            </div>
        </div>
    </dialog>
</body>
</html>
