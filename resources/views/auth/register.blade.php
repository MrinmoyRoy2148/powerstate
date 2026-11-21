@extends('layouts.app')

@section('title', __('Register'))

@section('content')
    @include('auth.partials.user-access', ['mode' => 'register'])
@endsection
