@extends('layouts.app')

@section('title', __('Upcoming schedules'))

@section('content')
    <x-admin-page-header :title="__('Load-shedding schedules')" :description="__('Published upcoming schedules by area. Times are local and planned periods may change.')" />
    <form method="GET" action="{{ route('schedules.index') }}" class="mb-6 grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-[1fr_1fr_auto]">
        <div>
            <label for="zone" class="mb-1 block text-sm font-semibold text-slate-700">{{ __('Area') }}</label>
            <select id="zone" name="zone" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <option value="">{{ __('All areas') }}</option>
                @foreach ($zones as $zone)
                    <option value="{{ $zone->id }}" @selected((string) request('zone') === (string) $zone->id)>{{ $zone->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="date" class="mb-1 block text-sm font-semibold text-slate-700">{{ __('Date') }}</label>
            <input id="date" name="date" type="date" value="{{ request('date') }}" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
        </div>
        <button class="self-end rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-700" type="submit">{{ __('Apply filters') }}</button>
    </form>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($schedules as $schedule)
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-3"><p class="text-xs font-bold uppercase tracking-widest text-emerald-700">{{ $schedule->zone->name }}</p><x-status-badge :value="$schedule->status" /></div>
                <h2 class="mt-2 text-lg font-extrabold text-slate-950">{{ $schedule->title }}</h2>
                <p class="mt-4 text-sm font-semibold text-slate-700">{{ $schedule->scheduled_date->locale(app()->getLocale())->translatedFormat('l, d M Y') }}</p>
                <p class="mt-1 text-sm text-slate-600">{{ $schedule->start_time }}–{{ $schedule->end_time }}</p>
                @if ($schedule->notes)<p class="mt-3 border-t border-slate-100 pt-3 text-sm leading-6 text-slate-600">{{ $schedule->notes }}</p>@endif
            </article>
        @empty
            <p class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-sm text-slate-600 sm:col-span-2 lg:col-span-3">{{ __('No published upcoming schedules match those filters.') }}</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $schedules->links() }}</div>
@endsection
