@extends('layouts.app')

@section('title', __('Browse areas'))

@section('content')
    <x-admin-page-header :title="__('Find your area')" :description="__('Browse active service areas to check their schedules and outage updates.')" />
    <form method="GET" action="{{ route('zones.index') }}" class="mb-6 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row">
        <label for="search" class="sr-only">{{ __('Search area name') }}</label>
        <input id="search" name="search" value="{{ request('search') }}" maxlength="120" type="search" placeholder="{{ __('Search areas by name') }}" class="min-w-0 flex-1 rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
        <button class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700" type="submit">{{ __('Search') }}</button>
    </form>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($zones as $zone)
            <a href="{{ route('zones.show', $zone) }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md">
                <p class="text-xs font-bold text-emerald-700">{{ $zone->parent?->name ?? __('Service area') }}</p>
                <h2 class="mt-2 text-xl font-extrabold text-slate-950 group-hover:text-emerald-700">{{ $zone->name }}</h2>
                <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-600">{{ $zone->description ?: __('View published schedules and current outage updates for this area.') }}</p>
                <span class="mt-5 inline-flex text-sm font-bold text-emerald-700">{{ __('View area') }} <span class="ml-2" aria-hidden="true">→</span></span>
            </a>
        @empty
            <p class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-slate-600 sm:col-span-2 lg:col-span-3">{{ __('No active areas match your search.') }}</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $zones->links() }}</div>
@endsection
