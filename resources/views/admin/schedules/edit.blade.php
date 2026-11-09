@extends('layouts.admin')
@section('title', __('Edit schedule'))
@section('content')
    <x-admin-page-header :title="__('Edit schedule')" :description="__('Update the schedule details or publication status.')" />
    <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" class="card card-body shadow-sm">@csrf @method('PUT') @include('admin.schedules._form')<div class="mt-4 d-flex gap-2"><button class="btn btn-success" type="submit">{{ __('Save changes') }}</button><a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a></div></form>
@endsection
