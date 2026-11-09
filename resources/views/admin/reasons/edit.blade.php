@extends('layouts.admin')
@section('title', __('Edit outage reason'))
@section('content')
    <x-admin-page-header :title="__('Edit outage reason').': '.$reason->name" :description="__('Update the reason label, description, or availability.')" />
    <form method="POST" action="{{ route('admin.reasons.update', $reason) }}" class="card card-body shadow-sm">@csrf @method('PUT') @include('admin.reasons._form')<div class="mt-4 d-flex gap-2"><button class="btn btn-success" type="submit">{{ __('Save changes') }}</button><a href="{{ route('admin.reasons.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a></div></form>
@endsection
