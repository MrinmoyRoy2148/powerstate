@extends('layouts.admin')
@section('title', __('Report outage'))
@section('content')
    <x-admin-page-header :title="__('Report an outage')" :description="__('Record an active power interruption for an area.')" />
    <form method="POST" action="{{ route('admin.outages.store') }}" class="card card-body shadow-sm">@csrf @include('admin.outages._form')<div class="mt-4 d-flex gap-2"><button class="btn btn-success" type="submit">{{ __('Report outage') }}</button><a href="{{ route('admin.outages.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a></div></form>
@endsection
