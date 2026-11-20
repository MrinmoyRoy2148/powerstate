@extends('layouts.app')

@section('title', __('Saved areas'))

@section('content')
    <x-admin-page-header :title="__('Saved areas')" :description="__('Areas you selected for quick access.')" />
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($zones as $zone)
            <a href="{{ route('zones.show', $zone) }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-extrabold text-slate-950">{{ $zone->name }}</h2>
                <p class="mt-3 text-sm text-slate-600">{{ __('View schedules and outages') }}</p>
            </a>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-slate-600 sm:col-span-2 lg:col-span-3">
                <p class="font-bold">{{ __('No saved areas yet.') }}</p>
                <p class="mt-2 text-sm">{{ __('Browse areas and save one to find its updates quickly.') }}</p>
                <a href="{{ route('zones.index') }}" class="mt-4 inline-flex rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white">{{ __('Browse areas') }}</a>
            </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $zones->links() }}</div>
@endsection
