<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="title" class="mb-1 block text-sm font-bold">{{ __('Title') }} <span class="text-rose-600">*</span></label>
        <input id="title" name="title" value="{{ old('title', $announcement->title ?? '') }}" required maxlength="180" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
        @error('title')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="zone_id" class="mb-1 block text-sm font-bold">{{ __('Audience') }}</label>
        <select id="zone_id" name="zone_id" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"><option value="">{{ __('All areas') }}</option>@foreach ($zones as $zone)<option value="{{ $zone->id }}" @selected((string) old('zone_id', $announcement->zone_id ?? '') === (string) $zone->id)>{{ $zone->name }}</option>@endforeach</select>
        @error('zone_id')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="status" class="mb-1 block text-sm font-bold">{{ __('Status') }} <span class="text-rose-600">*</span></label>
        <select id="status" name="status" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"><option value="draft" @selected(old('status', $announcement->status ?? 'draft') === 'draft')>{{ __('Draft (not public)') }}</option><option value="published" @selected(old('status', $announcement->status ?? '') === 'published')>{{ __('status.published') }}</option></select>
        @error('status')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="published_at" class="mb-1 block text-sm font-bold">{{ __('Publish at') }}</label>
        <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', isset($announcement) && $announcement->published_at ? $announcement->published_at->format('Y-m-d\TH:i') : '') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
        <p class="mt-1 text-xs text-slate-500">{{ __('Leave blank to publish immediately when status is Published.') }}</p>
        @error('published_at')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div class="sm:col-span-2">
        <label for="body" class="mb-1 block text-sm font-bold">{{ __('Message') }} <span class="text-rose-600">*</span></label>
        <textarea id="body" name="body" rows="8" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">{{ old('body', $announcement->body ?? '') }}</textarea>
        @error('body')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
</div>
