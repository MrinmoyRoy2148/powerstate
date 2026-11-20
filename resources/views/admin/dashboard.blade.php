@extends('layouts.admin')
@section('title', __('Dashboard'))
@section('content')
    <x-admin-page-header :title="__('Dashboard')" :description="__('A quick overview of the latest recorded power information.')" />
    <section aria-label="{{ __('Dashboard') }}" class="mb-4">
        <div class="row g-3">
        @foreach ([
            ['label' => __('Service areas'), 'value' => $zoneCount, 'route' => 'admin.zones.index', 'tone' => 'green', 'icon' => 'areas'],
            ['label' => __('Upcoming schedules'), 'value' => $upcomingScheduleCount, 'route' => 'admin.schedules.index', 'tone' => 'blue', 'icon' => 'calendar'],
            ['label' => __('Active outages'), 'value' => $activeOutageCount, 'route' => 'admin.outages.index', 'tone' => 'amber', 'icon' => 'outage'],
            ['label' => __('Published notices'), 'value' => $publishedAnnouncementCount, 'route' => 'admin.announcements.index', 'tone' => 'violet', 'icon' => 'notice'],
        ] as $stat)
            <div class="col-sm-6 col-xxl-3">
                <a href="{{ route($stat['route']) }}" class="admin-stat-card admin-stat-card--{{ $stat['tone'] }}">
                    <span class="admin-stat-card__top">
                        <span class="admin-stat-card__label">{{ $stat['label'] }}</span>
                        <span class="admin-stat-card__icon" aria-hidden="true">
                            @if ($stat['icon'] === 'areas')
                                <svg viewBox="0 0 24 24" fill="none"><path d="M4 5.5 9 3l6 2.5L20 3v15.5L15 21l-6-2.5L4 21V5.5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" /><path d="M9 3v15.5m6-13V21" stroke="currentColor" stroke-width="1.7" /></svg>
                            @elseif ($stat['icon'] === 'calendar')
                                <svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="5" width="17" height="16" rx="2.5" stroke="currentColor" stroke-width="1.7" /><path d="M7.5 3v4m9-4v4M3.5 9.5h17" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" /></svg>
                            @elseif ($stat['icon'] === 'outage')
                                <svg viewBox="0 0 24 24" fill="none"><path d="m13.3 2.8-8 10.1a1 1 0 0 0 .8 1.6h5l-.6 6.7 8-10.1a1 1 0 0 0-.8-1.6h-5l.6-6.7Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" /></svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none"><path d="M5 4.5h14A2.5 2.5 0 0 1 21.5 7v9a2.5 2.5 0 0 1-2.5 2.5H10L4 21v-3.1A2.5 2.5 0 0 1 2.5 15V7A2.5 2.5 0 0 1 5 4.5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" /><path d="M7 9h10M7 13h7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" /></svg>
                            @endif
                        </span>
                    </span>
                    <span class="admin-stat-card__value">{{ $stat['value'] }}</span>
                    <span class="admin-stat-card__footer">{{ __('Manage') }} <span aria-hidden="true">→</span></span>
                </a>
            </div>
        @endforeach
        </div>
    </section>

    <section class="admin-quick-actions mb-4" aria-labelledby="quick-actions-heading">
        <div class="admin-section-heading">
            <h2 id="quick-actions-heading">{{ __('Quick actions') }}</h2>
        </div>
        <div class="row g-2 g-lg-3">
            @foreach ([
                ['label' => __('Add area'), 'route' => 'admin.zones.create'],
                ['label' => __('Add schedule'), 'route' => 'admin.schedules.create'],
                ['label' => __('Report outage'), 'route' => 'admin.outages.create'],
                ['label' => __('Add announcement'), 'route' => 'admin.announcements.create'],
            ] as $action)
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ route($action['route']) }}" class="admin-quick-action">
                        <span class="admin-quick-action__mark" aria-hidden="true">+</span>
                        <span class="admin-quick-action__label">{{ $action['label'] }}</span>
                        <span class="admin-quick-action__arrow" aria-hidden="true">→</span>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <div class="row g-3 g-xl-4">
        <section class="col-lg-6">
            <div class="admin-panel h-100">
                <div class="admin-panel__header">
                    <div>
                        <h2>{{ __('Recent outages') }}</h2>
                        <p>{{ __('Latest recorded service interruptions') }}</p>
                    </div>
                    <a class="admin-panel__link" href="{{ route('admin.outages.index') }}">{{ __('Manage') }} <span aria-hidden="true">→</span></a>
                </div>
                <div class="admin-record-list">
                    @forelse ($recentOutages as $outage)
                        <a href="{{ route('admin.outages.show', $outage) }}" class="admin-record-list__item">
                            <span class="admin-record-list__copy">
                                <strong>{{ $outage->title }}</strong>
                                <span>{{ $outage->zone->name }} · {{ $outage->started_at->locale(app()->getLocale())->translatedFormat('d M, h:i A') }}</span>
                            </span>
                            <x-status-badge :value="$outage->status" class="flex-shrink-0" />
                        </a>
                    @empty
                        <p class="admin-empty-state">{{ __('No outages have been reported.') }}</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="col-lg-6">
            <div class="admin-panel h-100">
                <div class="admin-panel__header">
                    <div>
                        <h2>{{ __('Upcoming schedules') }}</h2>
                        <p>{{ __('Published plans for upcoming dates') }}</p>
                    </div>
                    <a class="admin-panel__link" href="{{ route('admin.schedules.index') }}">{{ __('Manage') }} <span aria-hidden="true">→</span></a>
                </div>
                <div class="admin-record-list">
                    @forelse ($upcomingSchedules as $schedule)
                        <div class="admin-record-list__item">
                            <span class="admin-record-list__copy">
                                <strong>{{ $schedule->title }}</strong>
                                <span>{{ $schedule->zone->name }} · {{ $schedule->scheduled_date->locale(app()->getLocale())->translatedFormat('d M Y') }}</span>
                            </span>
                            <span class="admin-record-list__time">{{ $schedule->start_time }}–{{ $schedule->end_time }}</span>
                        </div>
                    @empty
                        <p class="admin-empty-state">{{ __('No upcoming published schedules.') }}</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>

    <section class="admin-panel mt-3 mt-xl-4" aria-labelledby="latest-announcements-heading">
        <div class="admin-panel__header">
            <div>
                <h2 id="latest-announcements-heading">{{ __('Latest published announcements') }}</h2>
                <p>{{ __('Published information visible to visitors') }}</p>
            </div>
            <a class="admin-panel__link" href="{{ route('admin.announcements.index') }}">{{ __('Manage') }} <span aria-hidden="true">→</span></a>
        </div>
        <div class="admin-announcement-grid">
            @forelse ($latestAnnouncements as $announcement)
                <div class="admin-announcement">
                    <span>{{ $announcement->published_at?->locale(app()->getLocale())->translatedFormat('d M Y') ?? __('Published') }}</span>
                    <strong>{{ $announcement->title }}</strong>
                </div>
            @empty
                <p class="admin-empty-state">{{ __('No published announcements.') }}</p>
            @endforelse
        </div>
    </section>
@endsection
