@extends('layouts.admin')
@section('title', __('Add schedule'))
@section('content')
    <x-admin-page-header :title="__('Add a schedule')" :description="__('Record a planned load-shedding period for a service area.')" />
    <form method="POST" action="{{ route('admin.schedules.store') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">@csrf @include('admin.schedules._form')<div class="mt-7 flex gap-3"><button class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white" type="submit">{{ __('Create schedule') }}</button><a href="{{ route('admin.schedules.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-bold">{{ __('Cancel') }}</a></div></form>
@endsection
