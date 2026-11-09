@extends('layouts.admin')
@section('title', __('Outages'))
@section('content')
    <x-admin-page-header :title="__('Outage reports')" :description="__('Review current incidents and manage restoration updates.')" :action="route('admin.outages.create')" :action-label="__('Report outage')" />
    <form method="GET" action="{{ route('admin.outages.index') }}" class="card card-body mb-3">
        <div class="row g-3 align-items-end">
            <div class="col-sm-6 col-lg-3"><label for="search" class="form-label">{{ __('Search') }}</label><input id="search" name="search" type="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Title or reason') }}"></div>
            <div class="col-sm-6 col-lg-2"><label for="status" class="form-label">{{ __('labels.status') }}</label><select id="status" name="status" class="form-select"><option value="">{{ __('All statuses') }}</option>@foreach (['active', 'restored', 'cancelled'] as $value)<option value="{{ $value }}" @selected(request('status') === $value)>{{ __('status.'.$value) }}</option>@endforeach</select></div>
            <div class="col-sm-6 col-lg-2"><label for="zone" class="form-label">{{ __('Area') }}</label><select id="zone" name="zone" class="form-select"><option value="">{{ __('All areas') }}</option>@foreach ($zones as $zone)<option value="{{ $zone->id }}" @selected((string) request('zone') === (string) $zone->id)>{{ $zone->name }}</option>@endforeach</select></div>
            <div class="col-sm-6 col-lg-2"><label for="date" class="form-label">{{ __('Date') }}</label><input id="date" name="date" type="date" value="{{ request('date') }}" class="form-control"></div>
            <div class="col-sm-6 col-lg-3 d-flex gap-2"><button class="btn btn-primary" type="submit">{{ __('Filter') }}</button>
                @if (request()->hasAny(['status', 'zone', 'date', 'search']))<a href="{{ route('admin.outages.index') }}" class="btn btn-outline-secondary">{{ __('Clear') }}</a>@endif
            </div>
        </div>
    </form>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>{{ __('Outage') }}</th><th>{{ __('Area') }}</th><th>{{ __('Started') }}</th><th>{{ __('Expected restoration') }}</th><th>{{ __('Actual restoration') }}</th><th>{{ __('labels.status') }}</th><th>{{ __('Actions') }}</th></tr></thead>
                <tbody>
                    @forelse ($outages as $outage)
                        <tr><td><strong>{{ $outage->title }}</strong><div class="small text-secondary">{{ __('status.'.$outage->outage_type) }}{{ $outage->reason ? ' · '.$outage->reason->name : '' }}</div></td><td>{{ $outage->zone->name }}</td><td>{{ $outage->started_at->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') }}</td><td>{{ $outage->expected_restoration_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Not provided') }}</td><td>{{ $outage->restored_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? '—' }}</td><td><x-status-badge :value="$outage->status" /></td><td><div class="d-flex flex-wrap gap-2"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.outages.show', $outage) }}">{{ __('View') }}</a><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.outages.edit', $outage) }}">{{ __('Edit') }}</a></div></td></tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-secondary py-5">{{ __('No outages match this filter.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5">{{ $outages->links() }}</div>
@endsection
