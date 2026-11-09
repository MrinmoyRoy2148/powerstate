@extends('layouts.admin')
@section('title', __('Admin account'))
@section('content')
    <x-admin-page-header :title="__('Admin account')" :description="__('Manage your administrator profile and sign-in password.')" />
    <div class="row g-4">
        <div class="col-lg-6">
            <form method="POST" action="{{ route('admin.profile.update') }}" class="card card-body shadow-sm">
                @csrf
                @method('PUT')
                <h2 class="h5 mb-3">{{ __('Profile details') }}</h2>
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input id="name" name="name" value="{{ old('name', $admin->name) }}" required maxlength="255" class="form-control @error('name') is-invalid @enderror">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email address') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $admin->email) }}" required maxlength="255" class="form-control @error('email') is-invalid @enderror">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button class="btn btn-success align-self-start" type="submit">{{ __('Save profile') }}</button>
            </form>
        </div>
        <div class="col-lg-6">
            <form method="POST" action="{{ route('admin.profile.password.update') }}" class="card card-body shadow-sm">
                @csrf
                @method('PUT')
                <h2 class="h5 mb-3">{{ __('Change password') }}</h2>
                <div class="mb-3">
                    <label for="current_password" class="form-label">{{ __('Current password') }}</label>
                    <input id="current_password" name="current_password" type="password" required autocomplete="current-password" class="form-control @error('current_password') is-invalid @enderror">
                    @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('New password') }}</label>
                    <input id="password" name="password" type="password" required minlength="12" autocomplete="new-password" class="form-control @error('password') is-invalid @enderror">
                    <div class="form-text">{{ __('Use at least 12 characters.') }}</div>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">{{ __('Confirm new password') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="12" autocomplete="new-password" class="form-control">
                </div>
                <button class="btn btn-success align-self-start" type="submit">{{ __('Update password') }}</button>
            </form>
        </div>
    </div>
@endsection
