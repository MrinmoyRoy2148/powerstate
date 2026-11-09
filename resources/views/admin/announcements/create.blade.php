@extends('layouts.admin')
@section('title', __('Add announcement'))
@section('content')
    <x-admin-page-header :title="__('Add an announcement')" :description="__('Write a notice for one area or publish it across the portal.')" />
    <form method="POST" action="{{ route('admin.announcements.store') }}" class="card card-body shadow-sm">@csrf @include('admin.announcements._form')<div class="mt-4 d-flex gap-2"><button class="btn btn-success" type="submit">{{ __('Save announcement') }}</button><a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a></div></form>
@endsection
