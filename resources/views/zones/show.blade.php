@extends('layouts.app')

@section('title', $zone->name)

@section('content')
    <a href="{{ route('zones.index') }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-900">{{ __('← All areas') }}</a>
    <section class="mt-4 rounded-3xl bg-slate-950 px-6 py-8 text-white sm:px-9">
        <p class="text-xs font-bold text-emerald-300">{{ $zone->parent?->name ?? __('Service area') }}</p>
        <h1 class="mt-2 text-3xl font-black sm:text-4xl">{{ $zone->name }}</h1>
        @if ($zone->description)<p class="mt-3 max-w-2xl leading-7 text-slate-300">{{ $zone->description }}</p>@endif
    </section>

    <div class="mt-8 grid gap-8 lg:grid-cols-2">
        <section>
            <div class="mb-4 flex items-center justify-between"><h2 class="text-xl font-extrabold text-slate-950">{{ __('Active outages') }}</h2><a href="{{ route('outages.index', ['zone' => $zone->id]) }}" class="text-sm font-bold text-emerald-700">{{ __('All outages') }}</a></div>
            <div class="grid gap-3">
                @forelse ($zone->outages as $outage)
                    <a href="{{ route('outages.show', $outage) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-3"><h3 class="font-bold">{{ $outage->title }}</h3><x-status-badge :value="$outage->outage_type" /></div>
                        <p class="mt-2 text-sm text-slate-600">{{ __('Started') }} {{ $outage->started_at->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Expected restoration:') }} {{ $outage->expected_restoration_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Not provided') }} {{ __('(estimate)') }}</p>
                    </a>
                @empty
                    <p class="rounded-xl border border-dashed border-slate-300 bg-white p-5 text-sm text-slate-600">{{ __('No active outages are currently recorded for this area.') }}</p>
                @endforelse
            </div>
        </section>
        <section>
            <div class="mb-4 flex items-center justify-between"><h2 class="text-xl font-extrabold text-slate-950">{{ __('Upcoming schedules') }}</h2><a href="{{ route('schedules.index', ['zone' => $zone->id]) }}" class="text-sm font-bold text-emerald-700">{{ __('All schedules') }}</a></div>
            <div class="grid gap-3">
                @forelse ($zone->schedules as $schedule)
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h3 class="font-bold">{{ $schedule->title }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $schedule->scheduled_date->locale(app()->getLocale())->translatedFormat('d M Y') }} · {{ $schedule->start_time }}–{{ $schedule->end_time }}</p>
                        @if ($schedule->notes)<p class="mt-2 text-sm text-slate-500">{{ $schedule->notes }}</p>@endif
                    </article>
                @empty
                    <p class="rounded-xl border border-dashed border-slate-300 bg-white p-5 text-sm text-slate-600">{{ __('No upcoming schedules are published for this area.') }}</p>
                @endforelse
            </div>
        </section>
    </div>

    <section class="mt-8">
        <h2 class="mb-4 text-xl font-extrabold text-slate-950">{{ __('Announcements') }}</h2>
        <div class="grid gap-3 sm:grid-cols-2">
            @forelse ($zone->announcements->merge($announcements)->unique('id')->sortByDesc('published_at') as $announcement)
                <a href="{{ route('announcements.show', $announcement) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-emerald-300">
                    <p class="text-xs text-slate-500">{{ $announcement->zone?->name ?? __('All areas') }}</p>
                    <h3 class="mt-1 font-bold">{{ $announcement->title }}</h3>
                    <p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $announcement->body }}</p>
                </a>
            @empty
                <p class="rounded-xl border border-dashed border-slate-300 bg-white p-5 text-sm text-slate-600 sm:col-span-2">{{ __('No announcements are currently published for this area.') }}</p>
            @endforelse
        </div>
    </section>
@endsection
