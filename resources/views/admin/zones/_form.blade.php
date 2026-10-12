<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="name" class="mb-1 block text-sm font-bold">{{ __('Area name') }} <span class="text-rose-600">*</span></label>
        <input id="name" name="name" value="{{ old('name', $zone->name ?? '') }}" required maxlength="120" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
        @error('name')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="slug" class="mb-1 block text-sm font-bold">{{ __('URL slug') }} <span class="text-rose-600">*</span></label>
        <input id="slug" name="slug" value="{{ old('slug', $zone->slug ?? '') }}" required maxlength="140" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
        @error('slug')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="parent_id" class="mb-1 block text-sm font-bold">{{ __('Parent area') }}</label>
        <select id="parent_id" name="parent_id" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
            <option value="">{{ __('No parent') }}</option>
            @foreach ($zones as $parent)<option value="{{ $parent->id }}" @selected((string) old('parent_id', $zone->parent_id ?? '') === (string) $parent->id)>{{ $parent->name }}</option>@endforeach
        </select>
        @error('parent_id')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="is_active" class="mb-1 block text-sm font-bold">{{ __('Visibility') }}</label>
        <select id="is_active" name="is_active" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
            <option value="1" @selected((string) old('is_active', isset($zone) ? (int) $zone->is_active : 1) === '1')>{{ __('Active and visible publicly') }}</option>
            <option value="0" @selected((string) old('is_active', isset($zone) ? (int) $zone->is_active : 1) === '0')>{{ __('Inactive') }}</option>
        </select>
    </div>
    <div class="sm:col-span-2">
        <label for="description" class="mb-1 block text-sm font-bold">{{ __('Description') }}</label>
        <textarea id="description" name="description" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">{{ old('description', $zone->description ?? '') }}</textarea>
        @error('description')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>
</div>
