@extends('layouts.admin')
@section('title', __('Edit outage reason'))
@section('content')
    <x-admin-page-header :title="__('Edit outage reason').': '.$reason->name" :description="__('Update the reason label, description, or availability.')" />
    <form method="POST" action="{{ route('admin.reasons.update', $reason) }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">@csrf @method('PUT') @include('admin.reasons._form')<div class="mt-7 flex gap-3"><button class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white" type="submit">{{ __('Save changes') }}</button><a href="{{ route('admin.reasons.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-bold">{{ __('Cancel') }}</a></div></form>
@endsection
