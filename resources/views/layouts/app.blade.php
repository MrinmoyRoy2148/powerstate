<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PowerStat BD') | PowerStat BD</title>
    <meta name="description" content="@yield('description', __('Area-wise power updates for Bangladesh.'))">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="{{ __('PowerStat BD home') }}">
                <span class="flex size-11 items-center justify-center rounded-2xl bg-emerald-600 text-xl font-black text-white">P</span>
                <span>
                    <span class="block text-lg font-extrabold tracking-tight text-slate-950">PowerStat <span class="text-emerald-600">BD</span></span>
                    <span class="block text-xs text-slate-500">{{ __('Power updates, area by area') }}</span>
                </span>
            </a>
            <div class="flex flex-wrap items-center justify-between gap-3 lg:justify-end">
                <nav aria-label="{{ __('Main navigation') }}" class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm font-semibold text-slate-600">
                    <a class="{{ request()->routeIs('home') ? 'text-emerald-700' : 'hover:text-emerald-700' }}" href="{{ route('home') }}">{{ __('Home') }}</a>
                    <a class="{{ request()->routeIs('zones.*') ? 'text-emerald-700' : 'hover:text-emerald-700' }}" href="{{ route('zones.index') }}">{{ __('Areas') }}</a>
                    <a class="{{ request()->routeIs('schedules.*') ? 'text-emerald-700' : 'hover:text-emerald-700' }}" href="{{ route('schedules.index') }}">{{ __('Schedules') }}</a>
                    <a class="{{ request()->routeIs('outages.index') ? 'text-emerald-700' : 'hover:text-emerald-700' }}" href="{{ route('outages.index') }}">{{ __('Current outages') }}</a>
                    <a class="{{ request()->routeIs('outages.history') ? 'text-emerald-700' : 'hover:text-emerald-700' }}" href="{{ route('outages.history') }}">{{ __('History') }}</a>
                    <a class="{{ request()->routeIs('announcements.*') ? 'text-emerald-700' : 'hover:text-emerald-700' }}" href="{{ route('announcements.index') }}">{{ __('Notices') }}</a>
                </nav>
                <form method="POST" action="{{ route('language.update') }}">
                    @csrf
                    <input type="hidden" name="locale" value="{{ app()->getLocale() === 'bn' ? 'en' : 'bn' }}">
                    <button class="rounded-xl border border-slate-300 px-3 py-2 text-sm font-bold text-slate-700 hover:border-emerald-500 hover:text-emerald-700" type="submit" aria-label="{{ __('Switch language') }}">
                        {{ app()->getLocale() === 'bn' ? 'English' : 'বাংলা' }}
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto min-h-[70vh] max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-sm text-slate-500 sm:px-6 md:flex-row md:items-center md:justify-between lg:px-8">
            <p>&copy; {{ date('Y') }} PowerStat BD. {{ __('Community information portal.') }}</p>
            <p>{{ __('Expected restoration times are estimates, not guarantees.') }}</p>
        </div>
    </footer>
</body>
</html>
