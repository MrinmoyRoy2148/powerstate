@extends('layouts.admin')
@section('title', __('Add schedule'))
@section('content')
    <x-admin-page-header :title="__('Add a schedule')" :description="__('Record a planned load-shedding period for a service area.')" />
    <form method="POST" action="{{ route('admin.schedules.store') }}" class="card card-body shadow-sm">@csrf @include('admin.schedules._form')<div class="mt-4 d-flex gap-2"><button class="btn btn-success" type="submit">{{ __('Create schedule') }}</button><a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a></div></form>
@endsection
