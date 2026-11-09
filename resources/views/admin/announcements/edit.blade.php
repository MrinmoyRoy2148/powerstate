@extends('layouts.admin')
@section('title', __('Edit announcement'))
@section('content')
    <x-admin-page-header :title="__('Edit announcement')" :description="__('Update the notice, its audience, or publication timing.')" />
    <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" class="card card-body shadow-sm">@csrf @method('PUT') @include('admin.announcements._form')<div class="mt-4 d-flex gap-2"><button class="btn btn-success" type="submit">{{ __('Save changes') }}</button><a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a></div></form>
@endsection
