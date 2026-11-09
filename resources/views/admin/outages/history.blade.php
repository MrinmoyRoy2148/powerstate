@extends('layouts.admin')
@section('title', __('Outage history'))
@section('content')
    <x-admin-page-header :title="__('Outage history')" :description="__('Browse previous outage records without removing them from the system.')" />
    <form method="GET" action="{{ route('admin.outages.history') }}" class="card card-body mb-3">
        <div class="row g-3 align-items-end">
            <div class="col-sm-6 col-lg-3">
                <label for="zone" class="form-label">{{ __('Area') }}</label>
                <select id="zone" name="zone" class="form-select">
                    <option value="">{{ __('All areas') }}</option>
                    @foreach ($zones as $zone)
                        <option value="{{ $zone->id }}" @selected((string) request('zone') === (string) $zone->id)>{{ $zone->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-lg-3">
                <label for="outage_type" class="form-label">{{ __('Type') }}</label>
                <select id="outage_type" name="outage_type" class="form-select">
                    <option value="">{{ __('All types') }}</option>
                    @foreach (['planned', 'unexpected'] as $type)
                        <option value="{{ $type }}" @selected(request('outage_type') === $type)>{{ __('status.'.$type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6 col-lg-2">
                <label for="start_date" class="form-label">{{ __('From date') }}</label>
                <input id="start_date" name="start_date" type="date" value="{{ request('start_date') }}" class="form-control">
            </div>
            <div class="col-sm-6 col-lg-2">
                <label for="end_date" class="form-label">{{ __('To date') }}</label>
                <input id="end_date" name="end_date" type="date" value="{{ request('end_date') }}" class="form-control">
            </div>
            <div class="col-sm-6 col-lg-2 d-flex gap-2">
                <button class="btn btn-primary" type="submit">{{ __('Filter') }}</button>
                @if (request()->hasAny(['zone', 'outage_type', 'start_date', 'end_date']))
                    <a href="{{ route('admin.outages.history') }}" class="btn btn-outline-secondary">{{ __('Clear') }}</a>
                @endif
            </div>
        </div>
    </form>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>{{ __('Outage') }}</th><th>{{ __('Area') }}</th><th>{{ __('Reason') }}</th><th>{{ __('Type') }}</th><th>{{ __('Started') }}</th><th>{{ __('Restored') }}</th><th>{{ __('Duration') }}</th><th>{{ __('labels.status') }}</th><th>{{ __('Actions') }}</th></tr>
                </thead>
                <tbody>
                    @forelse ($outages as $outage)
                        <tr>
                            <td><strong>{{ $outage->title }}</strong></td>
                            <td>{{ $outage->zone->name }}</td>
                            <td>{{ $outage->reason?->name ?? __('Reason not provided') }}</td>
                            <td><x-status-badge :value="$outage->outage_type" /></td>
                            <td>{{ $outage->started_at->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') }}</td>
                            <td>{{ $outage->restored_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Not recorded') }}</td>
                            <td>
                                @if ($outage->duration_minutes !== null)
                                    @if ($outage->duration_minutes < 1)
                                        {{ __('Less than a minute') }}
                                    @else
                                        @php
                                            $durationDays = intdiv($outage->duration_minutes, 1440);
                                            $durationHours = intdiv($outage->duration_minutes % 1440, 60);
                                            $durationMinutes = $outage->duration_minutes % 60;
                                            $durationParts = [];
                                            if ($durationDays > 0) { $durationParts[] = $durationDays.'d'; }
                                            if ($durationHours > 0) { $durationParts[] = $durationHours.'h'; }
                                            if ($durationMinutes > 0) { $durationParts[] = $durationMinutes.'m'; }
                                        @endphp
                                        {{ implode(' ', $durationParts) }}
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td><x-status-badge :value="$outage->status" /></td>
                            <td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.outages.show', $outage) }}">{{ __('View') }}</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-secondary py-5">{{ __('No historical outages match those filters.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $outages->links() }}</div>
@endsection
