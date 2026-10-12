@extends('layouts.app')

@section('title', $outage->title)

@section('content')
    <a href="{{ $outage->status === 'active' ? route('outages.index') : route('outages.history') }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-900">{{ __('← Back to outages') }}</a>
    <article class="mt-4 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="bg-slate-950 px-6 py-8 text-white sm:px-9">
            <div class="flex flex-wrap gap-2"><x-status-badge :value="$outage->status" /><x-status-badge :value="$outage->outage_type" /></div>
            <h1 class="mt-4 text-3xl font-black sm:text-4xl">{{ $outage->title }}</h1>
            <p class="mt-2 text-slate-300">{{ $outage->zone->name }}</p>
        </div>
        <div class="grid gap-6 p-6 sm:grid-cols-2 sm:p-9">
            <div><p class="text-xs font-bold text-slate-500">{{ __('Reason') }}</p><p class="mt-1 font-semibold text-slate-950">{{ $outage->reason?->name ?? __('Not provided') }}</p></div>
            <div><p class="text-xs font-bold text-slate-500">{{ __('Started') }}</p><p class="mt-1 font-semibold text-slate-950">{{ $outage->started_at->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') }}</p></div>
            <div><p class="text-xs font-bold text-slate-500">{{ __('Expected restoration') }}</p><p class="mt-1 font-semibold text-slate-950">{{ $outage->expected_restoration_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Not provided') }} <span class="font-normal text-slate-500">{{ __('(estimate)') }}</span></p></div>
            <div><p class="text-xs font-bold text-slate-500">{{ __('Restored') }}</p><p class="mt-1 font-semibold text-slate-950">{{ $outage->restored_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Not yet restored') }}</p></div>
            @if ($outage->schedule)
                <div class="sm:col-span-2"><p class="text-xs font-bold text-slate-500">{{ __('Related schedule') }}</p><p class="mt-1 font-semibold text-slate-950">{{ $outage->schedule->title }} · {{ $outage->schedule->scheduled_date->locale(app()->getLocale())->translatedFormat('d M Y') }}</p></div>
            @endif
            <div class="sm:col-span-2"><p class="text-xs font-bold text-slate-500">{{ __('Details') }}</p><p class="mt-2 whitespace-pre-line leading-7 text-slate-700">{{ $outage->description ?: __('No additional details provided.') }}</p></div>
            <p class="sm:col-span-2 text-xs text-slate-500">{{ __('Last updated') }} {{ $outage->updated_at->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') }}. {{ __('Restoration estimates may change.') }}</p>
        </div>
    </article>
@endsection
