@extends('layouts.app')

@section('title', __('Outage history'))

@section('content')
    <x-admin-page-header :title="__('Outage history')" :description="__('Previous outages remain available for reference after service is restored.')" />
    <form method="GET" action="{{ route('outages.history') }}" class="mb-6 grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <label for="zone" class="mb-1 block text-sm font-semibold text-slate-700">{{ __('Area') }}</label>
            <select id="zone" name="zone" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
                <option value="">{{ __('All areas') }}</option>
                @foreach ($zones as $zone)<option value="{{ $zone->id }}" @selected((string) request('zone') === (string) $zone->id)>{{ $zone->name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label for="type" class="mb-1 block text-sm font-semibold text-slate-700">{{ __('Type') }}</label>
            <select id="type" name="type" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
                <option value="">{{ __('All types') }}</option>
                <option value="planned" @selected(request('type') === 'planned')>{{ __('Planned') }}</option>
                <option value="unexpected" @selected(request('type') === 'unexpected')>{{ __('Unexpected') }}</option>
            </select>
        </div>
        <div>
            <label for="date" class="mb-1 block text-sm font-semibold text-slate-700">{{ __('Started on') }}</label>
            <input id="date" name="date" type="date" value="{{ request('date') }}" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
        </div>
        <button class="self-end rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-700" type="submit">{{ __('Apply filters') }}</button>
    </form>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="divide-y divide-slate-100">
            @forelse ($outages as $outage)
                <a href="{{ route('outages.show', $outage) }}" class="flex flex-col gap-3 p-5 transition hover:bg-slate-50 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="flex flex-wrap items-center gap-2"><h2 class="font-bold text-slate-950">{{ $outage->title }}</h2><x-status-badge :value="$outage->status" /><x-status-badge :value="$outage->outage_type" /></div>
                        <p class="mt-1 text-sm text-slate-600">{{ $outage->zone->name }} · {{ $outage->reason?->name ?? __('Reason not provided') }}</p>
                    </div>
                    <div class="text-sm text-slate-500"><p>{{ __('Started') }} {{ $outage->started_at->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') }}</p><p>{{ __('Restored') }} {{ $outage->restored_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Not recorded') }}</p></div>
                </a>
            @empty
                <p class="p-8 text-sm text-slate-600">{{ __('No historical outages match those filters.') }}</p>
            @endforelse
        </div>
    </div>
    <div class="mt-6">{{ $outages->links() }}</div>
@endsection
