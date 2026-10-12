@extends('layouts.admin')
@section('title', __('Outages'))
@section('content')
    <x-admin-page-header :title="__('Outage reports')" :description="__('Review current incidents and manage restoration updates.')" :action="route('admin.outages.create')" :action-label="__('Report outage')" />
    <form method="GET" action="{{ route('admin.outages.index') }}" class="mb-5 flex flex-wrap items-end gap-3 rounded-2xl border border-slate-200 bg-white p-4">
        <div><label for="status" class="mb-1 block text-xs font-bold text-slate-600">{{ __('Status') }}</label><select id="status" name="status" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm"><option value="">{{ __('All statuses') }}</option>@foreach (['active', 'restored', 'cancelled'] as $value)<option value="{{ $value }}" @selected(request('status') === $value)>{{ __('status.'.$value) }}</option>@endforeach</select></div>
        <div><label for="zone" class="mb-1 block text-xs font-bold text-slate-600">{{ __('Area') }}</label><select id="zone" name="zone" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm"><option value="">{{ __('All areas') }}</option>@foreach ($zones as $zone)<option value="{{ $zone->id }}" @selected((string) request('zone') === (string) $zone->id)>{{ $zone->name }}</option>@endforeach</select></div>
        <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white" type="submit">{{ __('Filter') }}</button>
        @if (request()->hasAny(['status', 'zone']))<a href="{{ route('admin.outages.index') }}" class="px-2 py-2.5 text-sm font-semibold text-slate-600">{{ __('Clear') }}</a>@endif
    </form>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="bg-slate-50 text-xs text-slate-500"><tr><th class="px-5 py-3">{{ __('Outage') }}</th><th class="px-5 py-3">{{ __('Area') }}</th><th class="px-5 py-3">{{ __('Started') }}</th><th class="px-5 py-3">{{ __('Expected restoration') }}</th><th class="px-5 py-3">{{ __('Status') }}</th><th class="px-5 py-3">{{ __('Actions') }}</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($outages as $outage)
                        <tr><td class="px-5 py-4"><p class="font-bold">{{ $outage->title }}</p><p class="mt-1 text-xs text-slate-500">{{ __('status.'.$outage->outage_type) }}{{ $outage->reason ? ' · '.$outage->reason->name : '' }}</p></td><td class="px-5 py-4">{{ $outage->zone->name }}</td><td class="px-5 py-4">{{ $outage->started_at->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') }}</td><td class="px-5 py-4">{{ $outage->expected_restoration_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Not provided') }}</td><td class="px-5 py-4"><x-status-badge :value="$outage->status" /></td><td class="px-5 py-4"><div class="flex gap-3"><a class="font-bold text-emerald-700" href="{{ route('admin.outages.show', $outage) }}">{{ __('View') }}</a><a class="font-bold text-indigo-700" href="{{ route('admin.outages.edit', $outage) }}">{{ __('Edit') }}</a></div></td></tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-slate-500">{{ __('No outages match this filter.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5">{{ $outages->links() }}</div>
@endsection
