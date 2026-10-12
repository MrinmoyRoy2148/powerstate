@extends('layouts.admin')
@section('title', __('Announcements'))
@section('content')
    <x-admin-page-header :title="__('Announcements')" :description="__('Draft and publish service notices for a specific area or everyone.')" :action="route('admin.announcements.create')" :action-label="__('Add announcement')" />
    <form method="GET" action="{{ route('admin.announcements.index') }}" class="mb-5 flex flex-wrap items-end gap-3 rounded-2xl border border-slate-200 bg-white p-4">
        <div><label for="status" class="mb-1 block text-xs font-bold text-slate-600">{{ __('Status') }}</label><select id="status" name="status" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm"><option value="">{{ __('All statuses') }}</option><option value="draft" @selected(request('status') === 'draft')>{{ __('status.draft') }}</option><option value="published" @selected(request('status') === 'published')>{{ __('status.published') }}</option></select></div>
        <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white" type="submit">{{ __('Filter') }}</button>
        @if (request()->has('status'))<a href="{{ route('admin.announcements.index') }}" class="px-2 py-2.5 text-sm font-semibold text-slate-600">{{ __('Clear') }}</a>@endif
    </form>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] text-left text-sm">
                <thead class="bg-slate-50 text-xs text-slate-500"><tr><th class="px-5 py-3">{{ __('Announcement') }}</th><th class="px-5 py-3">{{ __('Audience') }}</th><th class="px-5 py-3">{{ __('Publication') }}</th><th class="px-5 py-3">{{ __('Author') }}</th><th class="px-5 py-3">{{ __('Actions') }}</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($announcements as $announcement)
                        <tr><td class="px-5 py-4"><p class="font-bold">{{ $announcement->title }}</p><p class="mt-1 max-w-md truncate text-slate-500">{{ $announcement->body }}</p></td><td class="px-5 py-4">{{ $announcement->zone?->name ?? __('All areas') }}</td><td class="px-5 py-4"><x-status-badge :value="$announcement->status" /><p class="mt-1 text-xs text-slate-500">{{ $announcement->published_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Not scheduled') }}</p></td><td class="px-5 py-4">{{ $announcement->creator?->name ?? '—' }}</td><td class="px-5 py-4"><div class="flex gap-3"><a class="font-bold text-emerald-700" href="{{ route('admin.announcements.edit', $announcement) }}">{{ __('Edit') }}</a><form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" onsubmit="return confirm(@js(__('Delete this announcement?')))">@csrf @method('DELETE')<button class="font-bold text-rose-700" type="submit">{{ __('Delete') }}</button></form></div></td></tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">{{ __('No announcements match this filter.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5">{{ $announcements->links() }}</div>
@endsection
