@props(['title', 'description' => null, 'action' => null, 'actionLabel' => null])

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">{{ $title }}</h1>
        @if ($description)
            <p class="mt-2 max-w-2xl text-sm text-slate-600">{{ $description }}</p>
        @endif
    </div>
    @if ($action)
        <a href="{{ $action }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">{{ $actionLabel ?? __('Add new') }}</a>
    @endif
</div>
