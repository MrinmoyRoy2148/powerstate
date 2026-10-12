@extends('layouts.app')

@section('title', __('Current outages'))

@section('content')
    <x-admin-page-header :title="__('Current outages')" :description="__('Active interruptions recorded by authorized staff. Restoration times are estimates.')" />
    <form method="GET" action="{{ route('outages.index') }}" class="mb-6 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-end">
        <div class="flex-1">
            <label for="zone" class="mb-1 block text-sm font-semibold text-slate-700">{{ __('Filter by area') }}</label>
            <select id="zone" name="zone" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <option value="">{{ __('All areas') }}</option>
                @foreach ($zones as $zone)<option value="{{ $zone->id }}" @selected((string) request('zone') === (string) $zone->id)>{{ $zone->name }}</option>@endforeach
            </select>
        </div>
        <button class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-700" type="submit">{{ __('Filter') }}</button>
        <a href="{{ route('outages.history') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-center text-sm font-bold text-slate-700 hover:bg-slate-50">{{ __('View outage history') }}</a>
    </form>
    <div class="grid gap-4 md:grid-cols-2">
        @forelse ($outages as $outage)
            <a href="{{ route('outages.show', $outage) }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-emerald-300 hover:shadow-md">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div><p class="text-xs font-bold uppercase tracking-widest text-slate-500">{{ $outage->zone->name }}</p><h2 class="mt-1 text-xl font-extrabold text-slate-950">{{ $outage->title }}</h2></div>
                    <div class="flex gap-2"><x-status-badge value="active" /><x-status-badge :value="$outage->outage_type" /></div>
                </div>
                <p class="mt-4 text-sm text-slate-600">{{ $outage->reason?->name ?? __('Reason not provided') }} · {{ __('Started') }} {{ $outage->started_at->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') }}</p>
                <p class="mt-2 text-sm font-semibold text-slate-700">{{ __('Expected restoration:') }} {{ $outage->expected_restoration_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Not provided') }} <span class="font-normal text-slate-500">{{ __('(estimate)') }}</span></p>
                <p class="mt-3 text-xs text-slate-500">{{ __('Last updated') }} {{ $outage->updated_at->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') }} · {{ __('View details') }} →</p>
            </a>
        @empty
            <p class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-sm text-slate-600 md:col-span-2">{{ __('No active outages are currently recorded for this area.') }}</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $outages->links() }}</div>
@endsection
