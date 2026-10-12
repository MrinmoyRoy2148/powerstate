@extends('layouts.app')

@section('title', __('Power updates by area'))

@section('content')
    <section class="relative overflow-hidden rounded-[2rem] bg-slate-950 px-6 py-10 text-white shadow-xl sm:px-10 sm:py-14">
        <div class="absolute -right-16 -top-20 size-72 rounded-full bg-emerald-500/20 blur-3xl"></div>
        <div class="relative grid gap-10 lg:grid-cols-[1.1fr_.9fr] lg:items-center">
            <div>
                <p class="mb-4 inline-flex rounded-full border border-emerald-300/30 bg-emerald-400/10 px-3 py-1 text-xs font-bold text-emerald-300">{{ __('Reliable local updates') }}</p>
                <h1 class="max-w-2xl text-4xl font-black leading-tight tracking-tight sm:text-5xl">{{ __('Know what’s happening with power in your area.') }}</h1>
                <p class="mt-5 max-w-xl text-base leading-7 text-slate-300">{{ __('Find published load-shedding schedules, recorded outages, estimated restoration times, and service notices in one place.') }}</p>
                <form method="GET" action="{{ route('zones.index') }}" class="mt-8 flex flex-col gap-3 rounded-2xl bg-white p-2 sm:max-w-xl sm:flex-row">
                    <label for="search" class="sr-only">{{ __('Search by area name') }}</label>
                    <input id="search" name="search" type="search" maxlength="120" placeholder="{{ __('Search your area, e.g. Mirpur') }}" class="min-w-0 flex-1 rounded-xl border-0 px-4 py-3 text-slate-900 outline-none ring-0 placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500">
                    <button class="rounded-xl bg-emerald-600 px-5 py-3 font-bold text-white transition hover:bg-emerald-700" type="submit">{{ __('Find my area') }}</button>
                </form>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                <a href="{{ route('outages.index') }}" class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur transition hover:bg-white/10">
                    <span class="text-3xl font-black text-emerald-300">{{ $activeOutages->count() }}</span>
                    <span class="mt-2 block text-sm text-slate-300">{{ __('Active outages shown') }}</span>
                </a>
                <a href="{{ route('schedules.index') }}" class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur transition hover:bg-white/10">
                    <span class="text-3xl font-black text-emerald-300">{{ $upcomingSchedules->count() }}</span>
                    <span class="mt-2 block text-sm text-slate-300">{{ __('Upcoming schedules') }}</span>
                </a>
                <a href="{{ route('zones.index') }}" class="col-span-2 rounded-2xl border border-white/10 bg-gradient-to-r from-emerald-500/20 to-cyan-500/10 p-5">
                    <span class="text-sm font-bold text-white">{{ __('Area-first information') }}</span>
                    <span class="mt-1 block text-sm text-slate-300">{{ __('Browse active service areas and their latest updates') }} <span aria-hidden="true">→</span></span>
                </a>
            </div>
        </div>
    </section>

    <div class="mt-10 grid gap-10 lg:grid-cols-2">
        <section>
            <div class="mb-4 flex items-end justify-between gap-4">
                <div><p class="text-xs font-bold text-rose-600">{{ __('Happening now') }}</p><h2 class="mt-1 text-2xl font-extrabold text-slate-950">{{ __('Current outages') }}</h2></div>
                <a class="text-sm font-bold text-emerald-700 hover:text-emerald-900" href="{{ route('outages.index') }}">{{ __('View all') }}</a>
            </div>
            <div class="grid gap-3">
                @forelse ($activeOutages as $outage)
                    <a href="{{ route('outages.show', $outage) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-emerald-300 hover:shadow-md">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <h3 class="font-bold text-slate-950">{{ $outage->title }}</h3>
                            <x-status-badge :value="$outage->outage_type" />
                        </div>
                        <p class="mt-2 text-sm text-slate-600">{{ $outage->zone->name }} · {{ __('Started') }} {{ $outage->started_at->locale(app()->getLocale())->translatedFormat('d M, h:i A') }}</p>
                        <p class="mt-2 text-sm text-slate-500">{{ __('Expected restoration:') }} {{ $outage->expected_restoration_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Not provided') }} {{ __('(estimate)') }}</p>
                    </a>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-600">{{ __('No active outages are currently recorded.') }}</div>
                @endforelse
            </div>
        </section>

        <section>
            <div class="mb-4 flex items-end justify-between gap-4">
                <div><p class="text-xs font-bold text-indigo-600">{{ __('Plan ahead') }}</p><h2 class="mt-1 text-2xl font-extrabold text-slate-950">{{ __('Upcoming schedules') }}</h2></div>
                <a class="text-sm font-bold text-emerald-700 hover:text-emerald-900" href="{{ route('schedules.index') }}">{{ __('View all') }}</a>
            </div>
            <div class="grid gap-3">
                @forelse ($upcomingSchedules as $schedule)
                    <a href="{{ route('zones.show', $schedule->zone) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-emerald-300 hover:shadow-md">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <h3 class="font-bold text-slate-950">{{ $schedule->title }}</h3>
                            <span class="rounded-lg bg-indigo-50 px-3 py-1 text-sm font-bold text-indigo-800">{{ $schedule->scheduled_date->locale(app()->getLocale())->translatedFormat('d M') }}</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-600">{{ $schedule->zone->name }} · {{ $schedule->start_time }}–{{ $schedule->end_time }}</p>
                    </a>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-600">{{ __('No upcoming schedules are currently published.') }}</div>
                @endforelse
            </div>
        </section>
    </div>

    <section class="mt-10">
        <div class="mb-4 flex items-end justify-between gap-4">
            <div><p class="text-xs font-bold text-amber-600">{{ __('Updates') }}</p><h2 class="mt-1 text-2xl font-extrabold text-slate-950">{{ __('Latest announcements') }}</h2></div>
            <a class="text-sm font-bold text-emerald-700 hover:text-emerald-900" href="{{ route('announcements.index') }}">{{ __('All notices') }}</a>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($announcements as $announcement)
                <a href="{{ route('announcements.show', $announcement) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-xs font-semibold text-slate-500">{{ $announcement->zone?->name ?? __('All areas') }} · {{ $announcement->published_at?->locale(app()->getLocale())->translatedFormat('d M Y') ?? __('Published') }}</p>
                    <h3 class="mt-2 font-bold text-slate-950">{{ $announcement->title }}</h3>
                    <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">{{ $announcement->body }}</p>
                </a>
            @empty
                <p class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-600 sm:col-span-2 lg:col-span-3">{{ __('No announcements are currently published.') }}</p>
            @endforelse
        </div>
    </section>
@endsection
