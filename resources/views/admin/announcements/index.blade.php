@extends('layouts.admin')
@section('title', __('Announcements'))
@section('content')
    <x-admin-page-header :title="__('Announcements')" :description="__('Draft and publish service notices for a specific area or everyone.')" :action="route('admin.announcements.create')" :action-label="__('Add announcement')" />
    <form method="GET" action="{{ route('admin.announcements.index') }}" class="card card-body mb-3">
        <div class="row g-3 align-items-end">
            <div class="col-sm-6 col-lg-3"><label for="search" class="form-label">{{ __('Search') }}</label><input id="search" name="search" type="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Title or message') }}"></div>
            <div class="col-sm-6 col-lg-3"><label for="zone_id" class="form-label">{{ __('Audience') }}</label><select id="zone_id" name="zone_id" class="form-select"><option value="">{{ __('All areas') }}</option>@foreach ($zones as $zone)<option value="{{ $zone->id }}" @selected((string) request('zone_id') === (string) $zone->id)>{{ $zone->name }}</option>@endforeach</select></div>
            <div class="col-sm-6 col-lg-3"><label for="status" class="form-label">{{ __('labels.status') }}</label><select id="status" name="status" class="form-select"><option value="">{{ __('All statuses') }}</option><option value="draft" @selected(request('status') === 'draft')>{{ __('status.draft') }}</option><option value="published" @selected(request('status') === 'published')>{{ __('status.published') }}</option><option value="expired" @selected(request('status') === 'expired')>{{ __('Expired') }}</option></select></div>
            <div class="col-sm-6 col-lg-3 d-flex gap-2"><button class="btn btn-primary" type="submit">{{ __('Filter') }}</button>
                @if (request()->hasAny(['status', 'zone_id', 'search']))<a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">{{ __('Clear') }}</a>@endif
            </div>
        </div>
    </form>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>{{ __('Announcement') }}</th><th>{{ __('Audience') }}</th><th>{{ __('Publication') }}</th><th>{{ __('Author') }}</th><th>{{ __('Actions') }}</th></tr></thead>
                <tbody>
                    @forelse ($announcements as $announcement)
                        <tr><td><strong>{{ $announcement->title }}</strong><div class="small text-secondary text-truncate" style="max-width: 24rem">{{ $announcement->body }}</div></td><td>{{ $announcement->zone?->name ?? __('All areas') }}</td><td>@if ($announcement->status === 'published' && $announcement->expires_at?->isPast())<span class="badge text-bg-secondary">{{ __('Expired') }}</span>@else<x-status-badge :value="$announcement->status" />@endif<div class="small text-secondary">{{ __('Publish at') }}: {{ $announcement->published_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Not scheduled') }}</div><div class="small text-secondary">{{ __('Expire at') }}: {{ $announcement->expires_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? '—' }}</div></td><td>{{ $announcement->creator?->name ?? '—' }}</td><td><div class="d-flex flex-wrap gap-2"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.announcements.edit', $announcement) }}">{{ __('Edit') }}</a>@if ($announcement->status === 'published')<form method="POST" action="{{ route('admin.announcements.unpublish', $announcement) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-secondary" type="submit">{{ __('Unpublish') }}</button></form>@else<form method="POST" action="{{ route('admin.announcements.publish', $announcement) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-success" type="submit">{{ __('Publish') }}</button></form>@endif<form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm(@js(__('Delete this announcement?')))">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit">{{ __('Delete') }}</button></form></div></td></tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-5">{{ __('No announcements match this filter.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5">{{ $announcements->links() }}</div>
@endsection
