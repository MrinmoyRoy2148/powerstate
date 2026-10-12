@extends('layouts.admin')
@section('title', $outage->title)
@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('admin.outages.index') }}" class="text-sm font-bold text-emerald-700">{{ __('← All outages') }}</a>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.outages.edit', $outage) }}" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold">{{ __('Edit outage') }}</a>
            @if ($outage->status === 'active')
                <form method="POST" action="{{ route('admin.outages.restore', $outage) }}" onsubmit="return confirm(@js(__('Mark this outage as restored now?')))">@csrf @method('PATCH')<button class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white" type="submit">{{ __('Mark restored') }}</button></form>
            @endif
            <form method="POST" action="{{ route('admin.outages.destroy', $outage) }}" onsubmit="return confirm(@js(__('Permanently delete this outage report?')))">@csrf @method('DELETE')<button class="rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-sm font-bold text-rose-700" type="submit">{{ __('Delete') }}</button></form>
        </div>
    </div>
    <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-6 sm:p-8"><div class="flex flex-wrap items-center gap-3"><x-status-badge :value="$outage->status" /><x-status-badge :value="$outage->outage_type" /></div><h1 class="mt-4 text-3xl font-black tracking-tight text-slate-950">{{ $outage->title }}</h1><p class="mt-2 text-slate-600">{{ $outage->zone->name }} · {{ __('Reported by') }} {{ $outage->reporter?->name ?? __('Administrator') }}</p></div>
        <dl class="grid gap-6 p-6 sm:grid-cols-2 sm:p-8">
            <div><dt class="text-xs font-bold text-slate-500">{{ __('Started') }}</dt><dd class="mt-1 font-semibold">{{ $outage->started_at->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') }}</dd></div>
            <div><dt class="text-xs font-bold text-slate-500">{{ __('Expected restoration') }}</dt><dd class="mt-1 font-semibold">{{ $outage->expected_restoration_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Not provided') }}</dd></div>
            <div><dt class="text-xs font-bold text-slate-500">{{ __('Restored') }}</dt><dd class="mt-1 font-semibold">{{ $outage->restored_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Not restored') }}</dd></div>
            <div><dt class="text-xs font-bold text-slate-500">{{ __('Reason') }}</dt><dd class="mt-1 font-semibold">{{ $outage->reason?->name ?? __('Not specified') }}</dd></div>
            <div><dt class="text-xs font-bold text-slate-500">{{ __('Related schedule') }}</dt><dd class="mt-1 font-semibold">{{ $outage->schedule?->title ?? __('None') }}</dd></div>
            <div><dt class="text-xs font-bold text-slate-500">{{ __('Last updated') }}</dt><dd class="mt-1 font-semibold">{{ $outage->updated_at->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-xs font-bold text-slate-500">{{ __('Description') }}</dt><dd class="mt-2 whitespace-pre-line leading-7 text-slate-700">{{ $outage->description ?: __('No additional details were provided.') }}</dd></div>
        </dl>
    </article>
@endsection
