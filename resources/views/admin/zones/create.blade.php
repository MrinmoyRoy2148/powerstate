@extends('layouts.admin')
@section('title', __('Add area'))
@section('content')
    <x-admin-page-header :title="__('Add a service area')" :description="__('Create an area that people can search on the public site.')" />
    <form method="POST" action="{{ route('admin.zones.store') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        @csrf
        @include('admin.zones._form')
        <div class="mt-7 flex gap-3"><button class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700" type="submit">{{ __('Create area') }}</button><a href="{{ route('admin.zones.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-bold">{{ __('Cancel') }}</a></div>
    </form>
@endsection
