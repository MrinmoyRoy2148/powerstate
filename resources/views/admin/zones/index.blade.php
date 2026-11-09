@extends('layouts.admin')

@section('title', __('Manage areas'))

@section('content')
    <x-admin-page-header :title="__('Service areas')" :description="__('Manage the public areas people can search.')" :action="route('admin.zones.create')" :action-label="__('Add area')" />
    <form method="GET" action="{{ route('admin.zones.index') }}" class="mb-5 flex flex-wrap items-end gap-3 rounded-2xl border border-slate-200 bg-white p-4">
        <div class="min-w-[220px] flex-1">
            <label for="search" class="mb-1 block text-xs font-bold text-slate-600">{{ __('Search areas') }}</label>
            <input id="search" name="search" value="{{ request('search') }}" type="search" placeholder="{{ __('Search by name or slug') }}" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label for="status" class="mb-1 block text-xs font-bold text-slate-600">{{ __('labels.status') }}</label>
            <select id="status" name="status" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm">
                <option value="">{{ __('All statuses') }}</option>
                <option value="active" @selected(request('status') === 'active')>{{ __('status.active') }}</option>
                <option value="inactive" @selected(request('status') === 'inactive')>{{ __('status.inactive') }}</option>
            </select>
        </div>
        <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white hover:bg-slate-700" type="submit">{{ __('Filter') }}</button>
        @if (request()->hasAny(['search', 'status']))
            <a href="{{ route('admin.zones.index') }}" class="px-2 py-2.5 text-sm font-semibold text-slate-600">{{ __('Clear') }}</a>
        @endif
    </form>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="bg-slate-50 text-xs text-slate-500"><tr><th class="px-5 py-3">{{ __('Area') }}</th><th class="px-5 py-3">{{ __('Parent') }}</th><th class="px-5 py-3">{{ __('Schedules') }}</th><th class="px-5 py-3">{{ __('Outages') }}</th><th class="px-5 py-3">{{ __('labels.status') }}</th><th class="px-5 py-3">{{ __('Actions') }}</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($zones as $zone)
                        <tr>
                            <td class="px-5 py-4"><p class="font-bold text-slate-900">{{ $zone->name }}</p><p class="text-xs text-slate-500">{{ $zone->slug }}</p></td>
                            <td class="px-5 py-4 text-slate-600">{{ $zone->parent?->name ?? '—' }}</td>
                            <td class="px-5 py-4">{{ $zone->schedules_count }}</td><td class="px-5 py-4">{{ $zone->outages_count }}</td>
                            <td class="px-5 py-4"><x-status-badge :value="$zone->is_active ? 'active' : 'inactive'" /></td>
                            <td class="px-5 py-4"><div class="flex items-center gap-3"><a class="font-bold text-emerald-700 hover:underline" href="{{ route('admin.zones.edit', $zone) }}">{{ __('Edit') }}</a>
                                <form method="POST" action="{{ route('admin.zones.destroy', $zone) }}" onsubmit="return confirm(@js(__('Delete this area? Areas in use cannot be deleted.')))">@csrf @method('DELETE')<button class="font-bold text-rose-700 hover:underline" type="submit">{{ __('Delete') }}</button></form></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-slate-500">{{ __('No service areas match this filter.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5">{{ $zones->links() }}</div>
@endsection
