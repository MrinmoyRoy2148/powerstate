@extends('layouts.app')

@section('title', __('Forgot your password?'))

@section('content')
    <section class="mx-auto max-w-lg rounded-3xl border border-slate-200 bg-white p-7 shadow-sm sm:p-9">
        <p class="text-sm font-bold text-emerald-700">{{ __('PowerStat BD account') }}</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-950">{{ __('Reset your password') }}</h1>
        <p class="mt-3 text-sm leading-6 text-slate-600">{{ __('Enter your account email address and we will send a password reset link if it matches an account.') }}</p>

        @if (session('status'))
            <p class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900" role="status">{{ session('status') }}</p>
        @endif

        @if ($errors->any())
            <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-7 grid gap-5">
            @csrf
            <div>
                <label for="email" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Email address') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
            </div>
            <button class="rounded-xl bg-emerald-600 px-5 py-3 font-bold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" type="submit">{{ __('Send reset link') }}</button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-600">
            <a class="font-bold text-emerald-700 hover:text-emerald-800" href="{{ route('login') }}">{{ __('Back to login') }}</a>
        </p>
    </section>
@endsection
