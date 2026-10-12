<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="title" class="mb-1 block text-sm font-bold">{{ __('Outage title') }} <span class="text-rose-600">*</span></label>
        <input id="title" name="title" value="{{ old('title', $outage->title ?? '') }}" required maxlength="180" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
        @error('title')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="zone_id" class="mb-1 block text-sm font-bold">{{ __('Service area') }} <span class="text-rose-600">*</span></label>
        <select id="zone_id" name="zone_id" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"><option value="">{{ __('Choose an area') }}</option>@foreach ($zones as $zone)<option value="{{ $zone->id }}" @selected((string) old('zone_id', $outage->zone_id ?? '') === (string) $zone->id)>{{ $zone->name }}</option>@endforeach</select>
        @error('zone_id')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="schedule_id" class="mb-1 block text-sm font-bold">{{ __('Related schedule') }}</label>
        <select id="schedule_id" name="schedule_id" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"><option value="">{{ __('No related schedule') }}</option>@foreach ($schedules as $schedule)<option value="{{ $schedule->id }}" @selected((string) old('schedule_id', $outage->schedule_id ?? '') === (string) $schedule->id)>{{ $schedule->zone->name }} · {{ $schedule->title }} ({{ $schedule->scheduled_date->locale(app()->getLocale())->translatedFormat('d M') }})</option>@endforeach</select>
        @error('schedule_id')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="reason_id" class="mb-1 block text-sm font-bold">{{ __('Outage reason') }}</label>
        <select id="reason_id" name="reason_id" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"><option value="">{{ __('Not specified') }}</option>@foreach ($reasons as $reason)<option value="{{ $reason->id }}" @selected((string) old('reason_id', $outage->reason_id ?? '') === (string) $reason->id)>{{ $reason->name }}</option>@endforeach</select>
        @error('reason_id')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="outage_type" class="mb-1 block text-sm font-bold">{{ __('Type') }} <span class="text-rose-600">*</span></label>
        <select id="outage_type" name="outage_type" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm"><option value="planned" @selected(old('outage_type', $outage->outage_type ?? 'unexpected') === 'planned')>{{ __('status.planned') }}</option><option value="unexpected" @selected(old('outage_type', $outage->outage_type ?? 'unexpected') === 'unexpected')>{{ __('status.unexpected') }}</option></select>
        @error('outage_type')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="started_at" class="mb-1 block text-sm font-bold">{{ __('Started at') }} <span class="text-rose-600">*</span></label>
        <input id="started_at" name="started_at" type="datetime-local" value="{{ old('started_at', isset($outage) ? $outage->started_at->format('Y-m-d\TH:i') : '') }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
        @error('started_at')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="expected_restoration_at" class="mb-1 block text-sm font-bold">{{ __('Expected restoration') }}</label>
        <input id="expected_restoration_at" name="expected_restoration_at" type="datetime-local" value="{{ old('expected_restoration_at', isset($outage) && $outage->expected_restoration_at ? $outage->expected_restoration_at->format('Y-m-d\TH:i') : '') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
        @error('expected_restoration_at')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="status" class="mb-1 block text-sm font-bold">{{ __('Status') }} <span class="text-rose-600">*</span></label>
        <select id="status" name="status" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
            @if (isset($outage) && $outage->status === 'restored')
                <option value="restored" selected>{{ __('status.restored') }}</option>
            @else
                <option value="active" @selected(old('status', $outage->status ?? 'active') === 'active')>{{ __('status.active') }}</option>
                <option value="cancelled" @selected(old('status', $outage->status ?? '') === 'cancelled')>{{ __('status.cancelled') }}</option>
            @endif
        </select>
        <p class="mt-1 text-xs text-slate-500">{{ __('Use “Mark restored” on the outage detail page to record its restoration time.') }}</p>
        @error('status')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div class="sm:col-span-2">
        <label for="description" class="mb-1 block text-sm font-bold">{{ __('Description') }}</label>
        <textarea id="description" name="description" rows="5" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">{{ old('description', $outage->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
</div>
