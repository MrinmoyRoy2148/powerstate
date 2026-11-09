@props(['title', 'description' => null, 'action' => null, 'actionLabel' => null])

<div class="mb-4 d-flex flex-column flex-sm-row align-items-sm-end justify-content-sm-between gap-3">
    <div>
        <h1 class="h2 fw-bold">{{ $title }}</h1>
        @if ($description)
            <p class="text-secondary mb-0">{{ $description }}</p>
        @endif
    </div>
    @if ($action)
        <a href="{{ $action }}" class="btn btn-success">{{ $actionLabel ?? __('Add new') }}</a>
    @endif
</div>
