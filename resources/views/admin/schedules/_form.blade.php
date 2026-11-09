<div class="row g-3">
    <div class="col-12">
        <label for="title" class="form-label">{{ __('Schedule title') }} <span class="text-danger">*</span></label>
        <input id="title" name="title" value="{{ old('title', $schedule->title ?? '') }}" required maxlength="180" class="form-control @error('title') is-invalid @enderror">
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="zone_id" class="form-label">{{ __('Service area') }} <span class="text-danger">*</span></label>
        <select id="zone_id" name="zone_id" required class="form-select @error('zone_id') is-invalid @enderror"><option value="">{{ __('Choose an area') }}</option>@foreach ($zones as $zone)<option value="{{ $zone->id }}" @selected((string) old('zone_id', $schedule->zone_id ?? '') === (string) $zone->id)>{{ $zone->name }}</option>@endforeach</select>
        @error('zone_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="scheduled_date" class="form-label">{{ __('Date') }} <span class="text-danger">*</span></label>
        <input id="scheduled_date" name="scheduled_date" type="date" value="{{ old('scheduled_date', isset($schedule) ? $schedule->scheduled_date->format('Y-m-d') : '') }}" required class="form-control @error('scheduled_date') is-invalid @enderror">
        @error('scheduled_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="start_time" class="form-label">{{ __('Start time') }} <span class="text-danger">*</span></label>
        <input id="start_time" name="start_time" type="time" value="{{ old('start_time', isset($schedule) ? substr($schedule->start_time, 0, 5) : '') }}" required class="form-control @error('start_time') is-invalid @enderror">
        @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="end_time" class="form-label">{{ __('End time') }} <span class="text-danger">*</span></label>
        <input id="end_time" name="end_time" type="time" value="{{ old('end_time', isset($schedule) ? substr($schedule->end_time, 0, 5) : '') }}" required class="form-control @error('end_time') is-invalid @enderror">
        @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">{{ __('labels.status') }} <span class="text-danger">*</span></label>
        <select id="status" name="status" required class="form-select @error('status') is-invalid @enderror">@foreach (['draft' => __('status.draft'), 'published' => __('status.published'), 'cancelled' => __('status.cancelled')] as $value => $label)<option value="{{ $value }}" @selected(old('status', $schedule->status ?? 'draft') === $value)>{{ $label }}</option>@endforeach</select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="notes" class="form-label">{{ __('Description') }}</label>
        <textarea id="notes" name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $schedule->notes ?? '') }}</textarea>
        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
