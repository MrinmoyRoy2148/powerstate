@extends('layouts.admin')
@section('title', __('Edit outage'))
@section('content')
    <x-admin-page-header :title="__('Edit outage')" :description="__('Correct the outage details or update its current status.')" />
    <form method="POST" action="{{ route('admin.outages.update', $outage) }}" class="card card-body shadow-sm">@csrf @method('PUT') @include('admin.outages._form')<div class="mt-4 d-flex gap-2"><button class="btn btn-success" type="submit">{{ __('Save changes') }}</button><a href="{{ route('admin.outages.show', $outage) }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a></div></form>
@endsection
