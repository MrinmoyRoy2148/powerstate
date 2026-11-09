@extends('layouts.admin')
@section('title', __('Dashboard'))
@section('content')
    <x-admin-page-header :title="__('Dashboard')" :description="__('A quick overview of the latest recorded power information.')" />
    <div class="row g-3">
        @foreach ([
            ['label' => __('Service areas'), 'value' => $zoneCount, 'route' => 'admin.zones.index'],
            ['label' => __('Upcoming schedules'), 'value' => $upcomingScheduleCount, 'route' => 'admin.schedules.index'],
            ['label' => __('Active outages'), 'value' => $activeOutageCount, 'route' => 'admin.outages.index'],
            ['label' => __('Published notices'), 'value' => $publishedAnnouncementCount, 'route' => 'admin.announcements.index'],
        ] as $stat)
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route($stat['route']) }}" class="card h-100 text-decoration-none shadow-sm">
                    <div class="card-body">
                        <div class="small fw-semibold text-secondary">{{ $stat['label'] }}</div>
                        <div class="display-6 fw-bold text-success">{{ $stat['value'] }}</div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <section class="mt-4">
        <h2 class="h5 fw-bold">{{ __('Quick actions') }}</h2>
        <div class="row g-2">
            @foreach ([
                ['label' => __('Add area'), 'route' => 'admin.zones.create'],
                ['label' => __('Add schedule'), 'route' => 'admin.schedules.create'],
                ['label' => __('Report outage'), 'route' => 'admin.outages.create'],
                ['label' => __('Add announcement'), 'route' => 'admin.announcements.create'],
            ] as $action)
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route($action['route']) }}" class="btn btn-outline-success w-100">{{ $action['label'] }}</a>
                </div>
            @endforeach
        </div>
    </section>

    <div class="row g-4 mt-1">
        <section class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h2 class="h6 fw-bold mb-0">{{ __('Recent outages') }}</h2>
                    <a class="small" href="{{ route('admin.outages.index') }}">{{ __('Manage') }}</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($recentOutages as $outage)
                        <a href="{{ route('admin.outages.show', $outage) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center gap-3">
                            <span><strong>{{ $outage->title }}</strong><span class="d-block small text-secondary">{{ $outage->zone->name }} · {{ $outage->started_at->locale(app()->getLocale())->translatedFormat('d M, h:i A') }}</span></span>
                            <x-status-badge :value="$outage->status" />
                        </a>
                    @empty
                        <p class="list-group-item text-secondary mb-0">{{ __('No outages have been reported.') }}</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h2 class="h6 fw-bold mb-0">{{ __('Upcoming schedules') }}</h2>
                    <a class="small" href="{{ route('admin.schedules.index') }}">{{ __('Manage') }}</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($upcomingSchedules as $schedule)
                        <div class="list-group-item"><strong>{{ $schedule->title }}</strong><span class="d-block small text-secondary">{{ $schedule->zone->name }} · {{ $schedule->scheduled_date->locale(app()->getLocale())->translatedFormat('d M Y') }} · {{ $schedule->start_time }}–{{ $schedule->end_time }}</span></div>
                    @empty
                        <p class="list-group-item text-secondary mb-0">{{ __('No upcoming published schedules.') }}</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>

    <section class="card mt-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h2 class="h6 fw-bold mb-0">{{ __('Latest published announcements') }}</h2>
            <a class="small" href="{{ route('admin.announcements.index') }}">{{ __('Manage') }}</a>
        </div>
        <div class="row g-0">
            @forelse ($latestAnnouncements as $announcement)
                <div class="col-sm-6 col-xl-4 border-bottom p-3">
                    <div class="small text-secondary">{{ $announcement->published_at?->locale(app()->getLocale())->translatedFormat('d M Y') ?? __('Published') }}</div>
                    <strong>{{ $announcement->title }}</strong>
                </div>
            @empty
                <p class="p-3 text-secondary mb-0">{{ __('No published announcements.') }}</p>
            @endforelse
        </div>
    </section>
@endsection
