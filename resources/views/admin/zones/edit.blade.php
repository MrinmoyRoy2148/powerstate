@extends('layouts.admin')
@section('title', __('Edit area'))
@section('content')
    <x-admin-page-header :title="__('Edit area').': '.$zone->name" :description="__('Update area details and public visibility.')" />
    <form method="POST" action="{{ route('admin.zones.update', $zone) }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        @csrf @method('PUT')
        @include('admin.zones._form')
        <div class="mt-7 flex gap-3"><button class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700" type="submit">{{ __('Save changes') }}</button><a href="{{ route('admin.zones.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-bold">{{ __('Cancel') }}</a></div>
    </form>
@endsection
