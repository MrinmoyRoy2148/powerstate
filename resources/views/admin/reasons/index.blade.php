@extends('layouts.admin')
@section('title', __('Outage reasons'))
@section('content')
    <x-admin-page-header :title="__('Outage reasons')" :description="__('Maintain the selectable reasons used when recording an outage.')" :action="route('admin.reasons.create')" :action-label="__('Add reason')" />
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-500"><tr><th class="px-5 py-3">{{ __('Reason') }}</th><th class="px-5 py-3">{{ __('Outages') }}</th><th class="px-5 py-3">{{ __('Status') }}</th><th class="px-5 py-3">{{ __('Actions') }}</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($reasons as $reason)
                    <tr><td class="px-5 py-4"><p class="font-bold">{{ $reason->name }}</p><p class="mt-1 text-slate-500">{{ $reason->description ?: '—' }}</p></td><td class="px-5 py-4">{{ $reason->outages_count }}</td><td class="px-5 py-4"><x-status-badge :value="$reason->is_active ? 'active' : 'inactive'" /></td><td class="px-5 py-4"><div class="flex gap-3"><a class="font-bold text-emerald-700" href="{{ route('admin.reasons.edit', $reason) }}">{{ __('Edit') }}</a><form method="POST" action="{{ route('admin.reasons.destroy', $reason) }}" onsubmit="return confirm(@js(__('Delete this reason?')))">@csrf @method('DELETE')<button class="font-bold text-rose-700" type="submit">{{ __('Delete') }}</button></form></div></td></tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-10 text-center text-slate-500">{{ __('No outage reasons have been created.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $reasons->links() }}</div>
@endsection
