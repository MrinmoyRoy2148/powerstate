<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Admin')) | PowerStat BD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    <header class="bg-slate-950 text-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 font-bold">
                <span class="flex size-10 items-center justify-center rounded-xl bg-emerald-500 text-lg">P</span>
                <span>PowerStat <span class="text-emerald-400">{{ __('Admin') }}</span></span>
            </a>
            <nav aria-label="{{ __('Admin navigation') }}" class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-slate-300">
                <a class="{{ request()->routeIs('admin.dashboard') ? 'text-white' : 'hover:text-white' }}" href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                <a class="{{ request()->routeIs('admin.zones.*') ? 'text-white' : 'hover:text-white' }}" href="{{ route('admin.zones.index') }}">{{ __('Areas') }}</a>
                <a class="{{ request()->routeIs('admin.reasons.*') ? 'text-white' : 'hover:text-white' }}" href="{{ route('admin.reasons.index') }}">{{ __('Reasons') }}</a>
                <a class="{{ request()->routeIs('admin.schedules.*') ? 'text-white' : 'hover:text-white' }}" href="{{ route('admin.schedules.index') }}">{{ __('Schedules') }}</a>
                <a class="{{ request()->routeIs('admin.outages.*') ? 'text-white' : 'hover:text-white' }}" href="{{ route('admin.outages.index') }}">{{ __('Outages') }}</a>
                <a class="{{ request()->routeIs('admin.announcements.*') ? 'text-white' : 'hover:text-white' }}" href="{{ route('admin.announcements.index') }}">{{ __('Notices') }}</a>
            </nav>
            <div class="flex flex-wrap items-center gap-3 text-sm">
                <span class="text-slate-300">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('language.update') }}">
                    @csrf
                    <input type="hidden" name="locale" value="{{ app()->getLocale() === 'bn' ? 'en' : 'bn' }}">
                    <button class="rounded-lg border border-slate-700 px-3 py-2 font-semibold hover:bg-slate-800" type="submit" aria-label="{{ __('Switch language') }}">{{ app()->getLocale() === 'bn' ? 'English' : 'বাংলা' }}</button>
                </form>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="rounded-lg border border-slate-700 px-3 py-2 font-semibold hover:bg-slate-800" type="submit">{{ __('Sign out') }}</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto min-h-[78vh] max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900" role="alert">
                <p class="font-bold">{{ __('Please review the following:') }}</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>
