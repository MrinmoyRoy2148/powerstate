@extends('layouts.app')

@section('title', __('Log in'))

@section('content')
    <section class="mx-auto max-w-lg rounded-3xl border border-slate-200 bg-white p-7 shadow-sm sm:p-9">
        <p class="text-sm font-bold text-emerald-700">{{ __('PowerStat BD account') }}</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-950">{{ __('Sign in to your account') }}</h1>

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

        <form method="POST" action="{{ route('login.store') }}" class="mt-7 grid gap-5">
            @csrf
            <div>
                <label for="email" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Email address') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
            </div>
            <div>
                <label for="password" class="mb-1 block text-sm font-bold text-slate-800">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" required autocomplete="current-password" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                <a class="mt-2 inline-block text-sm font-semibold text-emerald-700 hover:text-emerald-800" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
            </div>
            <button class="rounded-xl bg-emerald-600 px-5 py-3 font-bold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" type="submit">{{ __('Log in') }}</button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-600">
            {{ __('New to PowerStat BD?') }}
            <a class="font-bold text-emerald-700 hover:text-emerald-800" href="{{ route('register') }}">{{ __('Create an account') }}</a>
        </p>
    </section>
@endsection
