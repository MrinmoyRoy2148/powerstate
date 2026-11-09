<div class="row g-3">
    <div class="col-12">
        <label for="title" class="form-label">{{ __('Title') }} <span class="text-danger">*</span></label>
        <input id="title" name="title" value="{{ old('title', $announcement->title ?? '') }}" required maxlength="180" class="form-control @error('title') is-invalid @enderror">
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="zone_id" class="form-label">{{ __('Audience') }}</label>
        <select id="zone_id" name="zone_id" class="form-select @error('zone_id') is-invalid @enderror"><option value="">{{ __('All areas') }}</option>@foreach ($zones as $zone)<option value="{{ $zone->id }}" @selected((string) old('zone_id', $announcement->zone_id ?? '') === (string) $zone->id)>{{ $zone->name }}</option>@endforeach</select>
        @error('zone_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">{{ __('labels.status') }} <span class="text-danger">*</span></label>
        <select id="status" name="status" required class="form-select @error('status') is-invalid @enderror"><option value="draft" @selected(old('status', $announcement->status ?? 'draft') === 'draft')>{{ __('Draft (not public)') }}</option><option value="published" @selected(old('status', $announcement->status ?? '') === 'published')>{{ __('status.published') }}</option></select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="published_at" class="form-label">{{ __('Publish at') }}</label>
        <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', isset($announcement) && $announcement->published_at ? $announcement->published_at->format('Y-m-d\TH:i') : '') }}" class="form-control @error('published_at') is-invalid @enderror">
        <div class="form-text">{{ __('Leave blank to publish immediately when status is Published.') }}</div>
        @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="expires_at" class="form-label">{{ __('Expire at') }}</label>
        <input id="expires_at" name="expires_at" type="datetime-local" value="{{ old('expires_at', isset($announcement) && $announcement->expires_at ? $announcement->expires_at->format('Y-m-d\TH:i') : '') }}" class="form-control @error('expires_at') is-invalid @enderror">
        <div class="form-text">{{ __('Leave blank for no expiry.') }}</div>
        @error('expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="body" class="form-label">{{ __('Message') }} <span class="text-danger">*</span></label>
        <textarea id="body" name="body" rows="8" required class="form-control @error('body') is-invalid @enderror">{{ old('body', $announcement->body ?? '') }}</textarea>
        @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
