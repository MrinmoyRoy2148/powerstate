@props(['value'])

@php
    $tone = match ($value) {
        'active', 'published' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        'restored' => 'bg-sky-100 text-sky-800 ring-sky-200',
        'draft' => 'bg-amber-100 text-amber-800 ring-amber-200',
        'cancelled' => 'bg-slate-200 text-slate-700 ring-slate-300',
        'planned' => 'bg-indigo-100 text-indigo-800 ring-indigo-200',
        'unexpected' => 'bg-rose-100 text-rose-800 ring-rose-200',
        default => 'bg-slate-100 text-slate-700 ring-slate-200',
    };
@endphp

<span {{ $attributes->class(['inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold capitalize ring-1 ring-inset', $tone]) }}>
    {{ __('status.'.$value) }}
</span>
