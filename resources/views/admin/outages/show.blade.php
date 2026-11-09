@extends('layouts.admin')
@section('title', $outage->title)
@section('content')
    <div class="mb-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <a href="{{ route('admin.outages.index') }}" class="btn btn-link">{{ __('← All outages') }}</a>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.outages.edit', $outage) }}" class="btn btn-outline-primary">{{ __('Edit outage') }}</a>
            @if ($outage->status === 'active')
                <form method="POST" action="{{ route('admin.outages.restore', $outage) }}" onsubmit="return confirm(@js(__('Mark this outage as restored now?')))">@csrf @method('PATCH')<button class="btn btn-success" type="submit">{{ __('Mark restored') }}</button></form>
            @endif
        </div>
    </div>
    <article class="card shadow-sm">
        <div class="card-header bg-white"><div class="d-flex flex-wrap gap-2"><x-status-badge :value="$outage->status" /><x-status-badge :value="$outage->outage_type" /></div><h1 class="h3 fw-bold mt-3">{{ $outage->title }}</h1><p class="text-secondary mb-0">{{ $outage->zone->name }} · {{ __('Reported by') }} {{ $outage->reporter?->name ?? __('Administrator') }}</p></div>
        <dl class="card-body row g-3 mb-0">
            <div class="col-md-6"><dt class="text-secondary small">{{ __('Started') }}</dt><dd class="fw-semibold">{{ $outage->started_at->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') }}</dd></div>
            <div class="col-md-6"><dt class="text-secondary small">{{ __('Expected restoration') }}</dt><dd class="fw-semibold">{{ $outage->expected_restoration_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Not provided') }}</dd></div>
            <div class="col-md-6"><dt class="text-secondary small">{{ __('Actual restoration') }}</dt><dd class="fw-semibold">{{ $outage->restored_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Not restored') }}</dd></div>
            <div class="col-md-6"><dt class="text-secondary small">{{ __('Reason') }}</dt><dd class="fw-semibold">{{ $outage->reason?->name ?? __('Not specified') }}</dd></div>
            <div class="col-md-6"><dt class="text-secondary small">{{ __('Related schedule') }}</dt><dd class="fw-semibold">{{ $outage->schedule?->title ?? __('None') }}</dd></div>
            <div class="col-md-6"><dt class="text-secondary small">{{ __('Last updated') }}</dt><dd class="fw-semibold">{{ $outage->updated_at->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') }}</dd></div>
            <div class="col-12"><dt class="text-secondary small">{{ __('Description') }}</dt><dd class="text-break mb-0">{{ $outage->description ?: __('No additional details were provided.') }}</dd></div>
        </dl>
    </article>
@endsection
