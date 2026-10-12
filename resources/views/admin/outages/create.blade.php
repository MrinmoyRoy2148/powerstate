@extends('layouts.admin')
@section('title', __('Report outage'))
@section('content')
    <x-admin-page-header :title="__('Report an outage')" :description="__('Record an active power interruption for an area.')" />
    <form method="POST" action="{{ route('admin.outages.store') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">@csrf @include('admin.outages._form')<div class="mt-7 flex gap-3"><button class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white" type="submit">{{ __('Report outage') }}</button><a href="{{ route('admin.outages.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-bold">{{ __('Cancel') }}</a></div></form>
@endsection
