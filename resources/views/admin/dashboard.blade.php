@extends('layouts.admin')

@section('title', __('Dashboard'))

@section('content')
    <x-admin-page-header :title="__('Dashboard')" :description="__('A quick overview of the latest recorded power information.')" />
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['label' => __('Service areas'), 'value' => $zoneCount, 'route' => 'admin.zones.index', 'tone' => 'text-emerald-700'],
            ['label' => __('Upcoming schedules'), 'value' => $upcomingScheduleCount, 'route' => 'admin.schedules.index', 'tone' => 'text-indigo-700'],
            ['label' => __('Active outages'), 'value' => $activeOutageCount, 'route' => 'admin.outages.index', 'tone' => 'text-rose-700'],
            ['label' => __('Published notices'), 'value' => $publishedAnnouncementCount, 'route' => 'admin.announcements.index', 'tone' => 'text-amber-700'],
        ] as $stat)
            <a href="{{ route($stat['route']) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-sm font-semibold text-slate-500">{{ $stat['label'] }}</p>
                <p class="mt-2 text-3xl font-black {{ $stat['tone'] }}">{{ $stat['value'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4"><h2 class="font-extrabold text-slate-950">{{ __('Recent outages') }}</h2><a class="text-sm font-bold text-emerald-700" href="{{ route('admin.outages.index') }}">{{ __('Manage') }}</a></div>
            <div class="divide-y divide-slate-100">
                @forelse ($recentOutages as $outage)
                    <a href="{{ route('admin.outages.show', $outage) }}" class="flex items-center justify-between gap-4 px-5 py-4 hover:bg-slate-50">
                        <div><p class="font-bold text-slate-900">{{ $outage->title }}</p><p class="mt-1 text-sm text-slate-500">{{ $outage->zone->name }} · {{ $outage->started_at->locale(app()->getLocale())->translatedFormat('d M, h:i A') }}</p></div>
                        <x-status-badge :value="$outage->status" />
                    </a>
                @empty
                    <p class="p-5 text-sm text-slate-500">{{ __('No outages have been reported.') }}</p>
                @endforelse
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4"><h2 class="font-extrabold text-slate-950">{{ __('Upcoming schedules') }}</h2><a class="text-sm font-bold text-emerald-700" href="{{ route('admin.schedules.index') }}">{{ __('Manage') }}</a></div>
            <div class="divide-y divide-slate-100">
                @forelse ($upcomingSchedules as $schedule)
                    <div class="px-5 py-4"><p class="font-bold text-slate-900">{{ $schedule->title }}</p><p class="mt-1 text-sm text-slate-500">{{ $schedule->zone->name }} · {{ $schedule->scheduled_date->locale(app()->getLocale())->translatedFormat('d M Y') }} · {{ $schedule->start_time }}–{{ $schedule->end_time }}</p></div>
                @empty
                    <p class="p-5 text-sm text-slate-500">{{ __('No upcoming published schedules.') }}</p>
                @endforelse
            </div>
        </section>
    </div>

    <section class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4"><h2 class="font-extrabold text-slate-950">{{ __('Latest published announcements') }}</h2><a class="text-sm font-bold text-emerald-700" href="{{ route('admin.announcements.index') }}">{{ __('Manage') }}</a></div>
        <div class="grid gap-px bg-slate-100 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($latestAnnouncements as $announcement)
                <div class="bg-white p-5"><p class="text-xs text-slate-500">{{ $announcement->published_at?->locale(app()->getLocale())->translatedFormat('d M Y') ?? __('Published') }}</p><p class="mt-1 font-bold">{{ $announcement->title }}</p></div>
            @empty
                <p class="bg-white p-5 text-sm text-slate-500 sm:col-span-2 lg:col-span-3">{{ __('No published announcements.') }}</p>
            @endforelse
        </div>
    </section>
@endsection
