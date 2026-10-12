@extends('layouts.app')

@section('title', __('Announcements'))

@section('content')
    <div class="mb-6"><h1 class="text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">{{ __('Announcements') }}</h1><p class="mt-2 max-w-2xl text-sm text-slate-600">{{ __('Published service notices and maintenance updates from Power Authority staff.') }}</p></div>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($announcements as $announcement)
            <a href="{{ route('announcements.show', $announcement) }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md">
                <div class="flex items-center justify-between gap-2 text-xs font-semibold text-slate-500">
                    <span>{{ $announcement->zone?->name ?? __('All areas') }}</span>
                    <span>{{ $announcement->published_at?->locale(app()->getLocale())->translatedFormat('d M Y') ?? __('Published') }}</span>
                </div>
                <h2 class="mt-3 text-lg font-extrabold text-slate-950 group-hover:text-emerald-700">{{ $announcement->title }}</h2>
                <p class="mt-2 line-clamp-4 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $announcement->body }}</p>
                <span class="mt-5 inline-flex text-sm font-bold text-emerald-700">{{ __('Read notice') }} →</span>
            </a>
        @empty
            <p class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-sm text-slate-600 md:col-span-2 lg:col-span-3">{{ __('No announcements are currently published.') }}</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $announcements->links() }}</div>
@endsection
