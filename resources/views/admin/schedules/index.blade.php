@extends('layouts.admin')
@section('title', __('Schedules'))
@section('content')
    <x-admin-page-header :title="__('Load-shedding schedules')" :description="__('Create and publish planned service interruptions by area.')" :action="route('admin.schedules.create')" :action-label="__('Add schedule')" />
    <nav aria-label="{{ __('Schedule periods') }}" class="nav nav-pills mb-3">
        @foreach (['upcoming' => __('Upcoming'), 'past' => __('Past'), 'all' => __('All schedules')] as $value => $label)
            <a href="{{ route('admin.schedules.index', array_merge(request()->except('page'), ['period' => $value])) }}"
                @class(['nav-link', 'active' => $period === $value]) @if ($period === $value) aria-current="page" @endif>
                {{ $label }}
            </a>
        @endforeach
    </nav>
    <form method="GET" action="{{ route('admin.schedules.index') }}" class="card card-body mb-3">
        <input type="hidden" name="period" value="{{ $period }}">
        <div class="row g-3 align-items-end">
        <div class="col-sm-6 col-lg-3">
            <label for="search" class="form-label">{{ __('Search') }}</label>
            <input id="search" name="search" type="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Schedule title') }}">
        </div>
        <div class="col-sm-6 col-lg-3">
            <label for="zone_id" class="form-label">{{ __('Service area') }}</label>
            <select id="zone_id" name="zone_id" class="form-select">
                <option value="">{{ __('All areas') }}</option>
                @foreach ($zones as $zone)
                    <option value="{{ $zone->id }}" @selected((string) request('zone_id') === (string) $zone->id)>{{ $zone->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-6 col-lg-3">
            <label for="scheduled_date" class="form-label">{{ __('Date') }}</label>
            <input id="scheduled_date" name="scheduled_date" type="date" value="{{ request('scheduled_date') }}" class="form-control">
        </div>
        <div class="col-sm-6 col-lg-3">
            <label for="status" class="form-label">{{ __('labels.status') }}</label>
            <select id="status" name="status" class="form-select">
                <option value="">{{ __('All statuses') }}</option>
                @foreach (['draft', 'published', 'cancelled'] as $value)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ __('status.'.$value) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-6 col-lg-3 d-flex gap-2">
            <button class="btn btn-primary" type="submit">{{ __('Filter') }}</button>
            @if (request()->hasAny(['search', 'zone_id', 'scheduled_date', 'status']))
                <a href="{{ route('admin.schedules.index', ['period' => $period]) }}" class="btn btn-outline-secondary">{{ __('Clear') }}</a>
            @endif
        </div>
        </div>
    </form>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>{{ __('Schedule') }}</th><th>{{ __('Area') }}</th><th>{{ __('Date and time') }}</th><th>{{ __('labels.status') }}</th><th>{{ __('Actions') }}</th></tr></thead>
                <tbody>
                    @forelse ($schedules as $schedule)
                        <tr><td><strong>{{ $schedule->title }}</strong><div class="small text-secondary">{{ __('By') }} {{ $schedule->creator?->name ?? __('Administrator') }}</div></td><td>{{ $schedule->zone->name }}</td><td>{{ $schedule->scheduled_date->locale(app()->getLocale())->translatedFormat('d M Y') }}<div class="small text-secondary">{{ $schedule->start_time }}–{{ $schedule->end_time }}</div></td><td><x-status-badge :value="$schedule->status" /></td><td><div class="d-flex flex-wrap gap-2"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.schedules.edit', $schedule) }}">{{ __('Edit') }}</a>@if ($schedule->status !== 'published')<form method="POST" action="{{ route('admin.schedules.publish', $schedule) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-success" type="submit">{{ __('Publish') }}</button></form>@endif @if ($schedule->status !== 'cancelled')<form method="POST" action="{{ route('admin.schedules.cancel', $schedule) }}" onsubmit="return confirm(@js(__('Cancel this schedule?')))">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-danger" type="submit">{{ __('Cancel') }}</button></form>@endif <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" onsubmit="return confirm(@js(__('Delete this schedule?')))">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-secondary" type="submit">{{ __('Delete') }}</button></form></div></td></tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-5">{{ __('No schedules match this filter.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5">{{ $schedules->links() }}</div>
@endsection
