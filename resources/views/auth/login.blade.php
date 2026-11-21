@extends('layouts.app')

@section('title', __('Log in'))

@section('content')
    @include('auth.partials.user-access', ['mode' => 'login'])
@endsection
