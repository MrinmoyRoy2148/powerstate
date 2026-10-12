@extends('layouts.admin')
@section('title', __('Schedules'))
@section('content')
    <x-admin-page-header :title="__('Load-shedding schedules')" :description="__('Create and publish planned service interruptions by area.')" :action="route('admin.schedules.create')" :action-label="__('Add schedule')" />
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="bg-slate-50 text-xs text-slate-500"><tr><th class="px-5 py-3">{{ __('Schedule') }}</th><th class="px-5 py-3">{{ __('Area') }}</th><th class="px-5 py-3">{{ __('Date and time') }}</th><th class="px-5 py-3">{{ __('Status') }}</th><th class="px-5 py-3">{{ __('Actions') }}</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($schedules as $schedule)
                        <tr><td class="px-5 py-4"><p class="font-bold">{{ $schedule->title }}</p><p class="mt-1 text-xs text-slate-500">{{ __('By') }} {{ $schedule->creator?->name ?? __('Administrator') }}</p></td><td class="px-5 py-4">{{ $schedule->zone->name }}</td><td class="px-5 py-4">{{ $schedule->scheduled_date->locale(app()->getLocale())->translatedFormat('d M Y') }}<p class="text-xs text-slate-500">{{ $schedule->start_time }}–{{ $schedule->end_time }}</p></td><td class="px-5 py-4"><x-status-badge :value="$schedule->status" /></td><td class="px-5 py-4"><div class="flex gap-3"><a class="font-bold text-emerald-700" href="{{ route('admin.schedules.edit', $schedule) }}">{{ __('Edit') }}</a><form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" onsubmit="return confirm(@js(__('Delete this schedule?')))">@csrf @method('DELETE')<button class="font-bold text-rose-700" type="submit">{{ __('Delete') }}</button></form></div></td></tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">{{ __('No schedules have been created.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5">{{ $schedules->links() }}</div>
@endsection
