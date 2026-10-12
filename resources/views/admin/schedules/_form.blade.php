<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="title" class="mb-1 block text-sm font-bold">{{ __('Schedule title') }} <span class="text-rose-600">*</span></label>
        <input id="title" name="title" value="{{ old('title', $schedule->title ?? '') }}" required maxlength="180" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
        @error('title')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="zone_id" class="mb-1 block text-sm font-bold">{{ __('Service area') }} <span class="text-rose-600">*</span></label>
        <select id="zone_id" name="zone_id" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"><option value="">{{ __('Choose an area') }}</option>@foreach ($zones as $zone)<option value="{{ $zone->id }}" @selected((string) old('zone_id', $schedule->zone_id ?? '') === (string) $zone->id)>{{ $zone->name }}</option>@endforeach</select>
        @error('zone_id')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="scheduled_date" class="mb-1 block text-sm font-bold">{{ __('Date') }} <span class="text-rose-600">*</span></label>
        <input id="scheduled_date" name="scheduled_date" type="date" value="{{ old('scheduled_date', isset($schedule) ? $schedule->scheduled_date->format('Y-m-d') : '') }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
        @error('scheduled_date')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="start_time" class="mb-1 block text-sm font-bold">{{ __('Start time') }} <span class="text-rose-600">*</span></label>
        <input id="start_time" name="start_time" type="time" value="{{ old('start_time', isset($schedule) ? substr($schedule->start_time, 0, 5) : '') }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
        @error('start_time')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="end_time" class="mb-1 block text-sm font-bold">{{ __('End time') }} <span class="text-rose-600">*</span></label>
        <input id="end_time" name="end_time" type="time" value="{{ old('end_time', isset($schedule) ? substr($schedule->end_time, 0, 5) : '') }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
        @error('end_time')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="status" class="mb-1 block text-sm font-bold">{{ __('Status') }} <span class="text-rose-600">*</span></label>
        <select id="status" name="status" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">@foreach (['draft' => __('status.draft'), 'published' => __('status.published'), 'cancelled' => __('status.cancelled')] as $value => $label)<option value="{{ $value }}" @selected(old('status', $schedule->status ?? 'draft') === $value)>{{ $label }}</option>@endforeach</select>
        @error('status')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div class="sm:col-span-2">
        <label for="notes" class="mb-1 block text-sm font-bold">{{ __('Notes') }}</label>
        <textarea id="notes" name="notes" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">{{ old('notes', $schedule->notes ?? '') }}</textarea>
        @error('notes')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
</div>
