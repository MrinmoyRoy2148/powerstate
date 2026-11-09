@extends('layouts.admin')
@section('title', __('Add outage reason'))
@section('content')
    <x-admin-page-header :title="__('Add an outage reason')" :description="__('Reasons help make outage reports more informative.')" />
    <form method="POST" action="{{ route('admin.reasons.store') }}" class="card card-body shadow-sm">@csrf @include('admin.reasons._form')<div class="mt-4 d-flex gap-2"><button class="btn btn-success" type="submit">{{ __('Create reason') }}</button><a href="{{ route('admin.reasons.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a></div></form>
@endsection
