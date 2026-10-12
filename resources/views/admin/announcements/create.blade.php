@extends('layouts.admin')
@section('title', __('Add announcement'))
@section('content')
    <x-admin-page-header :title="__('Add an announcement')" :description="__('Write a notice for one area or publish it across the portal.')" />
    <form method="POST" action="{{ route('admin.announcements.store') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">@csrf @include('admin.announcements._form')<div class="mt-7 flex gap-3"><button class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white" type="submit">{{ __('Save announcement') }}</button><a href="{{ route('admin.announcements.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-bold">{{ __('Cancel') }}</a></div></form>
@endsection
