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
            <div class="flex flex-wrap items-center justify-between gap-2 lg:justify-end">
                <div class="flex flex-wrap items-center gap-2 text-sm font-semibold">
                    @guest
                        <a class="inline-flex min-h-10 items-center justify-center rounded-lg border border-emerald-200 px-3 text-emerald-700 hover:border-emerald-300 hover:bg-emerald-50" href="{{ route('login') }}">{{ __('Sign in') }}</a>
                        <a class="inline-flex min-h-10 items-center justify-center rounded-lg bg-emerald-600 px-3 text-white shadow-sm hover:bg-emerald-700" href="{{ route('register') }}">{{ __('Register') }}</a>
                    @else
                        <details class="relative">
                            <summary class="cursor-pointer list-none rounded-lg border border-slate-300 px-3 py-2.5 text-slate-700 hover:border-emerald-400">{{ auth()->user()->name }}</summary>
                            <div class="absolute right-0 z-30 mt-2 min-w-48 rounded-xl border border-slate-200 bg-white p-2 shadow-lg">
                                <a class="block rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('zones.saved') }}">{{ __('Saved areas') }}</a>
                                @if (auth()->user()->isAdmin())
                                    <a class="block rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('admin.dashboard') }}">{{ __('Admin panel') }}</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="block w-full rounded-lg px-3 py-2 text-left hover:bg-slate-50" type="submit">{{ __('Sign out') }}</button>
                                </form>
                            </div>
                        </details>
                    @endguest
                    <form method="POST" action="{{ route('language.update') }}">
                        @csrf
                        <input type="hidden" name="locale" value="{{ app()->getLocale() === 'bn' ? 'en' : 'bn' }}">
                        <button class="inline-flex min-h-10 items-center justify-center rounded-lg border border-slate-300 px-3 text-sm font-bold text-slate-700 hover:border-emerald-500 hover:bg-emerald-50" type="submit" aria-label="{{ __('Switch language') }}">
                            {{ app()->getLocale() === 'bn' ? 'English' : 'বাংলা' }}
                        </button>
                    </form>
                </div>
                <button type="button" class="inline-flex size-10 items-center justify-center rounded-lg border border-slate-300 text-slate-700 hover:border-emerald-500 hover:bg-emerald-50 lg:hidden" aria-expanded="false" aria-controls="public-mobile-menu" aria-label="{{ __('Open menu') }}" data-menu-toggle>
                    <svg data-menu-icon-open aria-hidden="true" viewBox="0 0 24 24" class="size-5 fill-none stroke-current stroke-2">
                        <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg data-menu-icon-close aria-hidden="true" viewBox="0 0 24 24" class="hidden size-5 fill-none stroke-current stroke-2">
                        <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/>
                    </svg>
                </button>
            </div>
            <div id="public-mobile-menu" class="hidden w-full flex-col gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3 lg:flex lg:w-auto lg:flex-row lg:items-center lg:border-0 lg:bg-transparent lg:p-0">
                <nav aria-label="{{ __('Main navigation') }}" class="flex flex-col gap-1 text-sm font-semibold text-slate-600 lg:flex-row lg:flex-wrap lg:items-center lg:gap-x-4 lg:gap-y-2">
                    <a class="rounded-lg px-3 py-2.5 lg:px-1 lg:py-1 {{ request()->routeIs('home') ? 'bg-emerald-100 text-emerald-700 lg:bg-transparent' : 'hover:bg-white hover:text-emerald-700 lg:hover:bg-transparent' }}" href="{{ route('home') }}">{{ __('Home') }}</a>
                    <a class="rounded-lg px-3 py-2.5 lg:px-1 lg:py-1 {{ request()->routeIs('zones.*') ? 'bg-emerald-100 text-emerald-700 lg:bg-transparent' : 'hover:bg-white hover:text-emerald-700 lg:hover:bg-transparent' }}" href="{{ route('zones.index') }}">{{ __('Areas') }}</a>
                    <a class="rounded-lg px-3 py-2.5 lg:px-1 lg:py-1 {{ request()->routeIs('schedules.*') ? 'bg-emerald-100 text-emerald-700 lg:bg-transparent' : 'hover:bg-white hover:text-emerald-700 lg:hover:bg-transparent' }}" href="{{ route('schedules.index') }}">{{ __('Schedules') }}</a>
                    <a class="rounded-lg px-3 py-2.5 lg:px-1 lg:py-1 {{ request()->routeIs('outages.*') ? 'bg-emerald-100 text-emerald-700 lg:bg-transparent' : 'hover:bg-white hover:text-emerald-700 lg:hover:bg-transparent' }}" href="{{ route('outages.index') }}">{{ __('Outages') }}</a>
                    <a class="rounded-lg px-3 py-2.5 lg:px-1 lg:py-1 {{ request()->routeIs('announcements.*') ? 'bg-emerald-100 text-emerald-700 lg:bg-transparent' : 'hover:bg-white hover:text-emerald-700 lg:hover:bg-transparent' }}" href="{{ route('announcements.index') }}">{{ __('Notices') }}</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="mx-auto min-h-[70vh] max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900" role="alert">
                <p class="font-bold">{{ __('Please review the following:') }}</p>
                <ul class="mb-0 mt-1 list-disc pl-5">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-sm text-slate-500 sm:px-6 md:flex-row md:items-center md:justify-between lg:px-8">
            <p>&copy; {{ date('Y') }} PowerStat BD. {{ __('Community information portal.') }}</p>
            <p>{{ __('Expected restoration times are estimates, not guarantees.') }}</p>
        </div>
    </footer>
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
