@extends('layouts.app')

@section('title', $announcement->title)

@section('content')
    <a href="{{ route('announcements.index') }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-900">{{ __('← All announcements') }}</a>
    <article class="mx-auto mt-4 max-w-3xl rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-10">
        <p class="text-sm font-semibold text-emerald-700">{{ $announcement->zone?->name ?? __('All areas') }} · {{ $announcement->published_at?->locale(app()->getLocale())->translatedFormat('d M Y, h:i A') ?? __('Published') }}</p>
        <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">{{ $announcement->title }}</h1>
        <div class="mt-7 whitespace-pre-line border-t border-slate-100 pt-6 text-base leading-8 text-slate-700">{{ $announcement->body }}</div>
    </article>
@endsection
