<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="name" class="mb-1 block text-sm font-bold">{{ __('Reason name') }} <span class="text-rose-600">*</span></label>
        <input id="name" name="name" value="{{ old('name', $reason->name ?? '') }}" required maxlength="120" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
        @error('name')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="is_active" class="mb-1 block text-sm font-bold">{{ __('Availability') }}</label>
        <select id="is_active" name="is_active" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
            <option value="1" @selected((string) old('is_active', isset($reason) ? (int) $reason->is_active : 1) === '1')>{{ __('Active') }}</option>
            <option value="0" @selected((string) old('is_active', isset($reason) ? (int) $reason->is_active : 1) === '0')>{{ __('Inactive') }}</option>
        </select>
    </div>
    <div class="sm:col-span-2">
        <label for="description" class="mb-1 block text-sm font-bold">{{ __('Description') }}</label>
        <textarea id="description" name="description" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">{{ old('description', $reason->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
</div>
