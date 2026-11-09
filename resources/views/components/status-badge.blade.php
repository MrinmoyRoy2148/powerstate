@props(['value'])

@php
    $tone = match ($value) {
        'active', 'published' => 'text-bg-success',
        'restored' => 'text-bg-info',
        'draft' => 'text-bg-warning',
        'cancelled', 'inactive' => 'text-bg-secondary',
        'planned' => 'text-bg-primary',
        'unexpected' => 'text-bg-danger',
        default => 'text-bg-secondary',
    };
@endphp

<span {{ $attributes->class(['badge rounded-pill', $tone]) }}>
    {{ __('status.'.$value) }}
</span>
