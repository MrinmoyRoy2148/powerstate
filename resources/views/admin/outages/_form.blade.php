<div class="row g-3">
    <div class="col-12">
        <label for="title" class="form-label">{{ __('Outage title') }} <span class="text-danger">*</span></label>
        <input id="title" name="title" value="{{ old('title', $outage->title ?? '') }}" required maxlength="180" class="form-control @error('title') is-invalid @enderror">
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="zone_id" class="form-label">{{ __('Service area') }} <span class="text-danger">*</span></label>
        <select id="zone_id" name="zone_id" required class="form-select @error('zone_id') is-invalid @enderror"><option value="">{{ __('Choose an area') }}</option>@foreach ($zones as $zone)<option value="{{ $zone->id }}" @selected((string) old('zone_id', $outage->zone_id ?? '') === (string) $zone->id)>{{ $zone->name }}</option>@endforeach</select>
        @error('zone_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="schedule_id" class="form-label">{{ __('Related schedule') }}</label>
        <select id="schedule_id" name="schedule_id" class="form-select @error('schedule_id') is-invalid @enderror"><option value="">{{ __('No related schedule') }}</option>@foreach ($schedules as $schedule)<option value="{{ $schedule->id }}" @selected((string) old('schedule_id', $outage->schedule_id ?? '') === (string) $schedule->id)>{{ $schedule->zone->name }} · {{ $schedule->title }} ({{ $schedule->scheduled_date->locale(app()->getLocale())->translatedFormat('d M') }})</option>@endforeach</select>
        @error('schedule_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="reason_id" class="form-label">{{ __('Outage reason') }}</label>
        <select id="reason_id" name="reason_id" class="form-select @error('reason_id') is-invalid @enderror"><option value="">{{ __('Not specified') }}</option>@foreach ($reasons as $reason)<option value="{{ $reason->id }}" @selected((string) old('reason_id', $outage->reason_id ?? '') === (string) $reason->id)>{{ $reason->name }}</option>@endforeach</select>
        @error('reason_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="outage_type" class="form-label">{{ __('Type') }} <span class="text-danger">*</span></label>
        <select id="outage_type" name="outage_type" required class="form-select @error('outage_type') is-invalid @enderror"><option value="planned" @selected(old('outage_type', $outage->outage_type ?? 'unexpected') === 'planned')>{{ __('status.planned') }}</option><option value="unexpected" @selected(old('outage_type', $outage->outage_type ?? 'unexpected') === 'unexpected')>{{ __('status.unexpected') }}</option></select>
        @error('outage_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="started_at" class="form-label">{{ __('Started at') }} <span class="text-danger">*</span></label>
        <input id="started_at" name="started_at" type="datetime-local" value="{{ old('started_at', isset($outage) ? $outage->started_at->format('Y-m-d\TH:i') : '') }}" required class="form-control @error('started_at') is-invalid @enderror">
        @error('started_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="expected_restoration_at" class="form-label">{{ __('Expected restoration') }}</label>
        <input id="expected_restoration_at" name="expected_restoration_at" type="datetime-local" value="{{ old('expected_restoration_at', isset($outage) && $outage->expected_restoration_at ? $outage->expected_restoration_at->format('Y-m-d\TH:i') : '') }}" class="form-control @error('expected_restoration_at') is-invalid @enderror">
        @error('expected_restoration_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">{{ __('labels.status') }} <span class="text-danger">*</span></label>
        <select id="status" name="status" required class="form-select @error('status') is-invalid @enderror">
            @if (isset($outage) && $outage->status === 'restored')
                <option value="restored" selected>{{ __('status.restored') }}</option>
            @else
                <option value="active" @selected(old('status', $outage->status ?? 'active') === 'active')>{{ __('status.active') }}</option>
                <option value="cancelled" @selected(old('status', $outage->status ?? '') === 'cancelled')>{{ __('status.cancelled') }}</option>
            @endif
        </select>
        <div class="form-text">{{ __('Use “Mark restored” on the outage detail page to record its restoration time.') }}</div>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="description" class="form-label">{{ __('Description') }}</label>
        <textarea id="description" name="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $outage->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
