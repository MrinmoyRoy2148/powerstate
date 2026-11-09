<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">{{ __('Reason name') }} <span class="text-danger">*</span></label>
        <input id="name" name="name" value="{{ old('name', $reason->name ?? '') }}" required maxlength="120" class="form-control @error('name') is-invalid @enderror">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="is_active" class="form-label">{{ __('Availability') }}</label>
        <select id="is_active" name="is_active" class="form-select">
            <option value="1" @selected((string) old('is_active', isset($reason) ? (int) $reason->is_active : 1) === '1')>{{ __('Active') }}</option>
            <option value="0" @selected((string) old('is_active', isset($reason) ? (int) $reason->is_active : 1) === '0')>{{ __('Inactive') }}</option>
        </select>
    </div>
    <div class="col-12">
        <label for="description" class="form-label">{{ __('Description') }}</label>
        <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $reason->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
